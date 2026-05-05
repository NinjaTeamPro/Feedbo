<?php
namespace Feedbo\Page;

use Feedbo\Classes\Board;

defined( 'ABSPATH' ) || exit;

class BoardUserAdmin {

	protected static $instance = null;

	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_feedbo_get_board_users',   array( $this, 'ajax_get_board_users' ) );
		add_action( 'wp_ajax_feedbo_bulk_add_users',    array( $this, 'ajax_bulk_add_users' ) );
		add_action( 'wp_ajax_feedbo_add_single_user',   array( $this, 'ajax_add_single_user' ) );
		add_action( 'wp_ajax_feedbo_update_user_role',  array( $this, 'ajax_update_user_role' ) );
		add_action( 'wp_ajax_feedbo_delete_board_user', array( $this, 'ajax_delete_board_user' ) );
	}

	public function enqueue_scripts( $hook ) {
		if ( strpos( $hook, 'feedbo-user' ) === false ) {
			return;
		}
		wp_enqueue_script(
			'feedbo-board-users-admin',
			FB_PLUGIN_URL . 'assets/js/board-users-admin.js',
			array( 'jquery' ),
			FB_PLUGIN_VERSION,
			true
		);
		wp_localize_script( 'feedbo-board-users-admin', 'feedboAdmin', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'feedbo_board_users_nonce' ),
		) );
	}

	public function ajax_get_board_users() {
		check_ajax_referer( 'feedbo_board_users_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}
		$board_id = (int) $_POST['board_id'];
		$board    = new Board();
		wp_send_json_success( $board->getUserInBoard( $board_id ) );
	}

	public function ajax_bulk_add_users() {
		check_ajax_referer( 'feedbo_board_users_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}

		$board_ids  = array_map( 'absint', (array) ( $_POST['board_ids'] ?? array() ) );
		$board_ids  = array_filter( $board_ids );
		$role       = sanitize_text_field( $_POST['role'] ?? 'Moderator' );
		$role_map   = array( 'Owner' => 1, 'Admin' => 2, 'Moderator' => 3, 'Member' => 4 );
		if ( ! array_key_exists( $role, $role_map ) ) {
			$role = 'Moderator';
		}
		$level      = $role_map[ $role ];
		$emails_raw = sanitize_textarea_field( $_POST['emails'] ?? '' );
		$emails     = array_filter( array_map( 'trim', preg_split( '/[\r\n,]+/', $emails_raw ) ) );

		if ( empty( $board_ids ) || empty( $emails ) ) {
			wp_send_json_error( 'No boards or emails provided.' );
		}

		global $wpdb;
		$term_users = $wpdb->prefix . 'term_users';
		$results    = array();

		foreach ( $board_ids as $board_id ) {
			$board_name = $this->get_board_name( $board_id );
			foreach ( $emails as $email ) {
				if ( ! is_email( $email ) ) {
					$results[] = array( 'board_name' => $board_name, 'email' => $email, 'status' => 'invalid' );
					continue;
				}
				$exists = (int) $wpdb->get_var( $wpdb->prepare(
					"SELECT COUNT(*) FROM $term_users WHERE term_id = %d AND user_email = %s",
					$board_id, $email
				) );
				if ( $exists > 0 ) {
					$results[] = array( 'board_name' => $board_name, 'email' => $email, 'status' => 'exists' );
					continue;
				}
				$wpdb->insert( $term_users, array(
					'term_id'    => $board_id,
					'user_email' => $email,
					'term_role'  => $role,
					'level'      => $level,
					'status'     => 'pending',
				) );
				$this->send_invite_email( $email, $board_id, $board_name );
				$results[] = array( 'board_name' => $board_name, 'email' => $email, 'status' => 'added' );
			}
		}

		wp_send_json_success( array( 'results' => $results ) );
	}

	public function ajax_add_single_user() {
		check_ajax_referer( 'feedbo_board_users_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}
		$board_id = (int) $_POST['board_id'];
		$email    = sanitize_email( $_POST['email'] ?? '' );
		$role     = sanitize_text_field( $_POST['role'] ?? 'Moderator' );
		$role_map = array( 'Owner' => 1, 'Admin' => 2, 'Moderator' => 3, 'Member' => 4 );
		if ( ! is_email( $email ) ) {
			wp_send_json_error( 'Invalid email address.' );
		}
		if ( ! array_key_exists( $role, $role_map ) ) {
			$role = 'Moderator';
		}
		global $wpdb;
		$term_users = $wpdb->prefix . 'term_users';
		$exists = (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM $term_users WHERE term_id = %d AND user_email = %s",
			$board_id, $email
		) );
		if ( $exists > 0 ) {
			wp_send_json_error( 'User is already in this board.' );
		}
		$wpdb->insert( $term_users, array(
			'term_id'    => $board_id,
			'user_email' => $email,
			'term_role'  => $role,
			'level'      => $role_map[ $role ],
			'status'     => 'pending',
		) );
		$this->send_invite_email( $email, $board_id );
		wp_send_json_success( ( new Board() )->getUserInBoard( $board_id ) );
	}

	public function ajax_update_user_role() {
		check_ajax_referer( 'feedbo_board_users_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}
		$user_id  = (int) $_POST['user_id'];
		$role     = sanitize_text_field( $_POST['role'] ?? '' );
		$role_map = array( 'Owner' => 1, 'Admin' => 2, 'Moderator' => 3, 'Member' => 4 );
		if ( ! array_key_exists( $role, $role_map ) ) {
			wp_send_json_error( 'Invalid role.' );
		}
		global $wpdb;
		$wpdb->update(
			$wpdb->prefix . 'term_users',
			array( 'term_role' => $role, 'level' => $role_map[ $role ] ),
			array( 'ID' => $user_id )
		);
		wp_send_json_success();
	}

	public function ajax_delete_board_user() {
		check_ajax_referer( 'feedbo_board_users_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}
		$user_id = (int) $_POST['user_id'];
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . 'term_users', array( 'ID' => $user_id ) );
		wp_send_json_success();
	}

	private function get_board_name( $board_id ) {
		global $wpdb;
		$meta = $wpdb->get_row( $wpdb->prepare(
			"SELECT meta_value FROM {$wpdb->prefix}termmeta WHERE term_id = %d AND meta_key = 'board_Setting'",
			$board_id
		), ARRAY_A );
		if ( ! $meta ) {
			return 'Board #' . $board_id;
		}
		$setting = maybe_unserialize( $meta['meta_value'] );
		return $setting['name'] ?? 'Board #' . $board_id;
	}

	private function send_invite_email( $email, $board_id, $board_name = '' ) {
		if ( ! $board_name ) {
			$board_name = $this->get_board_name( $board_id );
		}
		$user_name = wp_get_current_user()->display_name ?: 'Admin';
		$action     = email_exists( $email ) ? 'login' : 'register';
		$url        = site_url( '/wp-login.php?action=' . $action . '&email=' . rawurlencode( $email ) . '&board=' . $board_id );
		$subject    = '[' . $board_name . '] ' . $user_name . ' added you to the team';
		$body       = '<html><body>'
			. '<p>Hi,</p>'
			. '<p>' . esc_html( $user_name ) . ' added you to the <strong>' . esc_html( $board_name ) . '</strong> board on Feedbo.</p>'
			. '<p><a href="' . esc_url( $url ) . '">Click here to access the board</a></p>'
			. '</body></html>';
		wp_mail( $email, $subject, $body, array( 'Content-Type: text/html; charset=UTF-8' ) );
	}
}
