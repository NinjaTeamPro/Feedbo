<?php

namespace Feedbo\Page;

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'GoogleOnTab' ) ) {
	class GoogleOnTab {



		protected static $instance = null;

		public $google_client_id;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		private function __construct() {

			add_action( 'login_enqueue_scripts', array( $this, 'login_enqueue_scripts' ), 10 );
			add_filter( 'get_avatar_url', array( $this, 'get_avatar_url' ), 10, 3 );

			add_action( 'wp_ajax_feebo_connect_google_on_tab', array( $this, 'feebo_connect_google_on_tab' ) );
			add_action( 'wp_ajax_nopriv_feebo_connect_google_on_tab', array( $this, 'feebo_connect_google_on_tab' ) );
		}

		public function login_enqueue_scripts() {
			wp_register_script( 'feebo-one-tap-client-js', 'https://accounts.google.com/gsi/client', array(), FB_PLUGIN_VERSION, true );
			wp_enqueue_script( 'feebo-one-tap-client-js' );

			wp_enqueue_script( 'frontend-js', FB_PLUGIN_URL . 'assets/js/frontend.js', array( 'jquery' ), FB_PLUGIN_VERSION, true );
			wp_localize_script(
				'frontend-js',
				'feedboData',
				array(
					'login_page' => in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ? 'yes' : 'no',
					'domain'     => home_url( '/' ),
					'ajaxurl'    => admin_url( 'admin-ajax.php' ),
					'nonce'      => wp_create_nonce( 'feebo-login-nonce' ),
				)
			);
			if ( ! is_user_logged_in() ) {
				wp_enqueue_script( 'feebo-google-tap', FB_PLUGIN_URL . 'assets/js/google-tab.js', array( 'jquery' ), FB_PLUGIN_VERSION, true );
			}

		}

		public function register_or_login_user( $payload, $redirect_uri ) {
			$user_email = sanitize_email( $payload['email'] );
			$wp_user    = get_user_by( 'email', $user_email );

			if ( $wp_user ) {
				$user_id = $wp_user->ID;
				$this->login_user( $user_id, $payload, esc_url_raw( $redirect_uri ) );
			} else {
				$this->register_user( $payload, esc_url_raw( $redirect_uri ) );
			}

		}


		public function get_avatar_url( $avatar, $id_or_email, $args = null ) {
			if ( is_numeric( $id_or_email ) && $id_or_email > 0 ) {    // Check if we have an user identifier
				$user_id = $id_or_email;
			} elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ) {    // Check if we have an user email
				$user_id = $user->ID;
			} elseif ( is_object( $id_or_email ) && property_exists( $id_or_email, 'user_id' ) && is_numeric( $id_or_email->user_id ) ) {     // Check if we have an user object
				$user_id = $id_or_email->user_id;
			} else {        // None found
				$user_id = false;
			}

			if ( $user_id ) {
				$feedbo_avatar = get_user_meta( $user_id, 'feedbo_user_avatar', true );
				if ( ! empty( $feedbo_avatar ) ) {
					return $feedbo_avatar;
				}
			}
			return $avatar;
		}

		public function update_user( $payload, $user_id ) {
			$given_name = $payload['given_name'];
			$user_data  = array(
				'ID'           => $user_id,
				'first_name'   => $given_name,
				'last_name'    => $payload['family_name'],
				'display_name' => $payload['name'],
			);
			$avatar_url = $payload['picture'];
			wp_update_user( $user_data );
			update_user_meta( $user_id, 'feedbo_user_avatar', esc_url_raw( $avatar_url ) );
			update_user_meta( $user_id, 'nickname', $given_name );
		}

		public function register_user( $payload, $redirect_uri ) {

			$username_parts = array();
			if ( isset( $payload['given_name'] ) ) {
				$username_parts[] = sanitize_user( $payload['given_name'], true );
			}

			if ( isset( $payload['family_name'] ) ) {
				$username_parts[] = sanitize_user( $payload['family_name'], true );
			}

			if ( empty( $username_parts ) ) {
				$email_parts      = explode( '@', $payload['email'] );
				$email_username   = $email_parts[0];
				$username_parts[] = sanitize_user( $email_username, true );
			}

			$username = strtolower( implode( '.', $username_parts ) );

			$default_user_name = $username;
			$suffix            = 1;
			while ( username_exists( $username ) ) {
				$username = $default_user_name . $suffix;
				$suffix++;
			}

			$random_password = wp_generate_password();
			$user_id         = wp_create_user( sanitize_user( $username ), $random_password, $payload['email'] );
			$this->update_user( $payload, $user_id );
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id, true );

		}

		public function login_user( $user_id, $payload, $redirect_uri ) {
			$this->update_user( $payload, $user_id );
			wp_clear_auth_cookie();
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id, true );

		}

		public function feebo_connect_google_on_tab() {
			check_ajax_referer( 'feebo-login-nonce', 'nonce', true );
			if ( isset( $_POST['payload'] ) ) {
				$payload      = $_POST['payload'];
				$redirect_uri = $_POST['redirect_uri'];
				$this->register_or_login_user( $payload, $redirect_uri );
				if ( isset( $_COOKIE['feedbo_previous_url'] ) ) {
					$feedbo_previous_url = sanitize_text_field( $_COOKIE['feedbo_previous_url'] );
					if ( ! str_contains( $feedbo_previous_url, 'wp-login.php' ) && ! str_contains( $feedbo_previous_url, 'wp-register.php' ) ) {
						$redirect_uri = $feedbo_previous_url;
					}
				}

				try {
					wp_send_json_success(
						array(
							'success'      => true,
							'redirect_uri' => $redirect_uri,
						)
					);
				} catch ( \Exception $e ) {
					wp_send_json_error( $e );
				}
			}
		}

	}
}
