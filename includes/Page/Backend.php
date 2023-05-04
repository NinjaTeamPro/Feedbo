<?php

namespace Feedbo\Page;

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'Backend' ) ) {
	class Backend {

		protected static $instance = null;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
		private function __construct() {
			//Create Vote post type
			add_action( 'init', array( $this, 'vote_custom_post_type' ) );
			// Add column to custom post type
			add_filter( 'manage_edit-vote_columns', array( $this, 'my_columns' ) );
			//Show columns
			add_action( 'manage_posts_custom_column', array( $this, 'my_show_columns' ) );
			//Add metabox
			add_action( 'add_meta_boxes', array( $this, 'vote_metabox' ) );
			//Save Post
			add_action( 'save_post', array( $this, 'vote_post_type_save' ) );
		}
		public function vote_custom_post_type() {
			register_post_type(
				'Vote',
				array(
					'labels'              => array(
						'name'          => __( 'Vote Manager', 'feedbo' ),
						'singular_name' => __( 'Vote', 'feedbo' ),
						'add_new'       => __( 'Add New Vote', 'feedbo' ),
						'add_new_item'  => __( 'Add New Vote', 'feedbo' ),
						'edit_item'     => __( 'Edit Vote', 'feedbo' ),
						'search_items'  => __( 'Search Votes', 'feedbo' ),
					),
					'menu_position'       => 5,
					'public'              => true,
					'exclude_from_search' => true,
					'has_archive'         => false,
					'supports'            => array( 'title', 'editor', 'thumbnail', 'comments' ),
					'taxonomies'          => array( 'vote', 'category' ),
				)
			);
		}

		public function my_columns( $columns ) {
			unset( $columns['date'] );
			$columns['user_created'] = __( 'User Created', 'feedbo' );
			$columns['rating']       = __( 'Rating', 'feedbo' );
			$columns['date']         = __( 'Date', 'feedbo' );
			return $columns;
		}

		public function my_show_columns( $name ) {
			global $post;
			switch ( $name ) {
				case 'user_created':
					$user_created = get_post_meta( $post->ID, 'user_created', true );
					break;
				case 'categories':
					$categories = get_post_meta( $post->ID, 'categories', true );
					break;
				case 'rating':
					$rating = count( explode( ' , ', get_post_meta( $post->ID, 'user_voted_ids', true ) ) );
					break;
				case 'date':
					$date = get_post_meta( $post->ID, 'date', true );
					break;
			}
			?>
				<label><?php print $user_created; ?></label>
				<label><?php print $categories; ?></label>
				<label><?php print $rating; ?></label>
				<label><?php print $date; ?></label>
			<?php

		}

		public function vote_metabox() {
			add_meta_box( 'vote_metabox_customfields1', __( 'Author', 'feedbo' ), array( $this, 'author_metabox_display' ), 'vote', 'side' );
			add_meta_box( 'vote_metabox_customfields2', __( 'Rating', 'feedbo' ), array( $this, 'rating_metabox_display' ), 'vote', 'side' );
		}
		public function author_metabox_display() {
			global $post;
			$user_created = get_post_meta( $post->ID, 'user_created', true );
			$users        = get_users();
			?>
		   <select class="widefat" name="user_created">
				<?php
				foreach ( $users as $user ) {
					?>
						<option 
						<?php
						if ( $user->user_nicename == $user_created ) {
								echo 'selected';
						}
						?>
				  value="<?php print $user->user_nicename; ?>">
							<?php print $user->user_nicename; ?>
						</option>
				<?php } ?>
		   </select>
			<?php
		}
		public function rating_metabox_display() {
			global $post;
			$vote   = explode( ' , ', get_post_meta( $post->ID, 'user_voted_ids', true ) );
			$rating = count( $vote );
			?>
		   <input type="number" min ="0" name="rating" placeholder="Rating" disabled class="widefat" value="<?php print $rating; ?>">
			<?php
		}

		public function vote_post_type_save( $post_id ) {
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );
			if ( $is_autosave || $is_revision ) {
				return;
			}
			$post = get_post( $post_id );
			if ( $post->post_type == 'vote' ) {
				//save the custom fields data
				if ( array_key_exists( 'user_created', $_POST ) ) {
					update_post_meta( $post_id, 'user_created', $_POST['user_created'] );
				}
				if ( array_key_exists( 'categories', $_POST ) ) {
					update_post_meta( $post_id, 'categories', $_POST['categories'] );
				}
				if ( array_key_exists( 'rating', $_POST ) ) {
					if ( $_POST['rating'] != '' ) {
						update_post_meta( $post_id, 'rating', $_POST['rating'] );
					} else {
						update_post_meta( $post_id, 'rating', 1 );
					}
				}
			}
		}

	}
}
