<?php
namespace Feedbo\Api;

use Feedbo\Classes\Comment;
use Feedbo\Classes\PageLoad;
use Feedbo\Classes\Captcha;

defined( 'ABSPATH' ) || exit;
class AddApi extends \WP_REST_Controller {

	protected static $instance = null;
	private static $current_user;
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	public function __construct() {
		self::$current_user = wp_get_current_user();
		add_action( 'rest_api_init', array( $this, 'registerRoutes' ) );
	}
	public function registerRoutes() {
		register_rest_route(
			'v1',
			'wp_add_post',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'addPost' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_add_vote',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'addVote' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_add_comment',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'addComment' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_add_category',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'addCategory' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_add_user',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'addUser' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_create_user',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'createUser' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_add_down_vote',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'addDownVote' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_add_user_upvote',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'addNewUserVote' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}
	public function addPost( $request ) {
		$user_id     = array( self::$current_user->ID );
		$user_id_str = implode( ' , ', $user_id );
		global $wpdb;
		$req     = wp_unslash( $request->get_params() );
		$page    = PageLoad::getInstance();
		$boardId = $page->getTermByKey( $req['id'] );
		$table1  = $wpdb->prefix . 'posts';
		$table2  = $wpdb->prefix . 'postmeta';
		$table3  = $wpdb->prefix . 'term_relationships';
		$table4  = $wpdb->prefix . 'term_taxonomy';
		$table5  = $wpdb->prefix . 'comments';
		$table6  = $wpdb->prefix . 'commentmeta';
		$table7  = $wpdb->prefix . 'board_activity';

		if ( ! Captcha::isValid( $req['captcha'] ) ) {
			return new \WP_REST_Response( 'Validation failed', 500 );
		}

		$taxonomy_id = $wpdb->get_var(
			$wpdb->prepare( "SELECT term_taxonomy_id FROM {$table4} WHERE term_id=%d", $boardId )
		);
		$data1       = array(
			'post_title'    => $req['values']['title'],
			'post_author'   => self::$current_user->ID,
			'post_content'  => $req['values']['content'],
			'post_type'     => 'vote',
			'post_date'     => date( 'Y-m-d h:i:sa' ),
			'comment_count' => 1,
		);
		$format      = array( '%s', '%d', '%s' );
		$wpdb->insert( $table1, $data1, $format );
		$post_id = $wpdb->insert_id;
		$data4   = array(
			'post_id'    => $post_id,
			'meta_key'   => 'user_created',
			'meta_value' => self::$current_user->user_login,
		);
		$wpdb->insert( $table2, $data4 );
		$data5 = array(
			'post_id'    => $post_id,
			'meta_key'   => 'user_voted_ids',
			'meta_value' => $user_id_str,
		);
		$wpdb->insert( $table2, $data5 );
		$data5 = array(
			'post_id'    => $post_id,
			'meta_key'   => 'user_down_voted_ids',
			'meta_value' => null,
		);
		$wpdb->insert( $table2, $data5 );

		$arr           = get_user_meta( self::$current_user->ID, 'notification_setting' );
		$userSubsribed = array();
		if ( $arr[0]['OwnPost'] == true ) {
			array_push( $userSubsribed, self::$current_user->ID );
		}
		$data11 = array(
			'post_id'    => $post_id,
			'meta_key'   => 'user_subscribed',
			'meta_value' => maybe_serialize( $userSubsribed ),
		);
		$wpdb->insert( $table2, $data11 );
		$data3 = array(
			'object_id'        => $post_id,
			'term_taxonomy_id' => $taxonomy_id,
		);
		$wpdb->insert( $table3, $data3 );
		$data6 = array(
			'comment_post_ID' => $post_id,
			'comment_content' => $req['values']['content'],
			'comment_author'  => self::$current_user->display_name,
			'comment_date'    => date( 'Y-m-d h:i:sa' ),
			'user_id'         => self::$current_user->ID,
		);
		$wpdb->insert( $table5, $data6 );
		$comment_id = $wpdb->insert_id;
		$data8      = array(
			'comment_id' => $comment_id,
			'meta_key'   => 'users_like',
		);
		$wpdb->insert( $table6, $data8 );
		$imagelist = isset( $req['values']['file'] ) ? $req['values']['file'] : array();
		$image     = array();
		foreach ( $imagelist as $key => $value ) {
			array_push( $image, $value['response']['url'] );
		}
		$data7 = array(
			'comment_id' => $comment_id,
			'meta_key'   => 'attachment_id',
			'meta_value' => maybe_serialize( $image ),
		);
		$wpdb->insert( $table6, $data7 );
		$data9 = array(
			'term_id'       => $boardId,
			'activity_name' => 'posted',
			'post_id'       => $post_id,
			'activity_date' => date( 'Y-m-d h:i:sa' ),
			'user_id'       => self::$current_user->ID,
		);
		$wpdb->insert( $table7, $data9 );
		$data10 = array(
			'comment_id' => $comment_id,
			'meta_key'   => 'is_post',
			'meta_value' => true,
		);
		$wpdb->insert( $table6, $data10 );
		$post          = get_post( $post_id );
		$userVote      = get_post_meta( $post_id, 'user_voted_ids' );
		$userDownVote  = get_post_meta( $post_id, 'user_down_voted_ids' );
		$userSubscribe = get_post_meta( $post_id, 'user_subscribed' );
		$result        = (object) array(
			'term_id'          => $boardId,
			'post_id'          => $post_id,
			'post_author'      => $post->post_author,
			'post_title'       => $post->post_title,
			'post_content'     => $post->post_content,
			'post_status'      => $post->post_content_filtered != '' ? $post->post_content_filtered : 'Unassigned',
			'post_date'        => $post->post_date,
			'post_slug'        => sanitize_title( $post->post_title ),
			'display_name'     => self::$current_user->display_name,
			'comment_status'   => $post->comment_status,
			'vote_ids'         => $userVote[0],
			'down_vote_ids'    => $userDownVote[0],
			'subscribe_ids'    => $userSubscribe[0] != null ? $userSubscribe[0] : array(),
			'vote_length'      => ( $userVote[0] != '' || $userVote[0] != null ) ? count( explode( ' , ', $userVote[0] ) ) : 0,
			'down_vote_length' => ( $userDownVote[0] != '' || $userDownVote[0] != null ) ? count( explode( ' , ', $userDownVote[0] ) ) : 0,
		);
		return new \WP_REST_Response( $result, 200 );
	}
	public function addVote( $request ) {
		global $wpdb;
		$table = $wpdb->prefix . 'board_activity';
		$req   = wp_unslash( $request->get_params() );

		if ( ! Captcha::isValid( $req['captcha'] ) ) {
			return new \WP_REST_Response( 'Validation failed', 500 );
		}

		update_post_meta( $req['post']['post_id'], 'user_voted_ids', $req['post']['vote_ids'] );
		if ( isset( $req['post']['down_vote_ids'] ) ) {
			update_post_meta( $req['post']['post_id'], 'user_down_voted_ids', $req['post']['down_vote_ids'] );
		}
		$data = array(
			'term_id'       => $req['post']['term_id'],
			'post_id'       => $req['post']['post_id'],
			'activity_name' => $req['checkVote'] == false ? 'upvoted' : 'delete vote',
			'activity_date' => date( 'Y-m-d h:i:sa' ),
			'user_id'       => $req['userVote'],
		);
		$wpdb->insert( $table, $data );
		return new \WP_REST_Response( $req['checkVote'] == false ? 'Vote success' : 'Vote has been canceled', 200 );
	}
	public function addDownVote( $request ) {
		global $wpdb;
		$table = $wpdb->prefix . 'board_activity';
		$req   = wp_unslash( $request->get_params() );
		if ( isset( $req['post']['vote_ids'] ) ) {
			update_post_meta( $req['post']['post_id'], 'user_voted_ids', $req['post']['vote_ids'] );
		}
		update_post_meta( $req['post']['post_id'], 'user_down_voted_ids', $req['post']['down_vote_ids'] );
		$data = array(
			'term_id'       => $req['post']['term_id'],
			'post_id'       => $req['post']['post_id'],
			'activity_name' => $req['checkDownVote'] == false ? 'downvoted' : 'delete downvoted',
			'activity_date' => date( 'Y-m-d h:i:sa' ),
			'user_id'       => $req['userVote'],
		);
		$wpdb->insert( $table, $data );
		return new \WP_REST_Response( $req['checkDownVote'] == false ? 'Downvote success' : 'Downvote has been canceled', 200 );
	}
	public function addComment( $request ) {
		global $wpdb;
		$req = wp_unslash( $request->get_params() );

		if ( ! Captcha::isValid( $req['comment']['captcha'] ) ) {
			return new \WP_REST_Response( array( 'message' => 'Validation failed' ), 500 );
		}

		$table4 = $wpdb->prefix . 'board_activity';
		$table5 = $wpdb->prefix . 'user_notification';
		$table6 = $wpdb->prefix . 'postmeta';
		// Check @mention
		$page       = PageLoad::getInstance();
		$termMeta   = $page->getTermMeta( $req['comment']['termId'] );
		$boardUrl   = $termMeta['setting']['board_URL'];
		$project_id = $req['comment']['termId'];
		$term_users = $wpdb->prefix . 'term_users';
		$users      = $wpdb->prefix . 'users';
		$sql        = "SELECT {$term_users}.*, {$users}.*
                FROM {$term_users}
                LEFT JOIN {$users} ON {$users}.user_email = {$term_users}.user_email
                WHERE {$term_users}.term_id = $project_id
                ORDER BY {$term_users}.ID";
		$data       = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );

		$userTeam = array();
		$mention  = array();
		foreach ( $data as $key => $val ) {
			if ( $val['display_name'] != null ) {
				array_push(
					$userTeam,
					array(
						'id'         => $val['ID'],
						'user_name'  => '@' . $val['display_name'],
						'user_email' => $val['user_email'],
					)
				);
			}
		}
		foreach ( $userTeam as $key => $val ) {
			$pos              = strpos( $req['comment']['values']['comment'], $val['user_name'] );
			$val['user_name'] = substr( $val['user_name'], 1 );
			if ( $pos !== false && ! in_array( $val, $mention ) ) {
				array_push( $mention, $val );
			}
		}
		$userId = (int) $req['comment']['author'];
		$arr    = get_user_meta( $userId, 'notification_setting' );
		// Send mail if exist mention
		if ( $mention != null ) {
			foreach ( $mention as $key => $val ) {
				if ( self::$current_user->display_name != $val['user_name'] ) {
					$userNotification = get_user_meta( $val['id'], 'notification_setting' );
					if ( $userNotification[0]['sendEmail'] == true ) {
						$subject = self::$current_user->display_name . ' tag you on comment';
						$to      = $val['user_email'];
						$body    = '<html>
                            <body topmargin="25">
                            <div>Hi ' . $val['user_name'] . ',</div>
                            <div>' . self::$current_user->display_name . ' tag you on comment. Click link below to open website</div>
                            <div><a href="' . $boardUrl . '" target="_blank">' . $boardUrl . '</a></div>
                            </body>
                        </html>';
						$headers = array( 'Content-Type: text/html; charset=UTF-8' );
						wp_mail( $to, $subject, $body, $headers );
					}
				}
			}
		}

		// Check user subscribed on post
		$sql          = "SELECT {$table6}.meta_value
                FROM {$table6}
                WHERE {$table6}.post_id = {$req['comment']['id']} AND {$table6}.meta_key = 'user_subscribed'";
		$data         = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$arrSubscribe = unserialize( $data[0]['meta_value'] );
		// Send mail for user subscribed
		$arrUser = array();
		foreach ( $arrSubscribe as $key => $val ) {
			if ( $val != self::$current_user->ID ) {
				$user = get_user_by( 'id', $val );
				array_push(
					$arrUser,
					array(
						'user_name'  => $user->display_name,
						'user_email' => $user->user_email,
					)
				);
			}
		}
		foreach ( $arrUser as $key => $val ) {
			$subject = self::$current_user->display_name . ' comment on post you subscribed ';
			$to      = $val['user_email'];
			$body    = '<html>
                      <body topmargin="25">
                      <div>Hi ' . $val['user_name'] . ',</div>
                      <div>' . self::$current_user->display_name . ' comment "' . $req['comment']['values']['comment'] . '" on for post you subscribed. Click link below to open website</div>
                      <div><a href="' . $boardUrl . '" target="_blank">' . $boardUrl . '</a></div>
                      </body>
                  </html>';
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			wp_mail( $to, $subject, $body, $headers );
		}
		// Add user subscribed
		if ( ! in_array( self::$current_user->ID, $arrSubscribe ) && self::$current_user->ID != 0 ) {
			$arrNotification = get_user_meta( self::$current_user->ID, 'notification_setting' );
			if ( $arr[0]['Comment'] == true ) {
				array_push( $arrSubscribe, self::$current_user->ID );
				$data  = array( 'meta_value' => maybe_serialize( $arrSubscribe ) );
				$where = array(
					'post_id'  => $req['comment']['id'],
					'meta_key' => 'user_subscribed',
				);
				$wpdb->update( $table6, $data, $where );
			}
		}

		if ( comments_open( $req['comment']['id'] ) ) {
			$imagelist = isset( $req['comment']['values']['file'] ) ? $req['comment']['values']['file'] : array();
			$image     = array();
			if ( count( $imagelist ) > 0 ) {
				foreach ( $imagelist as $key => $value ) {
					array_push( $image, $value['response']['url'] );
				}
			}
			$dataComment = array(
				'comment_post_ID'      => $req['comment']['id'],
				'comment_content'      => $req['comment']['values']['comment'],
				'user_id'              => self::$current_user->ID,
				'comment_author'       => self::$current_user->display_name,
				'comment_author_email' => self::$current_user->user_email,
				'comment_author_url'   => self::$current_user->user_url,
				'comment_meta'         => array(
					'attachment_id' => $image,
					'users_like'    => null,
				),
			);

			$comment_id = wp_insert_comment( $dataComment );
			if ( ! is_wp_error( $comment_id ) ) {
				// Add board activity
				$data5 = array(
					'term_id'       => $project_id,
					'post_id'       => $req['comment']['id'],
					'activity_name' => 'commented',
					'activity_date' => date( 'Y-m-d h:i:sa' ),
					'user_id'       => self::$current_user->ID,
				);
				$wpdb->insert( $table4, $data5 );
				$comment  = Comment::getInstance();
				$comments = $comment->getComments( $req['comment']['id'] );
				$result   = array(
					'comments' => $comments,
					'message'  => 'Add new comment success',
				);
				return new \WP_REST_Response( $result, 200 );
			} else {
				$result = array(
					'message' => 'Add comment failed',
				);
				return new \WP_REST_Response( $result, 500 );
			}
		}
		$result = array(
			'message' => 'Add comment failed',
		);
		return new \WP_REST_Response( $result, 500 );
	}
	public function addCategory( $request ) {
		global $wpdb;
		$req     = wp_unslash( $request->get_params() );
		$table1  = $wpdb->prefix . 'terms';
		$table2  = $wpdb->prefix . 'posts';
		$table3  = $wpdb->prefix . 'postmeta';
		$table4  = $wpdb->prefix . 'term_relationships';
		$table5  = $wpdb->prefix . 'term_taxonomy';
		$table6  = $wpdb->prefix . 'comments';
		$table7  = $wpdb->prefix . 'commentmeta';
		$table8  = $wpdb->prefix . 'termmeta';
		$table9  = $wpdb->prefix . 'board_activity';
		$table10 = $wpdb->prefix . 'term_users';
		$data1   = array(
			'name' => $req['values']['category'],
			'slug' => sanitize_title( $req['values']['category'] ),
		);
		$wpdb->insert( $table1, $data1 );
		$term_id = $wpdb->insert_id;
		$data9   = array(
			'term_id'    => $term_id,
			'meta_key'   => 'user_created',
			'meta_value' => self::$current_user->ID,
		);
		$wpdb->insert( $table8, $data9 );

		$settingArray = array(
			'name'         => $req['values']['category'],
			'description'  => 'Let us know how we can improve. Vote on existing ideas or suggest new ones.',
			'button_text'  => 'Make a suggestion',
			'default_sort' => 'vote',
			'board_URL'    => site_url() . FB_URL_BOARD . $term_id,
			'visibility'   => 'public',
			'features'     => 'anonymous , roadmap',
		);
		$data9        = array(
			'term_id'    => $term_id,
			'meta_key'   => 'board_Setting',
			'meta_value' => maybe_serialize( $settingArray ),
		);
		$wpdb->insert( $table8, $data9 );
		$colorArray = array(
			'header'     => '#1890ff',
			'title'      => '#251016',
			'text'       => '#494949',
			'accent'     => '#1890ff',
			'background' => '#f0f2f5',
		);
		$data9      = array(
			'term_id'    => $term_id,
			'meta_key'   => 'theme_color',
			'meta_value' => maybe_serialize( $colorArray ),
		);
		$wpdb->insert( $table8, $data9 );
		$statusArray = array(
			'Archived'    => '#108ee9',
			'Completed'   => '#87d068',
			'In Progress' => '#f50',
			'Planned'     => '#2db7f5',
			'Unassigned'  => '#a4a4a4',
		);
		$data9       = array(
			'term_id'    => $term_id,
			'meta_key'   => 'status_color',
			'meta_value' => maybe_serialize( $statusArray ),
		);
		$wpdb->insert( $table8, $data9 );
		$logoFaviconArray = array(
			'logo'    => '',
			'favicon' => '',
		);
		$data9            = array(
			'term_id'    => $term_id,
			'meta_key'   => 'logo_favicon',
			'meta_value' => maybe_serialize( $logoFaviconArray ),
		);
		$wpdb->insert( $table8, $data9 );
		$data10 = array(
			'term_id'    => $term_id,
			'user_email' => self::$current_user->user_email,
			'term_role'  => 'Owner',
			'level'      => 1,
			'status'     => 'accept',
		);
		$wpdb->insert( $table10, $data10 );
		$data2 = array(
			array(
				'post_title'    => 'Welcome to Feedbo Project',
				'post_author'   => self::$current_user->ID,
				'post_content'  => 'Hi there! Feedbo is a beautiful, collaborative place for all your user requests – no more outdated spreadsheets or chaotic Trello boards. It’s super easy to get started and to master it! ',
				'post_type'     => 'vote',
				'post_date'     => date( 'Y-m-d h:i:sa' ),
				'comment_count' => 1,
			),

			array(
				'post_title'    => 'Getting Started',
				'post_author'   => self::$current_user->ID,
				'post_content'  => ' Vote on existing ideas or suggest new ones.',
				'post_type'     => 'vote',
				'post_date'     => date( 'Y-m-d h:i:sa' ),
				'comment_count' => 1,
			),
		);
		$data5 = array(
			'term_id'  => $term_id,
			'taxonomy' => 'category',
			'count'    => 2,
		);
		$wpdb->insert( $table5, $data5 );
		$term_taxonomy_id = $wpdb->insert_id;
		foreach ( $data2 as $value ) {
			$wpdb->insert( $table2, $value );
			$post_id = $wpdb->insert_id;
			$data4   = array(
				'post_id'    => $post_id,
				'meta_key'   => 'user_created',
				'meta_value' => self::$current_user->user_login,
			);
			$wpdb->insert( $table3, $data4 );
			$data5 = array(
				'post_id'    => $post_id,
				'meta_key'   => 'user_voted_ids',
				'meta_value' => '0',
			);
			$wpdb->insert( $table3, $data5 );
			$data5 = array(
				'post_id'    => $post_id,
				'meta_key'   => 'user_down_voted_ids',
				'meta_value' => null,
			);
			$wpdb->insert( $table3, $data5 );
			$data10 = array(
				'term_id'       => $term_id,
				'post_id'       => $post_id,
				'activity_name' => 'posted',
				'activity_date' => date( 'Y-m-d h:i:sa' ),
				'user_id'       => self::$current_user->ID,
			);
			$wpdb->insert( $table9, $data10 );
			$data6 = array(
				'object_id'        => $post_id,
				'term_taxonomy_id' => $term_taxonomy_id,
			);
			$wpdb->insert( $table4, $data6 );
			$data7 = array(
				'comment_post_ID' => $post_id,
				'comment_content' => $value['post_content'],
				'comment_author'  => self::$current_user->display_name,
				'comment_date'    => date( 'Y-m-d h:i:sa' ),
				'user_id'         => self::$current_user->ID,
			);
			$wpdb->insert( $table6, $data7 );
			$comment_id = $wpdb->insert_id;
			$data8      = array(
				'comment_id' => $comment_id,
				'meta_key'   => 'users_like',
			);
			$wpdb->insert( $table7, $data8 );
			$data8 = array(
				'comment_id' => $comment_id,
				'meta_key'   => 'attachment_id',
			);
			$wpdb->insert( $table7, $data8 );
			$data8 = array(
				'comment_id' => $comment_id,
				'meta_key'   => 'is_post',
				'meta_value' => true,
			);
			$wpdb->insert( $table7, $data8 );
			$arr           = get_user_meta( self::$current_user->ID, 'notification_setting' );
			$userSubsribed = array();
			if ( $arr[0]['OwnPost'] == true ) {
				array_push( $userSubsribed, self::$current_user->ID );
			}
			$data11 = array(
				'post_id'    => $post_id,
				'meta_key'   => 'user_subscribed',
				'meta_value' => maybe_serialize( $userSubsribed ),
			);
			$wpdb->insert( $table3, $data11 );
		}
		return new \WP_REST_Response( $term_id, 200 );

	}
	public function addUser( $request ) {
		global $wpdb;
		$req        = wp_unslash( $request->get_params() );
		$page       = PageLoad::getInstance();
		$term_id    = $page->getTermByKey( $req['termId'] );
		$termmeta   = $wpdb->prefix . 'termmeta';
		$term_users = $wpdb->prefix . 'term_users';
		$users      = $wpdb->prefix . 'users';
		$count      = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) from $term_users where term_id = %d and user_email = %s", $term_id, $req['email'] ) );
		if ( (int) $count == 0 ) {
			$data = array(
				'term_id'    => $term_id,
				'user_email' => $req['email'],
				'term_role'  => 'Moderator',
				'level'      => 3,
				'status'     => 'pending',
			);
			$wpdb->insert( $term_users, $data );
			$sql    = "SELECT {$termmeta}.* FROM {$termmeta}
                  WHERE {$termmeta}.term_id = {$term_id} AND {$termmeta}.meta_key = 'board_Setting'";
			$data   = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
			$array  = unserialize( $data[0]['meta_value'] );
			$name   = $array['name'];
			$exists = email_exists( $req['email'] );
			if ( $exists == false ) {
				$URL = site_url() . '/wp-login.php?action=register&email=' . $req['email'] . '&board=' . $req['termId'];
			} else {
				$URL = site_url() . '/wp-login.php?action=login&email=' . $req['email'] . '&board=' . $req['termId'];
			}
			$sql       = "SELECT {$users}.*
                  FROM {$users} JOIN {$termmeta} ON {$termmeta}.meta_value = {$users}.ID
                  WHERE {$termmeta}.term_id = {$term_id} AND {$termmeta}.meta_key = 'user_created'";
			$data      = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
			$user_name = ( $data[0]['display_name'] );

			$subject = '[' . $name . '] ' . $user_name . ' added you to the team';
			$to      = $req['email'];
			$body    = '<html>
                      <body bgcolor="#f4f8fb" topmargin="25">
                      <div style="background-color:#f4f8fb">
                      <div style="Margin:0px auto;max-width:475px">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%">
                          <tbody>
                            <tr>
                              <td style="direction:ltr;font-size:0px;padding:64px 16px;text-align:center;vertical-align:top">
                                <div style="Margin:0px auto;max-width:443px">
                                  <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%">
                                    <tbody>
                                      <tr>
                                        <td style="direction:ltr;font-size:0px;padding:0;text-align:center;vertical-align:top">

                                          <div class="m_1807332950475877641mj-column-per-100" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%">
                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                              <tbody>
                                                <tr>
                                                  <td style="vertical-align:top;padding:0">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                                      <tbody><tr>
                                                        <td align="left" style="font-size:0px;padding:0 0 8px;word-break:break-word">
                                                          <div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen,Ubuntu,Cantarell,Fira Sans,Droid Sans,Helvetica Neue,sans-serif;font-size:17px;font-weight:500;line-height:24px;text-align:left;color:#000000">' . $user_name . ' added you to the team </div>
                                                        </td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left" style="font-size:0px;padding:0 0 16px;word-break:break-word">
                                                          <div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen,Ubuntu,Cantarell,Fira Sans,Droid Sans,Helvetica Neue,sans-serif;font-size:15px;font-weight:400;line-height:24px;text-align:left;color:#494949"> Welcome to the crew! You are now part of the "' . $name . '" team on Feedbo where you can share feedback and vote on existing ideas. </div>
                                                        </td>
                                                      </tr>
                                                      <tr>
                                                        <td align="center" style="font-size:0px;padding:4px 0 16px;word-break:break-word">
                                                          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;width:100%;line-height:100%">
                                                            <tbody><tr>
                                                            <td align="center" bgcolor="#f9f586" role="presentation" style="border:1px solid #f6f04f;border-radius:4px;padding:10px 25px;background:#f9f586" valign="middle">
                                                            <a href="' . $URL . '" style="background:#f9f586;color:#000000;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen,Ubuntu,Cantarell,Fira Sans,Droid Sans,Helvetica Neue,sans-serif;font-size:13px;font-weight:normal;line-height:120%;Margin:0;text-decoration:none;text-transform:none" target="_blank"> Click here to open the board </a>
                                                          </td>
                                                            </tr>
                                                          </tbody></table>
                                                        </td>
                                                      </tr>
                                                    </tbody></table>
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </body>
                  </html>';
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			wp_mail( $to, $subject, $body, $headers );
		}
		$userTeam = $page->getUserInBoard( $term_id );
		$result   = array(
			'userInBoard' => $userTeam,
			'message'     => (int) $count == 0 ? 'Invite user success' : 'This user already of this board',
		);
		return new \WP_REST_Response( $result, 200 );
	}
	public function createUser( $request ) {
		global $wpdb;
		$req     = wp_unslash( $request->get_params() );
		$user_id = username_exists( $req['user_login'] );
		if ( ! $user_id && false == email_exists( $req['user_email'] ) ) {
			$userData = array(
				'role'       => 'subscriber',
				'user_login' => $req['user_login'],
				'user_pass'  => $req['user_pass'],
				'user_email' => $req['user_email'],
			);
			$user_id  = wp_insert_user( $userData );
			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}
		} else {
			return 'User already exists.';
		}

	}
	public function addNewUserVote( $request ) {
		global $wpdb;
		$req            = wp_unslash( $request->get_params() );
		$users          = $wpdb->prefix . 'users';
		$board_activity = $wpdb->prefix . 'board_activity';
		$data           = array(
			'user_login'    => $req['userCreate'],
			'user_nicename' => $req['userCreate'],
			'user_email'    => $req['userCreate'],
			'display_name'  => $req['userCreate'],
		);
		$wpdb->insert( $users, $data );
		$userId = $wpdb->insert_id;
		if ( $req['post']['vote_ids'] == '' || $req['post']['vote_ids'] == null ) {
			$voteIds = (string) $userId;
		} else {
			$voteIds = $req['post']['vote_ids'] . ' , ' . $userId;
		}
		update_post_meta( $req['post']['post_id'], 'user_voted_ids', $voteIds );
		$data1 = array(
			'term_id'       => $req['post']['term_id'],
			'post_id'       => $req['post']['post_id'],
			'activity_name' => 'upvoted',
			'activity_date' => date( 'Y-m-d h:i:sa' ),
			'user_id'       => $userId,
		);
		$wpdb->insert( $board_activity, $data1 );
		$comment = Comment::getInstance();
		$voters  = $comment->getListVote( $req['post']['post_id'] );
		$result  = array(
			'voters'  => $voters,
			'message' => 'Add new user vote success',
		);
		return new \WP_REST_Response( $result, 200 );
	}
}
