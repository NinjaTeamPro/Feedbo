<?php
namespace Feedbo\Classes;

defined( 'ABSPATH' ) || exit;

class WidgetLoad {
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

	public function run( $boardId ) {
		$term_id = $this->getTermByKey( $boardId );
		return array(
			'currentUser'      => $this->getCurrentUser(),
			'currentUserLevel' => $this->getCurrentUserLevel( $term_id ),
			'boardInfo'        => $this->getTermMeta( $term_id ),
			'userInBoard'      => $this->getUserInBoard( $term_id ),
			'avatarAllUser'    => $this->getAvatarAllUser(),
			'listMostVotePost' => $this->getMostVotePosts( $term_id ),
			'listNewPost'      => $this->getNewPosts( $term_id ),
			'listRoadmapPost'  => $this->getRoadmapPosts( $term_id ),
		);
	}
	public function getCurrentUser() {
		$notificationSetting = array(
			'sendEmail' => true,
			'OwnPost'   => true,
			'Comment'   => true,
		);
		if ( $this->currentUser->ID != 0 ) {
			// Add user meta notification
			$userNotification = get_user_meta( $this->currentUser->ID, 'notification_setting', false );
			if ( $userNotification == false ) {
				add_user_meta( $this->currentUser->ID, 'notification_setting', $notificationSetting, true );
			}

			global $wpdb;
			$terms      = $wpdb->prefix . 'terms';
			$termUsers  = $wpdb->prefix . 'term_users';
			$sql        = "SELECT {$terms}.*
                    FROM {$terms}
                    RIGHT JOIN {$termUsers} ON {$terms}.term_id = {$termUsers}.term_id
                    WHERE {$termUsers}.user_email = '{$this->currentUser->user_email}' ";
			$boardQuery = $wpdb->get_results( $wpdb->prepare( $sql ) );
			$listBoard  = array();
			foreach ( $boardQuery as $key => $board ) {
				$boardMeta   = get_term_meta( $board->term_id, 'board_Setting' );
				$boardURL    = str_replace( '/#/board/', FB_URL_BOARD, $boardMeta[0]['board_URL'] );
				$listBoard[] = array(
					'term_id'    => $board->term_id,
					'board_URL'  => str_replace( site_url() . FB_URL_BOARD, '', $boardURL ),
					'name'       => $board->name,
					'slug'       => $board->slug,
					'term_group' => $board->term_group,
				);
			}
			$result = array(
				'ID'            => $this->currentUser->data->ID,
				'user_login'    => $this->currentUser->data->user_login,
				'user_nicename' => $this->currentUser->data->user_nicename,
				'user_email'    => $this->currentUser->data->user_email,
				'display_name'  => $this->currentUser->data->display_name,
				'user_avatar'   => get_avatar_url( $this->currentUser->data->ID, array( 'size' => '64', 'default' => 'gravatar_default' ) ),
				'notification'  => get_user_meta( $this->currentUser->data->ID, 'notification_setting', false ),
				'list_board'    => $listBoard,
			);
			return $result;
		} else {
			return array();
		}
	}
	public function getAvatarAllUser() {
		$users      = get_users( array( 'fields' => array( 'display_name', 'user_email' ) ) );
		$userAvatar = array();
		if ( count( $users ) > 0 ) {
			foreach ( $users as $key => $val ) {
				array_push(
					$userAvatar,
					array(
						'user_name'   => $val->display_name,
						'user_avatar' => get_avatar_url( $val->user_email, array( 'size' => '64', 'default' => 'gravatar_default' ) ),
					)
				);
			}
		}
		return $userAvatar;
	}
	public function getTermMeta( $boardId ) {
		$results = array();
		if ( false !== $boardId ) {
			$term = get_term_meta( $boardId );
			if ( count( $term ) > 0 ) {
				$statusColor = array();
				if ( $term['status_color'] ) {
					$status      = maybe_unserialize( $term['status_color'][0] );
					$statusColor = array(
						'Archived'    => ! empty( $status['Archived'] ) ? $status['Archived'] : '#108ee9',
						'Completed'   => ! empty( $status['Completed'] ) ? $status['Completed'] : '#87d068',
						'In Progress' => ! empty( $status['In Progress'] ) ? $status['In Progress'] : '##f50',
						'Planned'     => ! empty( $status['Planned'] ) ? $status['Planned'] : '#2db7f5',
						'Unassigned'  => ! empty( $status['Unassigned'] ) ? $status['Unassigned'] : '#a4a4a4',
					);
				}
				$results = array(
					'user_created' => count( $term['user_created'] ) ? $term['user_created'][0] : 0,
					'setting'      => count( $term['board_Setting'] ) ? maybe_unserialize( $term['board_Setting'][0] ) : array(),
					'theme'        => count( $term['theme_color'] ) ? maybe_unserialize( $term['theme_color'][0] ) : array(),
					'status'       => $statusColor,
					'logo_favicon' => count( $term['logo_favicon'] ) ? maybe_unserialize( $term['logo_favicon'][0] ) : array(),
				);
				return $results;
			} else {
				return $results;
			}
		}
		return $results;
	}
	public function getUserInBoard( $boardId ) {
		if ( false !== $boardId ) {
			global $wpdb;
			$term_users = $wpdb->prefix . 'term_users';
			$sql        = "SELECT {$term_users}.*
                    FROM {$term_users}
                    WHERE {$term_users}.term_id = $boardId ";
			$data       = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
			$results    = array();
			if ( count( $data ) > 0 ) {
				foreach ( $data as $key => $val ) {
					$data[ $key ]['user_avatar'] = get_avatar_url( $val['user_email'], array( 'size' => '64', 'default' => 'gravatar_default' ) );
				}
			}
			return count( $data ) > 0 ? $data : array();
		}
		return array();
	}
	public function getMostVotePosts( $boardId ) {
		global $wpdb;
		$results = array();
		if ( false !== $boardId ) {
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
			return $results;
		}
		return $results;
	}
	public function getNewPosts( $boardId ) {
		global $wpdb;
		$results = array();
		if ( false !== $boardId ) {
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
				$sortByNew = array_column( $results, 'post_date' );
				array_multisort( $sortByNew, SORT_DESC, $results );
			}
			return $results;
		}
		return $results;
	}
	public function getRoadmapPosts( $boardId ) {
		global $wpdb;
		$hasStatus  = array();
		$unassigned = array();
		if ( false !== $boardId ) {
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
						$hasStatus[ $key ]['display_name']     = $val['display_name'];
						$hasStatus[ $key ]['post_slug']        = sanitize_title( $val['post_title'] );
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
						$unassigned[ $key ]['display_name']     = $val['display_name'];
						$unassigned[ $key ]['post_slug']        = sanitize_title( $val['post_title'] );
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
			return $results;
		}
		return $results;
	}
	public function getCurrentUserLevel( $boardId ) {
		if ( $this->currentUser->ID != 0 && false !== $boardId ) {
			global $wpdb;
			$project_id = (int) $_REQUEST['id'];
			$term_users = $wpdb->prefix . 'term_users';
			$sql        = "SELECT {$term_users}.*
                    FROM {$term_users}
                    WHERE {$term_users}.term_id = $boardId ";
			$users      = $wpdb->get_results( $wpdb->prepare( $sql ), ARRAY_A );
			$level      = 4;
			foreach ( $users as $key => $value ) {
				if ( $value['user_email'] == $this->currentUser->user_email ) {
					$level = $value['level'];
				}
			}
			return $level;
		} else {
			return 4;
		}
	}
	public function getTermByKey( $key ) {
		$args        = array(
			'taxonomy'   => 'category',
			'order'      => 'ASC',
			'hide_empty' => false,
			'meta_key'   => 'board_Setting',
		);
		$terms_query = new \WP_Term_Query( $args );
		if ( ! empty( $terms_query->terms ) ) {
			foreach ( $terms_query->terms as $term ) {
				$boardMeta = get_term_meta( $term->term_id, 'board_Setting' );
				$boardURL  = str_replace( '/#/board/', FB_URL_BOARD, $boardMeta[0]['board_URL'] );
				if ( $boardURL === site_url() . FB_URL_BOARD . $key ) {
					return $term->term_id;
				}
			}
		}
		return false;
	}
}
