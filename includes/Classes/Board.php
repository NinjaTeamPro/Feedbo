<?php
namespace Feedbo\Classes;

defined( 'ABSPATH' ) || exit;

class Board {
	
	protected static $instance = null;
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function getBoards() {
        $args        = array(
			'taxonomy'   => 'category',
			'order'      => 'ASC',
			'hide_empty' => false,
			'meta_key'   => 'board_Setting',
		);
		$results     = array();
		$terms_query = new \WP_Term_Query( $args );
		if ( ! empty( $terms_query->terms ) ) {
			foreach ( $terms_query->terms as $term ) {
				$boardMeta = get_term_meta( $term->term_id, 'board_Setting' );
				if ( !empty( $boardMeta ) && isset( $boardMeta[0] ) && $boardMeta[0]['board_URL'] ) {
					$results[] = array(
                        'id' => $term->term_id,
                        'name' => $term->name,
                        'url' => $boardMeta[0]['board_URL']
                    );
				}
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
					$data[ $key ]['user_avatar'] = get_avatar_url( $val['user_email'], array( 'size' => '64' ) );
				}
			}
			return count( $data ) > 0 ? $data : array();
		}
		return array();
	}
	
}
