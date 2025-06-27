<?php
namespace Feedbo\Api;

use Feedbo\Classes\PageLoad;
use Feedbo\Classes\WidgetLoad;
use Feedbo\Classes\Comment;
defined( 'ABSPATH' ) || exit;
class GetApi extends \WP_REST_Controller {

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
			'wp_get_new_post_by_category',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getNewPostByCategory' ),
					'permission_callback' => '__return_true',

				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_most_vote_post_by_category',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getMostVotePostByCategory' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_term_by_id',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getTermById' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_term',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getTerm' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_comments_by_post_id',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getCommentsByPost' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_users_like_by_post_id',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getUsersLikeByPost' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_search_posts',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getSearchPosts' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_current_user',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getCurrentUser' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_post_by_id',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getPostById' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_status_by_category',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getStatusByCategory' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_activity_by_category',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getActivityByCategory' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_post_activity',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getPostByActivity' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_term_users',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getTermUsers' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_term_users_avatar',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getTermUsersAvatar' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_current_user_avatar',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getCurrentUserAvatar' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_comment_images_by_post_id',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getCommentImages' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_user_notification',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getUserNotification' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_user_subscribe',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getUserSubscribe' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_comments',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getComments' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_list_vote',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getListVote' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_export_vote_list',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getListVoteExport' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_export_subscribed_list',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getListSubscribedExport' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_all_users_avatar',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getAllUsersAvatar' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_board_content',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getBoardContent' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_widget_get_board_content',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getWidgetBoardContent' ),
					'permission_callback' => '__return_true',
				),
			)
		);
		register_rest_route(
			'v1',
			'wp_get_posts',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'getPosts' ),
					'permission_callback' => '__return_true',
				),
			)
		);
	}
	public function getNewPostByCategory() {
		global $wpdb;
		$page               = PageLoad::getInstance();
		$boardId            = $page->getTermByKey( $_REQUEST['id'] );
		$posts              = $wpdb->prefix . 'posts';
		$terms              = $wpdb->prefix . 'terms';
		$users              = $wpdb->prefix . 'users';
		$term_relationships = $wpdb->prefix . 'term_relationships';
		$term_taxonomy      = $wpdb->prefix . 'term_taxonomy';
		$sql                = "SELECT {$posts}.ID as post_id,{$posts}.*, {$terms}.*,{$users}.*
                FROM {$posts}
                INNER JOIN {$term_relationships} ON {$posts}.ID = {$term_relationships}.object_id
                INNER JOIN {$term_taxonomy} ON {$term_taxonomy}.term_taxonomy_id = {$term_relationships}.term_taxonomy_id
                INNER JOIN {$terms} ON {$term_taxonomy}.term_id = {$terms}.term_id
                LEFT JOIN {$users} ON {$posts}.post_author = {$users}.ID
                WHERE {$posts}.post_type = 'vote' AND {$posts}.post_status='publish' AND {$terms}.term_id = {$boardId}";
		$data               = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$results            = array();
		if ( count( $data ) > 0 ) {
			foreach ( $data as $key => $val ) {
				$results[ $key ]['term_id']          = $val['term_id'];
				$results[ $key ]['post_id']          = $val['post_id'];
				$results[ $key ]['post_author']      = $val['post_author'];
				$results[ $key ]['post_title']       = $val['post_title'];
				$results[ $key ]['post_content']     = $val['post_content'];
				$results[ $key ]['post_status']      = $val['post_content_filtered'] != '' ? $val['post_content_filtered'] : 'Unassigned';
				$results[ $key ]['post_slug']        = sanitize_title( $val['post_title'] );
				$results[ $key ]['post_date']        = $val['post_date'];
				$results[ $key ]['display_name']     = $val['display_name'];
				$results[ $key ]['comment_status']   = $val['comment_status'];
				$userVote                            = get_post_meta( $val['post_id'], 'user_voted_ids' );
				$userDownVote                        = get_post_meta( $val['post_id'], 'user_down_voted_ids' );
				$userSubscribe                       = get_post_meta( $val['post_id'], 'user_subscribed' );
				$results[ $key ]['vote_ids']         = $userVote[0];
				$results[ $key ]['down_vote_ids']    = $userDownVote[0];
				$results[ $key ]['subscribe_ids']    = $userSubscribe[0] != null ? $userSubscribe[0] : array();
				$results[ $key ]['vote_length']      = ( $userVote[0] != '' || $userVote[0] != null ) ? count( explode( ' , ', $userVote[0] ) ) : 0;
				$results[ $key ]['down_vote_length'] = ( $userDownVote[0] != '' || $userDownVote[0] != null ) ? count( explode( ' , ', $userDownVote[0] ) ) : 0;
			}
			$sortByNew = array_column( $results, 'post_date' );
			array_multisort( $sortByNew, SORT_DESC, $results );
		}
		return new \WP_REST_Response( $results, 200 );
	}
	public function getMostVotePostByCategory() {
		global $wpdb;
		$page               = PageLoad::getInstance();
		$boardId            = $page->getTermByKey( $_REQUEST['id'] );
		$posts              = $wpdb->prefix . 'posts';
		$terms              = $wpdb->prefix . 'terms';
		$users              = $wpdb->prefix . 'users';
		$term_relationships = $wpdb->prefix . 'term_relationships';
		$term_taxonomy      = $wpdb->prefix . 'term_taxonomy';
		$sql                = "SELECT {$posts}.ID as post_id,{$posts}.*, {$terms}.*,{$users}.*
                FROM {$posts}
                INNER JOIN {$term_relationships} ON {$posts}.ID = {$term_relationships}.object_id
                INNER JOIN {$term_taxonomy} ON {$term_taxonomy}.term_taxonomy_id = {$term_relationships}.term_taxonomy_id
                INNER JOIN {$terms} ON {$term_taxonomy}.term_id = {$terms}.term_id
                LEFT JOIN {$users} ON {$posts}.post_author = {$users}.ID
                WHERE {$posts}.post_type = 'vote' AND {$posts}.post_status='publish' AND {$terms}.term_id = {$boardId}";
		$data               = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$results            = array();
		if ( count( $data ) > 0 ) {
			foreach ( $data as $key => $val ) {
				$results[ $key ]['term_id']          = $val['term_id'];
				$results[ $key ]['post_id']          = $val['post_id'];
				$results[ $key ]['post_author']      = $val['post_author'];
				$results[ $key ]['post_title']       = $val['post_title'];
				$results[ $key ]['post_content']     = $val['post_content'];
				$results[ $key ]['post_status']      = $val['post_content_filtered'] != '' ? $val['post_content_filtered'] : 'Unassigned';
				$results[ $key ]['post_date']        = $val['post_date'];
				$results[ $key ]['post_slug']        = sanitize_title( $val['post_title'] );
				$results[ $key ]['display_name']     = $val['display_name'];
				$results[ $key ]['comment_status']   = $val['comment_status'];
				$userVote                            = get_post_meta( $val['post_id'], 'user_voted_ids' );
				$userDownVote                        = get_post_meta( $val['post_id'], 'user_down_voted_ids' );
				$userSubscribe                       = get_post_meta( $val['post_id'], 'user_subscribed' );
				$results[ $key ]['vote_ids']         = $userVote[0];
				$results[ $key ]['down_vote_ids']    = $userDownVote[0];
				$results[ $key ]['subscribe_ids']    = $userSubscribe[0] != null ? $userSubscribe[0] : array();
				$results[ $key ]['vote_length']      = ( $userVote[0] != '' || $userVote[0] != null ) ? count( explode( ' , ', $userVote[0] ) ) : 0;
				$results[ $key ]['down_vote_length'] = ( $userDownVote[0] != '' || $userDownVote[0] != null ) ? count( explode( ' , ', $userDownVote[0] ) ) : 0;
			}
			$sortByVote = array_column( $results, 'vote_length' );
			array_multisort( $sortByVote, SORT_DESC, $results );
		}
		return new \WP_REST_Response( $results, 200 );
	}
	public function getPostByActivity() {
		global $wpdb;
		$post_id            = (int) $_REQUEST['id'];
		$posts              = $wpdb->prefix . 'posts';
		$postmeta           = $wpdb->prefix . 'postmeta';
		$terms              = $wpdb->prefix . 'terms';
		$users              = $wpdb->prefix . 'users';
		$term_relationships = $wpdb->prefix . 'term_relationships';
		$term_taxonomy      = $wpdb->prefix . 'term_taxonomy';
		$sql                = "SELECT {$posts}.*, {$postmeta}.*, {$terms}.*,{$users}.*
                FROM {$posts}
                INNER JOIN {$postmeta} ON {$posts}.ID = {$postmeta}.post_id
                INNER JOIN {$term_relationships} ON {$posts}.ID = {$term_relationships}.object_id
                INNER JOIN {$term_taxonomy} ON {$term_taxonomy}.term_taxonomy_id = {$term_relationships}.term_taxonomy_id
                INNER JOIN {$terms} ON {$term_taxonomy}.term_id = {$terms}.term_id
                LEFT JOIN {$users} ON {$posts}.post_author = {$users}.ID
                WHERE {$posts}.ID = $post_id AND {$postmeta}.meta_key='user_voted_ids'";
		$data               = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		return new \WP_REST_Response( $data, 200 );
	}
	public function getPostById() {
		$post_id = (int) $_REQUEST['id'];
		$data    = get_post( $post_id );
		return new \WP_REST_Response( $data, 200 );
	}
	public function getTermById() {
		global $wpdb;
		$project_id = (int) $_REQUEST['id'];
		$terms      = $wpdb->prefix . 'terms';
		$termmeta   = $wpdb->prefix . 'termmeta';
		// $data = get_term_by('term_id',$project_id , 'category');
		$sql  = "SELECT {$terms}.*, {$termmeta}.*
                FROM {$terms}
                LEFT JOIN {$termmeta} ON {$terms}.term_id = {$termmeta}.term_id
                WHERE {$terms}.term_id = $project_id";
		$data = $wpdb->get_results( $wpdb->prepare( $sql ) );
		return new \WP_REST_Response( $data, 200 );
	}
	public function getTerm() {
		global $wpdb;
		$user       = $_REQUEST['user'];
		$terms      = $wpdb->prefix . 'terms';
		$term_users = $wpdb->prefix . 'term_users';
		$sql        = "SELECT {$terms}.*
                FROM {$terms}
                RIGHT JOIN {$term_users} ON {$terms}.term_id = {$term_users}.term_id
                WHERE {$term_users}.user_email = '{$user}' ";
		$data       = $wpdb->get_results( $wpdb->prepare( $sql ) );
		return new \WP_REST_Response( $data, 200 );
	}
	public function getCommentsByPost() {
		global $wpdb;
		$post_id     = (int) $_REQUEST['id'];
		$comments    = $wpdb->prefix . 'comments';
		$commentmeta = $wpdb->prefix . 'commentmeta';
		$posts       = $wpdb->prefix . 'posts';
		$sql         = "SELECT {$comments}.*, {$commentmeta}.*,{$posts}.*
                FROM {$comments}
                LEFT JOIN {$commentmeta} ON {$comments}.comment_ID = {$commentmeta}.comment_id AND {$commentmeta}.meta_key = 'attachment_id'
                LEFT JOIN {$posts} ON {$commentmeta}.meta_value = {$posts}.ID
                WHERE {$comments}.comment_post_ID = $post_id
                ORDER BY {$comments}.comment_date
                LIMIT 10";
		$data        = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );

		foreach ( $data as $key => $val ) {
			$commentDate                         = human_time_diff( strtotime( $val['comment_date'] ), time() ) . ' ago';
			$data[ $key ]['comment_date_format'] = $commentDate;
		}
		return new \WP_REST_Response( $data, 200 );
	}
	public function getSearchPosts() {
		global $wpdb;
		$page               = PageLoad::getInstance();
		$termId             = $page->getTermByKey( $_REQUEST['id'] );
		$search             = $_REQUEST['search'];
		$searchText         = '%' . $search . '%';
		$posts              = $wpdb->prefix . 'posts';
		$postmeta           = $wpdb->prefix . 'postmeta';
		$terms              = $wpdb->prefix . 'terms';
		$users              = $wpdb->prefix . 'users';
		$term_relationships = $wpdb->prefix . 'term_relationships';
		$term_taxonomy      = $wpdb->prefix . 'term_taxonomy';
		$data               = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT {$posts}.*, {$postmeta}.*, {$terms}.*,{$users}.*
        FROM {$posts}
        INNER JOIN {$postmeta} ON {$posts}.ID = {$postmeta}.post_id
        INNER JOIN {$term_relationships} ON {$posts}.ID = {$term_relationships}.object_id
        INNER JOIN {$term_taxonomy} ON {$term_taxonomy}.term_taxonomy_id = {$term_relationships}.term_taxonomy_id
        INNER JOIN {$terms} ON {$term_taxonomy}.term_id = {$terms}.term_id
        LEFT JOIN {$users} ON {$posts}.post_author = {$users}.ID
        WHERE {$posts}.post_type = 'vote' AND {$postmeta}.meta_key='user_voted_ids' AND {$posts}.post_status='publish' AND {$terms}.term_id = $termId AND {$posts}.post_title LIKE %s
        ORDER BY length({$postmeta}.meta_value) DESC",
				$searchText
			),
			ARRAY_A
		);
		$results            = array();
		if ( count( $data ) > 0 ) {
			foreach ( $data as $key => $val ) {
				$results[ $key ]['term_id']          = $val['term_id'];
				$results[ $key ]['post_id']          = $val['post_id'];
				$results[ $key ]['post_author']      = $val['post_author'];
				$results[ $key ]['post_title']       = $val['post_title'];
				$results[ $key ]['post_content']     = $val['post_content'];
				$results[ $key ]['post_status']      = $val['post_content_filtered'] != '' ? $val['post_content_filtered'] : 'Unassigned';
				$results[ $key ]['post_date']        = $val['post_date'];
				$results[ $key ]['display_name']     = $val['display_name'];
				$results[ $key ]['comment_status']   = $val['comment_status'];
				$results[ $key ]['post_slug']        = sanitize_title( $val['post_title'] );
				$userVote                            = get_post_meta( $val['post_id'], 'user_voted_ids' );
				$userDownVote                        = get_post_meta( $val['post_id'], 'user_down_voted_ids' );
				$userSubscribe                       = get_post_meta( $val['post_id'], 'user_subscribed' );
				$results[ $key ]['vote_ids']         = $userVote[0];
				$results[ $key ]['down_vote_ids']    = $userDownVote[0];
				$results[ $key ]['subscribe_ids']    = $userSubscribe[0] != null ? $userSubscribe[0] : array();
				$results[ $key ]['vote_length']      = ( $userVote[0] != '' || $userVote[0] != null ) ? count( explode( ' , ', $userVote[0] ) ) : 0;
				$results[ $key ]['down_vote_length'] = ( $userDownVote[0] != '' || $userDownVote[0] != null ) ? count( explode( ' , ', $userDownVote[0] ) ) : 0;
			}
		}
		return new \WP_REST_Response( $results, 200 );
	}
	public function getCurrentUser() {
		$page                   = PageLoad::getInstance();
		$data                   = $page->getCurrentUser();
		$responseObj['user']    = $data;
		$responseObj['message'] = 'Get current user success';
		return new \WP_REST_Response( $responseObj, 200 );
	}
	public function getUserNotification() {
		$id = self::$current_user->ID;
		global $wpdb;
		$usermeta = $wpdb->prefix . 'usermeta';
		$sql      = "SELECT meta_value
                FROM {$usermeta}
                WHERE user_id = {$id} AND meta_key = 'notification_setting'";
		$data     = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		return new \WP_REST_Response( unserialize( $data[0]['meta_value'] ), 200 );
	}
	public function getCurrentUserAvatar() {
		$currentUser = self::$current_user;
		$data        = array(
			'user_name'   => self::$current_user->user_email,
			'user_avatar' => get_avatar_url( self::$current_user->user_email, array( 'size' => '64', 'default' => 'gravatar_default' ) ),
		);
		return new \WP_REST_Response( $data, 200 );
	}
	public function getUsersLikeByPost() {
		global $wpdb;
		$post_id     = (int) $_REQUEST['id'];
		$comments    = $wpdb->prefix . 'comments';
		$commentmeta = $wpdb->prefix . 'commentmeta';
		$sql         = "SELECT {$comments}.*, {$commentmeta}.*
                FROM {$comments}
                LEFT JOIN {$commentmeta} ON {$comments}.comment_ID = {$commentmeta}.comment_id AND {$commentmeta}.meta_key = 'users_like'
                WHERE {$comments}.comment_post_ID = $post_id
                ORDER BY {$comments}.comment_date
                LIMIT 10";
		$data        = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		return new \WP_REST_Response( $data, 200 );
	}
	public function getCommentImages() {
		global $wpdb;
		$post_id     = (int) $_REQUEST['id'];
		$comments    = $wpdb->prefix . 'comments';
		$commentmeta = $wpdb->prefix . 'commentmeta';
		$sql         = "SELECT {$commentmeta}.*
                FROM {$commentmeta}
                INNER JOIN {$comments} ON {$comments}.comment_ID = {$commentmeta}.comment_id
                WHERE {$comments}.comment_post_ID = $post_id AND {$commentmeta}.meta_key = 'attachment_id'
                ORDER BY {$comments}.comment_date";
		$data        = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );

		$commentImages = array();
		foreach ( $data as $key => $val ) {
			array_push(
				$commentImages,
				array(
					'comment_id'    => (int) $val['comment_id'],
					'comment_image' => unserialize( $val['meta_value'] ),
				)
			);
		}

		return new \WP_REST_Response( $commentImages, 200 );
	}
	public function getStatusByCategory() {
		global $wpdb;
		$page               = PageLoad::getInstance();
		$boardId            = $page->getTermByKey( $_REQUEST['id'] );
		$posts              = $wpdb->prefix . 'posts';
		$terms              = $wpdb->prefix . 'terms';
		$users              = $wpdb->prefix . 'users';
		$term_relationships = $wpdb->prefix . 'term_relationships';
		$term_taxonomy      = $wpdb->prefix . 'term_taxonomy';
		$sql                = "SELECT {$posts}.ID as post_id,{$posts}.*, {$terms}.*,{$users}.*
                FROM {$posts}
                INNER JOIN {$term_relationships} ON {$posts}.ID = {$term_relationships}.object_id
                INNER JOIN {$term_taxonomy} ON {$term_taxonomy}.term_taxonomy_id = {$term_relationships}.term_taxonomy_id
                INNER JOIN {$terms} ON {$term_taxonomy}.term_id = {$terms}.term_id
                LEFT JOIN {$users} ON {$posts}.post_author = {$users}.ID
                WHERE {$posts}.post_type = 'vote' AND {$posts}.post_status='publish' AND {$terms}.term_id = {$boardId}
                ORDER BY {$posts}.post_content_filtered ASC";
		$data               = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$hasStatus          = array();
		$unassigned         = array();
		if ( count( $data ) > 0 ) {
			foreach ( $data as $key => $val ) {
				if ( $val['post_content_filtered'] != '' ) {
					$hasStatus[ $key ]['term_id']          = $val['term_id'];
					$hasStatus[ $key ]['post_id']          = $val['post_id'];
					$hasStatus[ $key ]['post_author']      = $val['post_author'];
					$hasStatus[ $key ]['post_title']       = $val['post_title'];
					$hasStatus[ $key ]['post_content']     = $val['post_content'];
					$hasStatus[ $key ]['post_status']      = $val['post_content_filtered'] != '' ? $val['post_content_filtered'] : 'Unassigned';
					$hasStatus[ $key ]['post_date']        = $val['post_date'];
					$hasStatus[ $key ]['post_slug']        = sanitize_title( $val['post_title'] );
					$hasStatus[ $key ]['display_name']     = $val['display_name'];
					$hasStatus[ $key ]['comment_status']   = $val['comment_status'];
					$userVote                              = get_post_meta( $val['post_id'], 'user_voted_ids' );
					$userDownVote                          = get_post_meta( $val['post_id'], 'user_down_voted_ids' );
					$hasStatus[ $key ]['vote_ids']         = $userVote[0];
					$hasStatus[ $key ]['down_vote_ids']    = $userDownVote[0];
					$userSubscribe                         = get_post_meta( $val['post_id'], 'user_subscribed' );
					$hasStatus[ $key ]['subscribe_ids']    = $userSubscribe[0] != null ? $userSubscribe[0] : array();
					$hasStatus[ $key ]['vote_length']      = count( explode( ' , ', $userVote[0] ) );
					$hasStatus[ $key ]['down_vote_length'] = count( explode( ' , ', $userDownVote[0] ) );
				} else {
					$unassigned[ $key ]['term_id']          = $val['term_id'];
					$unassigned[ $key ]['post_id']          = $val['post_id'];
					$unassigned[ $key ]['post_author']      = $val['post_author'];
					$unassigned[ $key ]['post_title']       = $val['post_title'];
					$unassigned[ $key ]['post_content']     = $val['post_content'];
					$unassigned[ $key ]['post_status']      = 'Unassigned';
					$unassigned[ $key ]['post_date']        = $val['post_date'];
					$unassigned[ $key ]['post_slug']        = sanitize_title( $val['post_title'] );
					$unassigned[ $key ]['display_name']     = $val['display_name'];
					$unassigned[ $key ]['comment_status']   = $val['comment_status'];
					$userVote                               = get_post_meta( $val['post_id'], 'user_voted_ids' );
					$userDownVote                           = get_post_meta( $val['post_id'], 'user_down_voted_ids' );
					$unassigned[ $key ]['vote_ids']         = $userVote[0];
					$unassigned[ $key ]['down_vote_ids']    = $userDownVote[0];
					$userSubscribe                          = get_post_meta( $val['post_id'], 'user_subscribed' );
					$unassigned[ $key ]['subscribe_ids']    = $userSubscribe[0] != null ? $userSubscribe[0] : array();
					$unassigned[ $key ]['vote_length']      = count( explode( ' , ', $userVote[0] ) );
					$unassigned[ $key ]['down_vote_length'] = count( explode( ' , ', $userDownVote[0] ) );
				}
				$results = array_merge( $hasStatus, $unassigned );
			}
		}
		return new \WP_REST_Response( $results, 200 );
	}
	public function getActivityByCategory() {
		global $wpdb;
		$page           = PageLoad::getInstance();
		$project_id     = $page->getTermByKey( $_REQUEST['id'] );
		$posts          = $wpdb->prefix . 'posts';
		$postmeta       = $wpdb->prefix . 'postmeta';
		$board_activity = $wpdb->prefix . 'board_activity';
		$users          = $wpdb->prefix . 'users';
		$sql            = "SELECT {$posts}.*,{$board_activity}.*,{$users}.display_name,{$postmeta}.meta_value
                FROM {$board_activity}
                INNER JOIN {$posts} ON {$posts}.ID = {$board_activity}.post_id
                INNER JOIN {$postmeta} ON {$posts}.ID = {$postmeta}.post_id
                LEFT JOIN {$users} ON {$users}.ID = {$board_activity}.user_id
                WHERE {$board_activity}.term_id = $project_id AND {$postmeta}.meta_key = 'user_voted_ids'
                ORDER BY {$board_activity}.activity_date DESC";
		$data           = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		if ( count( $data ) > 0 ) {
			foreach ( $data as $key => $val ) {
				$results[ $key ]['term_id']              = $val['term_id'];
				$results[ $key ]['post_id']              = $val['post_id'];
				$results[ $key ]['post_author']          = $val['post_author'];
				$results[ $key ]['post_title']           = $val['post_title'];
				$results[ $key ]['post_content']         = $val['post_content'];
				$results[ $key ]['post_status']          = $val['post_content_filtered'] != '' ? $val['post_content_filtered'] : 'Unassigned';
				$results[ $key ]['post_date']            = $val['post_date'];
				$results[ $key ]['activity_date_format'] = human_time_diff( strtotime( $val['activity_date'] ), time() ) . ' ago';
				$results[ $key ]['activity_date']        = $val['activity_date'];
				$results[ $key ]['post_slug']            = sanitize_title( $val['post_title'] );
				$results[ $key ]['activity_name']        = $val['activity_name'];
				$results[ $key ]['display_name']         = $val['display_name'];
				$results[ $key ]['comment_status']       = $val['comment_status'];
				$userVote                                = get_post_meta( $val['post_id'], 'user_voted_ids' );
				$userDownVote                            = get_post_meta( $val['post_id'], 'user_down_voted_ids' );
				$userSubscribe                           = get_post_meta( $val['post_id'], 'user_subscribed' );
				$results[ $key ]['vote_ids']             = $userVote[0];
				$results[ $key ]['down_vote_ids']        = $userDownVote[0];
				$results[ $key ]['subscribe_ids']        = $userSubscribe[0] != null ? $userSubscribe[0] : array();
				$results[ $key ]['vote_length']          = ( $userVote[0] != '' || $userVote[0] != null ) ? count( explode( ' , ', $userVote[0] ) ) : 0;
				$results[ $key ]['down_vote_length']     = ( $userDownVote[0] != '' || $userDownVote[0] != null ) ? count( explode( ' , ', $userDownVote[0] ) ) : 0;
			}
		}
		return new \WP_REST_Response( $results, 200 );
	}
	public function getTermUsers() {
		global $wpdb;
		$page       = PageLoad::getInstance();
		$termId     = $page->getTermByKey( $_REQUEST['id'] );
		$term_users = $wpdb->prefix . 'term_users';
		$sql        = "SELECT {$term_users}.*
                FROM {$term_users}
                WHERE {$term_users}.term_id = $termId ";
		$data       = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		return new \WP_REST_Response( $data, 200 );
	}
	public function getTermUsersAvatar() {
		global $wpdb;
		$page       = PageLoad::getInstance();
		$termId     = $page->getTermByKey( $_REQUEST['id'] );
		$term_users = $wpdb->prefix . 'term_users';
		$users      = $wpdb->prefix . 'users';
		$sql        = "SELECT {$term_users}.*, {$users}.*
                FROM {$term_users}
                LEFT JOIN {$users} ON {$users}.user_email = {$term_users}.user_email
                WHERE {$term_users}.term_id = $termId
                ORDER BY {$term_users}.ID";
		// $sql = "SELECT {$users}.*
		// FROM {$users}";
		$data       = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$userAvatar = array();
		$default    = 'https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?f=y';
		$size       = 40;
		foreach ( $data as $key => $val ) {
			array_push(
				$userAvatar,
				(object) array(
					'user_name'   => $val['display_name'],
					'user_avatar' => get_avatar_url( $val['user_email'], array( 'size' => '64', 'default' => 'gravatar_default' ) ),
					'gravatar'    => 'https://www.gravatar.com/avatar/' . md5( strtolower( trim( $val['user_email'] ) ) ) . '?d=' . urlencode( $default ) . '&s=' . $size,
				)
			);
		}
		return new \WP_REST_Response( $userAvatar, 200 );
	}
	public function getUserSubscribe() {
		global $wpdb;
		$postId   = (int) $_REQUEST['id'];
		$postmeta = $wpdb->prefix . 'postmeta';
		$sql      = "SELECT {$postmeta}.meta_value
                FROM {$postmeta}
                WHERE {$postmeta}.post_id = $postId AND {$postmeta}.meta_key = 'user_subscribed'";
		$data     = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		return new \WP_REST_Response( unserialize( $data[0]['meta_value'] ), 200 );
	}
	public function getListVote() {
		global $wpdb;
		$voteUserIds = $_REQUEST['post'];
		if ( $_REQUEST['post'] == '' || $_REQUEST['post'] == null ) {
			return;
		}
		$userVoteArr = explode( ' , ', $_REQUEST['post'] );
		$results     = array();
		foreach ( $userVoteArr as $key => $val ) {
			$user = get_user_by( 'id', $val );
			array_push(
				$results,
				array(
					'id'          => $user->ID,
					'user_name'   => $user->display_name,
					'user_email'  => $user->user_email,
					'user_avatar' => get_avatar_url(
						$user->user_email,
						array( 'size' => '64', 'default' => 'gravatar_default' )
					),
				)
			);
		}
		$reversed = array_reverse( $results );
		return new \WP_REST_Response( $reversed, 200 );
	}
	public function getListVoteExport() {
		global $wpdb;
		$postId   = (int) $_REQUEST['post'];
		$postmeta = $wpdb->prefix . 'postmeta';
		$sql      = "SELECT {$postmeta}.meta_value
                FROM {$postmeta}
                WHERE {$postmeta}.post_id = {$postId} AND {$postmeta}.meta_key = 'user_voted_ids'";
		$data     = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		if ( $data[0]['meta_value'] != null || $data[0]['meta_value'] != '' ) {
			$arrUserVote = explode( ' , ', $data[0]['meta_value'] );
		} else {
			$arrUserVote = array();
		}
		$filename = 'ListEmailVotePost-' . $postId;
		$content  = array();
		foreach ( $arrUserVote as $key => $val ) {
			$user = get_user_by( 'id', $val );
			if ( $user != false ) {
				array_push(
					$content,
					array(
						$user->ID,
						$user->user_nicename,
						$user->user_email,
					)
				);
			} else {
				array_push(
					$content,
					array(
						'Anonymous',
						'Anonymous',
						'Anonymous',
					)
				);
			}
		}
		$headers     = $this->exportCsvHeaders();
		$responseObj = array(
			'title'    => implode( ',', $headers ),
			'data'     => $content,
			'filename' => $filename,
		);
		return new \WP_REST_Response( $responseObj, 200 );
	}
	public function getListSubscribedExport() {
		global $wpdb;
		$postId         = (int) $_REQUEST['post'];
		$postmeta       = $wpdb->prefix . 'postmeta';
		$sql            = "SELECT {$postmeta}.meta_value
                FROM {$postmeta}
                WHERE {$postmeta}.post_id = {$postId} AND {$postmeta}.meta_key = 'user_subscribed'";
		$data           = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$userSubscribed = unserialize( $data[0]['meta_value'] );
		$filename       = 'ListEmailSubscribedPost-' . $postId;
		$content        = array();
		foreach ( $userSubscribed as $key => $val ) {
			$user = get_user_by( 'id', $val );
			if ( $user != false ) {
				array_push(
					$content,
					array(
						$user->ID,
						$user->user_nicename,
						$user->user_email,
					)
				);
			} else {
				array_push(
					$content,
					array(
						'Anonymous',
						'Anonymous',
						'Anonymous',
					)
				);
			}
		}
		$headers     = $this->exportCsvHeaders();
		$responseObj = array(
			'title'    => implode( ',', $headers ),
			'data'     => $content,
			'filename' => $filename,
		);
		return new \WP_REST_Response( $responseObj, 200 );
	}
	public function exportCsvHeaders() {
		$headers = array(
			'id'         => __( 'User ID', 'feedbo' ),
			'user_name'  => __( 'User Name', 'feedbo' ),
			'User Email' => __( 'User Email', 'feedbo' ),
		);
		return $headers;
	}
	public function getAllUsersAvatar() {
		global $wpdb;
		$users      = $wpdb->prefix . 'users';
		$sql        = "SELECT {$users}.*
                FROM {$users}";
		$data       = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$userAvatar = array();
		foreach ( $data as $key => $val ) {
			array_push(
				$userAvatar,
				(object) array(
					'user_name'   => $val['display_name'],
					'user_avatar' => get_avatar_url( $val['user_email'], array( 'size' => '64', 'default' => 'gravatar_default' ) ),
				)
			);
		}
		return new \WP_REST_Response( $userAvatar, 200 );
	}
	public function getBoardContent() {
		try {
			$responseObj = array(
				'status'  => true,
				'message' => __( 'Get Board is successful', 'feedbo' ),
			);

			$boardId = $_REQUEST['id'];

			if ( ! $boardId ) {
				return new \WP_Error( 'no_empty_id_board', __( 'Id Board is not empty', 'feedbo' ), array( 'status' => 404 ) );

			}

			$page = PageLoad::getInstance();
			$data = $page->run( $boardId );

			$responseObj['data'] = $data;
			return new \WP_REST_Response( $responseObj, 200 );

		} catch ( \Exception $e ) {
			$responseObj = array(
				'status'  => false,
				'message' => $e->getMessage(),
			);
			return new \WP_REST_Response( $responseObj, 500 );
		}
	}
	public function getWidgetBoardContent() {
		try {
			$responseObj = array(
				'status'  => true,
				'message' => __( 'Get Board is successful', 'feedbo' ),
			);

			$boardId = $_REQUEST['id'];

			if ( ! $boardId ) {
				return new \WP_Error( 'no_empty_id_board', __( 'Id Board is not empty', 'feedbo' ), array( 'status' => 404 ) );

			}

			$widget = WidgetLoad::getInstance();
			$data   = $widget->run( $boardId );

			$responseObj['data'] = $data;
			return new \WP_REST_Response( $responseObj, 200 );

		} catch ( \Exception $e ) {
			$responseObj = array(
				'status'  => false,
				'message' => $e->getMessage(),
			);
			return new \WP_REST_Response( $responseObj, 500 );
		}
	}
	public function getPosts() {
		$boardId             = $_REQUEST['id'];
		$page                = PageLoad::getInstance();
		$termId              = $page->getTermByKey( $boardId );
		$data                = $page->getPosts( $termId );
		$responseObj['data'] = $data;
		return new \WP_REST_Response( $responseObj, 200 );
	}
	public function getComments() {
		try {
			$responseObj = array(
				'status'  => true,
				'message' => __( 'Get comment is successful', 'feedbo' ),
			);
			$postId      = (int) $_REQUEST['id'];

			if ( ! $postId ) {
				return new \WP_Error( 'no_empty_id_post', __( 'Id Post is not empty', 'feedbo' ), array( 'status' => 404 ) );

			}
			$page                = Comment::getInstance();
			$data                = $page->run( $postId );
			$responseObj['data'] = $data;
			return new \WP_REST_Response( $responseObj, 200 );

		} catch ( \Exception $e ) {
			$responseObj = array(
				'status'  => false,
				'message' => $e->getMessage(),
			);
			return new \WP_REST_Response( $responseObj, 500 );
		}
	}

}
