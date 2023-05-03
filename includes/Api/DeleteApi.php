<?php
namespace Feedbo\Api;

use Feedbo\Classes\Comment;
use Feedbo\Classes\PageLoad;
defined( 'ABSPATH' ) || exit;
class DeleteApi extends \WP_REST_Controller {

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
			'wp_delete_post',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'deletePost' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_comment',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateComment' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_delete_comment',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'deleteComment' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_users_like',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateUsersLike' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_post_status',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updatePostStatus' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_upload_file',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'uploadfile' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_lock_comment_post',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'lockComment' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_unlock_comment_post',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'unLockComment' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_delete_term',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'deleteTerm' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_termmeta',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateTermmeta' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_change_role',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateRole' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_term_users_status',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateStatus' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_user_notification',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateUserNotification' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_user_account',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateUserAccount' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_user_subscribe',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateUserSubscribe' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_unsubscribed_all_post',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'unsubscribedAllPost' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_status_color',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateStatusColor' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_appearance',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateAppearance' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_update_option_redirect',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'updateSocialLoginRedirect' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}
	public function updateComment( $request ) {
		global $wpdb;
		$req    = wp_unslash( $request->get_params() );
		$table1 = $wpdb->prefix . 'comments';
		$table2 = $wpdb->prefix . 'commentmeta';
		$table3 = $wpdb->prefix . 'board_activity';

		// Check @mention
		$page       = PageLoad::getInstance();
		$project_id = $page->getTermByKey( $req['termId'] );
		$term_users = $wpdb->prefix . 'term_users';
		$users      = $wpdb->prefix . 'users';
		$sql        = "SELECT {$term_users}.*, {$users}.*
                FROM {$term_users}
                LEFT JOIN {$users} ON {$users}.user_email = {$term_users}.user_email
                WHERE {$term_users}.term_id = $project_id
                ORDER BY {$term_users}.ID";
		$data       = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$userTeam   = array();
		$mention    = array();
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
			$pos              = strpos( $req['values'], $val['user_name'] );
			$val['user_name'] = substr( $val['user_name'], 1 );
			if ( $pos !== false && ! in_array( $val, $mention ) ) {
				array_push( $mention, $val );
			}
		}
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
                                    <div><a href="' . site_url() . MV_URL_BOARD . $req['termId'] . '" target="_blank">' . site_url() . '/board/' . $req['termId'] . '</a></div>
                                    </body>
                                </html>';
						$headers = array( 'Content-Type: text/html; charset=UTF-8' );
						wp_mail( $to, $subject, $body, $headers );
					}
				}
			}
		}

		$data1  = array( 'comment_content' => $req['values'] );
		$where1 = array( 'comment_ID' => $req['commentId'] );
		$wpdb->update( $table1, $data1, $where1 );
		$data2  = array( 'meta_value' => maybe_serialize( $req['arrImage'] ) );
		$where2 = array(
			'comment_id' => (int) $req['commentId'],
			'meta_key'   => 'attachment_id',
		);
		$wpdb->update( $table2, $data2, $where2 );
		$comment = get_comment( $req['commentId'], ARRAY_A );
		$postId  = $req['postId'];
		$term    = get_the_terms( $postId, 'category' );
		$data3   = array(
			'term_id'       => $project_id,
			'post_id'       => $postId,
			'activity_name' => 'Edit comment',
			'activity_date' => date( 'Y-m-d h:i:sa' ),
			'user_id'       => self::$current_user->ID,
		);
		$wpdb->insert( $table3, $data3 );
		$isPost = metadata_exists( 'comment', $req['commentId'], 'is_post' );
		if ( $isPost == true ) {
			$postUpdate = array(
				'ID'           => $postId,
				'post_content' => $req['values'],
			);
			wp_update_post( $postUpdate );
		}
		$comment  = Comment::getInstance();
		$comments = $comment->getComments( $postId );
		$result   = array(
			'comments' => $comments,
			'isPost'   => $isPost,
			'message'  => 'Update comment success',
		);
		return new \WP_REST_Response( $result, 200 );
	}
	public function deleteComment ( $request ) {
		$req    = wp_unslash( $request->get_params() );
		if ( isset( $req['comment'] ) && ! empty( $req['comment']['comment_ID'] ) ) {
			wp_delete_comment( $req['comment']['comment_ID'], true );
		}
		$comment  = Comment::getInstance();
		$comments = $comment->getComments( $req['comment']['comment_post_ID'] );
		$result   = array(
			'comments' => $comments,
			'message'  => 'Delete comment success',
		);
		return new \WP_REST_Response( $result, 200 );
	}
	public function updateUsersLike( $request ) {
		global $wpdb;
		$req     = wp_unslash( $request->get_params() );
		$table   = $wpdb->prefix . 'board_activity';
		$page    = PageLoad::getInstance();
		$term_id = $page->getTermByKey( $req['boardId'] );
		update_comment_meta( $req['comment']['comment_ID'], 'users_like', $req['comment']['users_like'] );
		$data = array(
			'term_id'       => $term_id,
			'post_id'       => $req['comment']['comment_post_ID'],
			'activity_name' => $req['checkLike'] == false ? 'Like comment' : 'Cancel like comment',
			'activity_date' => date( 'Y-m-d h:i:sa' ),
			'user_id'       => $req['userLike'],
		);
		$wpdb->insert( $table, $data );
		return new \WP_REST_Response( $req['checkLike'] == false ? 'Like comment success' : 'Like has been canceled', 200 );
	}
	public function updatePostStatus( $request ) {
		global $wpdb;
		$req    = wp_unslash( $request->get_params() );
		$table  = $wpdb->prefix . 'posts';
		$table1 = $wpdb->prefix . 'board_activity';
		$data   = array( 'post_content_filtered' => $req['value']['status'] );
		$where  = array( 'ID' => $req['value']['postId'] );
		$wpdb->update( $table, $data, $where );
		$term  = get_the_terms( $req['value']['postId'], 'category' );
		$data1 = array(
			'term_id'       => $term[0]->term_id,
			'post_id'       => $req['value']['postId'],
			'activity_name' => 'change status',
			'activity_date' => date( 'Y-m-d h:i:sa' ),
			'user_id'       => self::$current_user->ID,
		);
		$wpdb->insert( $table1, $data1 );
		$pageLoad = PageLoad::getInstance();
		$posts    = $pageLoad->getPosts( $term[0]->term_id );
		$results  = array(
			'posts'   => $posts,
			'message' => 'Change status success',
		);
		return new \WP_REST_Response( $results, 200 );
	}
	public function uploadfile() {
		$fileTmpPath   = $_FILES['file']['tmp_name'];
		$fileName      = $_FILES['file']['name'];
		$fileType      = $_FILES['file']['type'];
		$uploadDir     = wp_upload_dir();
		$destPath      = $uploadDir['path'] . '/' . $_FILES['file']['name'];
		$uploadFileURL = $uploadDir['url'] . '/' . $_FILES['file']['name'];
		$validFileType = array( 'image/jpg', 'image/jpe', 'image/jpeg', 'image/jfif', 'image/png', 'image/bmp', 'image/dib', 'image/gif' );
		if ( ! in_array( $fileType, $validFileType ) ) {
			$responseObj = array(
				'status'  => false,
				'message' => 'You can only upload image file!',
			);
			return new \WP_REST_Response( $responseObj, 500 );
		}
		move_uploaded_file( $fileTmpPath, $destPath );

		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $destPath ), null );

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $uploadDir['url'] . '/' . basename( $destPath ),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $destPath ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);
		// Insert the attachment.
		$attachId = wp_insert_attachment( $attachment, $destPath );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Generate the metadata for the attachment, and update the database record.
		$attachData = wp_generate_attachment_metadata( $attachId, $destPath );
		wp_update_attachment_metadata( $attachId, $attachData );
		$data = (object) array(
			'id'       => $attachId,
			'name'     => $fileName,
			'status'   => 'done',
			'url'      => $uploadFileURL,
			'thumbUrl' => $uploadDir['url'] . '/' . $attachData['sizes']['thumbnail']['file'],
		);
		return new \WP_REST_Response( $data, 200 );
	}
	public function deletePost( $request ) {
		$req = wp_unslash( $request->get_params() );
		wp_delete_post( $req['post']['post_id'], true );
	}
	public function lockComment( $request ) {
		global $wpdb;
		$req    = wp_unslash( $request->get_params() );
		$table  = $wpdb->prefix . 'posts';
		$table1 = $wpdb->prefix . 'board_activity';
		$data   = array( 'comment_status' => 'closed' );
		$where  = array( 'ID' => $req['post']['post_id'] );
		$wpdb->update( $table, $data, $where );
		$data1 = array(
			'term_id'       => $req['post']['term_id'],
			'post_id'       => $req['post']['post_id'],
			'activity_name' => 'lock comment',
			'activity_date' => date( 'Y-m-d h:i:sa' ),
			'user_id'       => self::$current_user->ID,
		);
		$wpdb->insert( $table1, $data1 );
	}
	public function unLockComment( $request ) {
		global $wpdb;
		$req    = wp_unslash( $request->get_params() );
		$table  = $wpdb->prefix . 'posts';
		$table1 = $wpdb->prefix . 'board_activity';
		$data   = array( 'comment_status' => 'open' );
		$where  = array( 'ID' => $req['post']['post_id'] );
		$wpdb->update( $table, $data, $where );
		$data1 = array(
			'term_id'       => $req['post']['term_id'],
			'post_id'       => $req['post']['post_id'],
			'activity_name' => 'unlock comment',
			'activity_date' => date( 'Y-m-d h:i:sa' ),
			'user_id'       => self::$current_user->ID,
		);
		$wpdb->insert( $table1, $data1 );
	}
	public function deleteTerm( $request ) {
		global $wpdb;
		$req     = wp_unslash( $request->get_params() );
		$page    = PageLoad::getInstance();
		$term_id = $page->getTermByKey( $req['id'] );
		wp_delete_term( $term_id, 'category' );
		$table  = $wpdb->prefix . 'term_users';
		$table1 = $wpdb->prefix . 'board_activity';
		$where  = array( 'term_id' => $term_id );
		$wpdb->delete( $table, $where );
		$wpdb->delete( $table1, $where );
	}
	public function updateTermmeta( $request ) {
		global $wpdb;
		$req     = wp_unslash( $request->get_params() );
		$page    = PageLoad::getInstance();
		$term_id = $page->getTermByKey( $req['id'] );
		$table   = $wpdb->prefix . 'terms';
		$table1  = $wpdb->prefix . 'termmeta';
		$data    = array(
			'name' => $req['values']['name'],
			'slug' => sanitize_title( $req['values']['name'] ),
		);
		$where   = array( 'term_id' => $term_id );
		$wpdb->update( $table, $data, $where );

		if ( $req['values']['features'] == null || $req['values']['features'] == '' ) {
			$features = null;
		} else {
			$features = implode( ' , ', $req['values']['features'] );
		}

		$arrSetting = array(
			'name'         => $req['values']['name'],
			'description'  => $req['values']['description'],
			'button_text'  => $req['values']['button_text'],
			'default_sort' => $req['values']['default_sort'],
			'board_URL'    => $req['values']['board_URL'],
			'visibility'   => $req['values']['visibility'],
			'features'     => $features,
		);
		$data1      = array( 'meta_value' => maybe_serialize( $arrSetting ) );
		$where1     = array(
			'term_id'  => $term_id,
			'meta_key' => 'board_Setting',
		);
		$wpdb->update( $table1, $data1, $where1 );
		$result = array(
			'board'   => $arrSetting,
			'message' => 'Changes saved',
		);
		return new \WP_REST_Response( $result, 200 );
	}
	public function updateRole( $request ) {
		global $wpdb;
		$req     = wp_unslash( $request->get_params() );
		$page    = PageLoad::getInstance();
		$term_id = $page->getTermByKey( $req['term_id'] );
		$table   = $wpdb->prefix . 'term_users';
		$level   = 0;
		if ( $req['user_role'] !== 'Delete' ) {
			if ( $req['user_role'] == 'Owner' ) {
				$level = 1;
			}
			if ( $req['user_role'] == 'Admin' ) {
				$level = 2;
			}
			if ( $req['user_role'] == 'Moderator' ) {
				$level = 3;
			}
			if ( $req['user_role'] == 'Member' ) {
				$level = 4;
			}
			$data  = array(
				'term_role' => $req['user_role'],
				'level'     => $level,
			);
			$where = array(
				'term_id'    => $term_id,
				'user_email' => $req['user_email'],
			);
			$wpdb->update( $table, $data, $where );
		} else {
			$table = $wpdb->prefix . 'term_users';
			$where = array(
				'term_id'    => $term_id,
				'user_email' => $req['user_email'],
			);
			$wpdb->delete( $table, $where );
		}
		$userTeam = $page->getUserInBoard( $term_id );
		$result   = array(
			'userInBoard' => $userTeam,
			'message'     => 'Change role success',
		);
		return new \WP_REST_Response( $result, 200 );
	}
	public function updateStatus( $request ) {
		global $wpdb;
		$req     = wp_unslash( $request->get_params() );
		$page    = PageLoad::getInstance();
		$term_id = $page->getTermByKey( $req['id'] );
		$table   = $wpdb->prefix . 'term_users';
		$data    = array( 'status' => 'accept' );
		$where   = array(
			'term_id'    => $term_id,
			'user_email' => $req['email'],
		);
		$wpdb->update( $table, $data, $where );
		$table1 = $wpdb->prefix . 'options';
		$data1  = array( 'option_value' => 'relative' );
		$where1 = array( 'option_name' => 'mo_openid_login_redirect' );
		$wpdb->update( $table1, $data1, $where1 );
		$data2  = array( 'option_value' => MV_URL_BOARD . $req['id'] );
		$where2 = array( 'option_name' => 'mo_openid_relative_login_redirect_url' );
		$wpdb->update( $table1, $data2, $where2 );
	}
	public function updateUserNotification( $request ) {
		global $wpdb;
		$req   = wp_unslash( $request->get_params() );
		$table = $wpdb->prefix . 'usermeta';
		$data  = array( 'meta_value' => maybe_serialize( $req ) );
		$where = array(
			'user_id'  => self::$current_user->ID,
			'meta_key' => 'notification_setting',
		);
		$wpdb->update( $table, $data, $where );
	}
	public function updateUserAccount( $request ) {
		global $wpdb;
		$req    = wp_unslash( $request->get_params() );
		$table  = $wpdb->prefix . 'comments';
		$table1 = $wpdb->prefix . 'term_users';
		$data   = array( 'comment_author' => $req['name'] );
		$where  = array( 'comment_author' => self::$current_user->display_name );
		$wpdb->update( $table, $data, $where );

		$data1  = array( 'user_email' => $req['email'] );
		$where1 = array( 'user_email' => self::$current_user->user_email );
		$wpdb->update( $table1, $data1, $where1 );
		$user_data = wp_update_user(
			array(
				'ID'           => self::$current_user->ID,
				'user_email'   => $req['email'],
				'display_name' => $req['name'],
			)
		);
	}
	public function updateUserSubscribe( $request ) {
		global $wpdb;
		$req          = wp_unslash( $request->get_params() );
		$table        = $wpdb->prefix . 'postmeta';
		$sql          = "SELECT {$table}.meta_value
                FROM {$table}
                WHERE {$table}.post_id = {$req['post']['post_id']} AND {$table}.meta_key = 'user_subscribed'";
		$data         = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$arrSubscribe = unserialize( $data[0]['meta_value'] );
		if ( ! in_array( self::$current_user->ID, $arrSubscribe ) ) {
			array_push( $arrSubscribe, self::$current_user->ID );
		} else {
			$key = array_search( self::$current_user->ID, $arrSubscribe );
			array_splice( $arrSubscribe, $key, 1 );
		}
		$data  = array( 'meta_value' => maybe_serialize( $arrSubscribe ) );
		$where = array(
			'post_id'  => $req['post']['post_id'],
			'meta_key' => 'user_subscribed',
		);
		$wpdb->update( $table, $data, $where );
		$data = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		return new \WP_REST_Response( unserialize( $data[0]['meta_value'] ), 200 );
	}
	public function unsubscribedAllPost() {
		global $wpdb;
		$postmeta = $wpdb->prefix . 'postmeta';
		$sql      = "SELECT {$postmeta}.*
                FROM {$postmeta}
                WHERE {$postmeta}.meta_key = 'user_subscribed'";
		$data     = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$arrPost  = array();
		foreach ( $data as $key => $val ) {
			$userSubscribed = unserialize( $val['meta_value'] );
			if ( in_array( self::$current_user->ID, $userSubscribed ) ) {
				$key = array_search( self::$current_user->ID, $userSubscribed );
				array_splice( $userSubscribed, $key, 1 );
				$data  = array( 'meta_value' => maybe_serialize( $userSubscribed ) );
				$where = array(
					'post_id'  => $val['post_id'],
					'meta_key' => 'user_subscribed',
				);
				$wpdb->update( $postmeta, $data, $where );
			}
		}
	}
	public function updateStatusColor( $request ) {
		global $wpdb;
		$req        = wp_unslash( $request->get_params() );
		$page       = PageLoad::getInstance();
		$termId     = $page->getTermByKey( $req['values']['boardId'] );
		$table      = $wpdb->prefix . 'termmeta';
		$colorArray = $req['status'];
		foreach ( $req['status'] as $key => $value ) {
			if ( $key == $req['values']['name'] ) {
				$colorArray[ $key ] = $req['values']['color'];
			}
		}
		$data  = array( 'meta_value' => maybe_serialize( $colorArray ) );
		$where = array(
			'term_id'  => $termId,
			'meta_key' => 'status_color',
		);
		$wpdb->update( $table, $data, $where );
		return new \WP_REST_Response( $colorArray, 200 );
	}
	public function updateSocialLoginRedirect( $request ) {
		global $wpdb;
		$req   = wp_unslash( $request->get_params() );
		$table = $wpdb->prefix . 'options';
		$data  = array( 'option_value' => '/' );
		$where = array( 'option_name' => 'mo_openid_relative_login_redirect_url' );
		$wpdb->update( $table, $data, $where );
	}
	public function updateAppearance( $request ) {
		global $wpdb;
		$req           = wp_unslash( $request->get_params() );
		$page          = PageLoad::getInstance();
		$termId        = $page->getTermByKey( $req['id'] );
		$checkDelete   = $req['imageRequest']['delele_image'];
		$logoUpdate    = $req['imageRequest']['logo_img'];
		$faviconUpdate = $req['imageRequest']['favicon_img'];
		$meta          = get_term_meta( $termId, 'logo_favicon' );
		$logoFavicon   = $meta[0];
		if ( $checkDelete == false ) {
			if ( $logoUpdate != '' ) {
				$logoFavicon['logo'] = $logoUpdate;
			}
			if ( $faviconUpdate != '' ) {
				$logoFavicon['favicon'] = $faviconUpdate;
			}
		} else {
			$logoFavicon['logo']    = $logoUpdate;
			$logoFavicon['favicon'] = $faviconUpdate;
		}
		update_term_meta( $termId, 'theme_color', $req['themeRequest'] );
		update_term_meta( $termId, 'logo_favicon', $logoFavicon );
		$results = array(
			'theme'        => maybe_unserialize( get_term_meta( $termId, 'theme_color' ) )[0],
			'logo_favicon' => maybe_unserialize( get_term_meta( $termId, 'logo_favicon' ) )[0],
		);
		return new \WP_REST_Response( $results, 200 );
	}
}
