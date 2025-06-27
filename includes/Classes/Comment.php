<?php
namespace Feedbo\Classes;

defined( 'ABSPATH' ) || exit;

class Comment {
	private $currentUser;
	protected static $instance = null;
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	public function __construct() {
		$this->currentUser = wp_get_current_user();
	}
	public function run( $postId ) {
		return array(
			'comments'      => $this->getComments( $postId ),
			'listVoter'     => $this->getListVote( $postId ),
			'listSubscribe' => $this->getUserSubscribe( $postId ),
		);
	}
	public function getComments( $postId ) {
		global $wpdb;
		$comments    = $wpdb->prefix . 'comments';
		$commentmeta = $wpdb->prefix . 'commentmeta';
		$posts       = $wpdb->prefix . 'posts';
		$sql         = "SELECT {$comments}.*, {$commentmeta}.*,{$posts}.*
                FROM {$comments}
                LEFT JOIN {$commentmeta} ON {$comments}.comment_ID = {$commentmeta}.comment_id AND {$commentmeta}.meta_key = 'attachment_id'
                LEFT JOIN {$posts} ON {$commentmeta}.meta_value = {$posts}.ID
                WHERE {$comments}.comment_post_ID = $postId
                ORDER BY {$comments}.comment_date";
		$data        = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
		$results     = array();
		if ( count( $data ) > 0 ) {
			foreach ( $data as $key => $val ) {
				$results[ $key ]['comment_ID']          = $val['comment_ID'];
				$results[ $key ]['comment_id']          = $val['comment_id'];
				$results[ $key ]['comment_post_ID']     = $val['comment_post_ID'];
				$results[ $key ]['comment_author']      = $val['comment_author'];
				$results[ $key ]['comment_date']        = $val['comment_date'];
				$results[ $key ]['comment_date_format'] = human_time_diff( strtotime( $val['comment_date'] ), time() ) . ' ago';
				$results[ $key ]['comment_content']     = $val['comment_content'];
				$commentLike                            = get_comment_meta( $val['comment_ID'], 'users_like' );
				$commentImage                           = get_comment_meta( $val['comment_ID'], 'attachment_id' );
				$isPost                                 = get_comment_meta( $val['comment_ID'], 'is_post' );
				$results[ $key ]['users_like']          = count($commentLike) > 0 ? $commentLike[0] : null;
				$results[ $key ]['comment_image']       = count($commentImage) > 0 ? $commentImage[0] : null;
				$results[ $key ]['userslike_length']    = ( count($commentImage) > 0 && $commentLike[0] != null && $commentLike[0] != '' ) ? count( explode( ' , ', $commentLike[0] ) ) : 0;
			}
		}
		return $results;
	}
	public function getListVote( $postId ) {
		global $wpdb;
		$voteMeta    = get_post_meta( $postId, 'user_voted_ids' );
		$userVoteArr = ( $voteMeta[0] != '' || $voteMeta[0] != null ) ? explode( ' , ', $voteMeta[0] ) : array();
		$results     = array();
		foreach ( $userVoteArr as $key => $val ) {
			$user = get_user_by( 'id', $val );
			array_push(
				$results,
				array(
					'id'          => false !== $user ? $user->ID : null,
					'user_name'   => false !== $user ? $user->display_name : null,
					'user_email'  => false !== $user ? $user->user_email : null,
					'user_avatar' => get_avatar_url(
						false !== $user ? $user->user_email : '',
						array( 'size' => '64', 'default' => 'gravatar_default' )
					),
				)
			);
		}
		$reversed = array_reverse( $results );
		return $results;
	}
	public function getUserSubscribe( $postId ) {
		$userSubscribe = get_post_meta( $postId, 'user_subscribed' );
		return $userSubscribe[0];
	}
}
