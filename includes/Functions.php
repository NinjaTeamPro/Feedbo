<?php
namespace Feedbo;

defined( 'ABSPATH' ) || exit;

use Feedbo\Classes\PageLoad;

class Functions {
	protected static $instance = null;
	private $bodyClass         = 'big-ninja-feedbo';

	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		// Add class for yaymarket plugin in body element
		add_filter(
			'body_class',
			function ( $classes ) {
				return array_merge( $classes, array( $this->bodyClass ) );
			}
		);

		add_action( 'init', array( $this, 'customRewriteBasic' ) );

		add_filter( 'template_include', array( $this, 'widgetTemplate' ) );

		add_filter( 'query_vars', array( $this, 'registerQueryVar' ) );

		add_action(
			'init',
			function() {
				add_rewrite_rule( 'board/([0-9a-zA-Z-_]+)[/]?$', 'index.php?pagename=board', 'top' );
				add_rewrite_rule( 'board/([0-9a-zA-Z-_]+)/([0-9a-zA-Z-_]+)[/]?$', 'index.php?pagename=board', 'top' );
				add_rewrite_rule( 'new-board', 'index.php?pagename=board', 'top' );
				add_rewrite_rule( 'private', 'index.php?pagename=board', 'top' );
				add_rewrite_rule( 'not-found', 'index.php?pagename=board', 'top' );
			}
		);

		add_action( 'wp_head', array( $this, 'addMeta' ) );
		add_action( 'template_redirect', array( $this, 'redirectCategoryToBoard' ) );
	}

	public function widgetTemplate( $template ) {
		$boardId = get_query_var( 'widget' );
		if ( ! empty( $boardId ) ) {
			return FB_PLUGIN_PATH . '/views/pages/widget.php';
		}
		return $template;
	}

	public function registerQueryVar( $vars ) {
		$vars[] = 'widget';
		return $vars;
	}

	public function customRewriteBasic() {
		add_rewrite_rule( 'widget/([^/]+)/?$', 'index.php?widget=$matches[1]', 'top' );
		flush_rewrite_rules();
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
					return $boardMeta[0];
				}
			}
		}
		return false;
	}

	public function getTermIdByKey( $key ) {
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

	public function addMeta() {

		$currentUrl = $_SERVER['REQUEST_URI'];
		if ( '/' === substr( $currentUrl, -1 ) ) {
			$currentUrl = substr( $currentUrl, 0, strlen( $currentUrl ) - 1 );
		}
		$boardSlug     = str_replace( FB_URL_BOARD, '', $currentUrl );
		$hasPost 	   = strpos($boardSlug, '/');
		if ( false !== $hasPost ) {
			$postSlug  = substr($boardSlug, $hasPost + 1);
			$boardSlug = substr($boardSlug, 0, $hasPost);
			$boardId 	   = $this->getTermIdByKey( $boardSlug );
			$boardSettings = $this->getTermByKey( $boardSlug );
			$pageLoad      = PageLoad::getInstance();
			$listPosts     = $pageLoad->getPosts( $boardId );
			foreach ( $listPosts as $post ) {
				if ( $post['post_slug'] === $postSlug ) {
					?>
					<meta property="og:title" content="<?php echo $post['post_title'] . ' - ' . $boardSettings['name'] . ' | ' . get_bloginfo( 'name' ); ?>" />
					<meta property="og:type" content="article">
					<meta property="og:description" content="<?php echo sanitize_text_field( $post['post_content'] ); ?>" />
					<meta property="og:url" content="<?php echo $boardSettings['board_URL'] . '/'. $postSlug; ?>" />
					<?php
					break;
				}
			}
			?>
			<meta property="og:image" content="<?php echo FB_PLUGIN_URL . 'assets/img/feedbo_banner.png'; ?>" />
			<meta property="fb:app_id" content="741859919960916" />
			<?php
		} else {
			$boardSettings = $this->getTermByKey( $boardSlug );
			if ( false !== $boardSettings ) {
				?>
		  <meta property="og:title" content="<?php echo $boardSettings['name']; ?>" />
		  <meta property="og:type" content="article">
		  <meta property="og:description" content="<?php echo sanitize_text_field( $boardSettings['description'] ); ?>" />
		  <meta property="og:url" content="<?php echo $boardSettings['board_URL']; ?>" />
				<?php
			}
	
			?>
			<meta property="og:image" content="<?php echo FB_PLUGIN_URL . 'assets/img/feedbo_banner.png'; ?>" />
			<meta property="fb:app_id" content="741859919960916" />
			<?php
		}
		
	}

	public function redirectCategoryToBoard() {
		$current_url = $_SERVER['REQUEST_URI'];
		if ( preg_match( '#^/category/(.+)#', $current_url, $matches ) ) {
			$slug    = $matches[1];
			$new_url = home_url( '/board/' . $slug );
			wp_redirect( $new_url, 301 );
			exit;
		}
	}
}
