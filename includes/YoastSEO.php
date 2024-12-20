<?php
namespace Feedbo;

use Feedbo\Functions;
use Feedbo\Classes\PageLoad;

defined( 'ABSPATH' ) || exit;

class YoastSEO {
	protected static $instance = null;

	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		
		add_filter( 'wpseo_title', array( $this, 'wpseo_title' ), 10, 2 );
		add_filter( 'wpseo_opengraph_title', array( $this, 'wpseo_opengraph_title' ), 10, 2 );
		add_filter( 'wpseo_opengraph_url', array( $this, 'wpseo_opengraph_url' ), 10, 2 );
		add_filter( 'wpseo_opengraph_desc', array( $this, 'wpseo_opengraph_desc' ), 10, 2 );
		add_filter( 'wpseo_opengraph_image', array( $this, 'wpseo_opengraph_image' ), 10, 2 );
		add_filter( 'wpseo_schema_webpage', array( $this, 'wpseo_schema_webpage' ), 10, 4 );
		add_filter( 'wpseo_canonical', array( $this, 'edit_canonical_urls' ), 10, 2 );
		add_action( 'wpseo_head', array( $this, 'add_meta' ) );
		add_filter( 'xmlrpc_enabled', '__return_false' );
	}

	public function wpseo_title( $title, $presentation ) {
		$currentUrl = $_SERVER['REQUEST_URI'];
		if ( '/' === substr( $currentUrl, -1 ) ) {
			$currentUrl = substr( $currentUrl, 0, strlen( $currentUrl ) - 1 );
		}
		$boardSlug     = str_replace( FB_URL_BOARD, '', $currentUrl );
		$hasPost 	   = strpos($boardSlug, '/');
		if ( false !== $hasPost ) {
			$postSlug  = substr($boardSlug, $hasPost + 1);
			$boardSlug = substr($boardSlug, 0, $hasPost);
			$functions     = Functions::getInstance();
			$boardId 	   = $functions->getTermIdByKey( $boardSlug );
			$boardSettings = $functions->getTermByKey( $boardSlug );
			$pageLoad      = PageLoad::getInstance();
			$listPosts     = $pageLoad->getPosts( $boardId );
			foreach ( $listPosts as $post ) {
				if ( $post['post_slug'] === $postSlug ) {
					return $post['post_title'] . ' - ' . $boardSettings['name'] . ' | ' . get_bloginfo( 'name' );
				}
			}
		} else {
			$functions     = Functions::getInstance();
			$boardSettings = $functions->getTermByKey( $boardSlug );
			if ( false !== $boardSettings ) {
				return $boardSettings['name'];
			}
		}
		return $title;
	}

	public function wpseo_opengraph_title( $title, $presentation ) {
		$currentUrl = $_SERVER['REQUEST_URI'];
		if ( '/' === substr( $currentUrl, -1 ) ) {
			$currentUrl = substr( $currentUrl, 0, strlen( $currentUrl ) - 1 );
		}
		$boardSlug     = str_replace( FB_URL_BOARD, '', $currentUrl );
		$hasPost 	   = strpos($boardSlug, '/');
		if ( false !== $hasPost ) {
			$postSlug  = substr($boardSlug, $hasPost + 1);
			$boardSlug = substr($boardSlug, 0, $hasPost);
			$functions     = Functions::getInstance();
			$boardId 	   = $functions->getTermIdByKey( $boardSlug );
			$boardSettings = $functions->getTermByKey( $boardSlug );
			$pageLoad      = PageLoad::getInstance();
			$listPosts     = $pageLoad->getPosts( $boardId );
			foreach ( $listPosts as $post ) {
				if ( $post['post_slug'] === $postSlug ) {
					return $post['post_title'] . ' - ' . $boardSettings['name'] . ' | ' . get_bloginfo( 'name' );
				}
			}
		} else {
			$functions     = Functions::getInstance();
			$boardSettings = $functions->getTermByKey( $boardSlug );
			if ( false !== $boardSettings ) {
				return $boardSettings['name'];
			}
		}
		return $title;
	}

	public function wpseo_opengraph_url( $url, $presentation ) {
		$currentUrl = $_SERVER['REQUEST_URI'];
		if ( '/' === substr( $currentUrl, -1 ) ) {
			$currentUrl = substr( $currentUrl, 0, strlen( $currentUrl ) - 1 );
		}
		$boardSlug     = str_replace( FB_URL_BOARD, '', $currentUrl );
		$hasPost 	   = strpos($boardSlug, '/');
		if ( false !== $hasPost ) {
			$postSlug  = substr($boardSlug, $hasPost + 1);
			$boardSlug = substr($boardSlug, 0, $hasPost);
			$functions     = Functions::getInstance();
			$boardSettings = $functions->getTermByKey( $boardSlug );
			if ( false !== $boardSettings ) {
				return $boardSettings['board_URL'] . '/' . $postSlug;
			}
		} else {
			$functions     = Functions::getInstance();
			$boardSettings = $functions->getTermByKey( $boardSlug );
			if ( false !== $boardSettings ) {
				return $boardSettings['board_URL'];
			}
		}
		
		return $url;
	}

	public function wpseo_opengraph_desc( $description, $presentation ) {
		$currentUrl = $_SERVER['REQUEST_URI'];
		if ( '/' === substr( $currentUrl, -1 ) ) {
			$currentUrl = substr( $currentUrl, 0, strlen( $currentUrl ) - 1 );
		}
		$boardSlug     = str_replace( FB_URL_BOARD, '', $currentUrl );
		$hasPost 	   = strpos($boardSlug, '/');
		if ( false !== $hasPost ) {
			$postSlug  = substr($boardSlug, $hasPost + 1);
			$boardSlug = substr($boardSlug, 0, $hasPost);
			$functions     = Functions::getInstance();
			$boardId 	   = $functions->getTermIdByKey( $boardSlug );
			$pageLoad      = PageLoad::getInstance();
			$listPosts     = $pageLoad->getPosts( $boardId );
			foreach ( $listPosts as $post ) {
				if ( $post['post_slug'] === $postSlug ) {
					return $post['post_content'];
				}
			}
		} else {
			$functions     = Functions::getInstance();
			$boardSettings = $functions->getTermByKey( $boardSlug );
			if ( false !== $boardSettings ) {
				return $boardSettings['description'];
			}
		}
		return $description;
	}

	public function wpseo_opengraph_image( $image, $presentation ) {
		return FB_PLUGIN_URL . 'assets/img/feedbo_banner.png';
	}

	public function wpseo_schema_webpage($graph_piece, $context, $graph_piece_generator, $graph_piece_generators) {
		$currentUrl = $_SERVER['REQUEST_URI'];
		if(preg_match('#/board/(.*?)#', $currentUrl)) {
			if ( '/' === substr( $currentUrl, -1 ) ) {
				$currentUrl = substr( $currentUrl, 0, strlen( $currentUrl ) - 1 );
			}
			$boardSlug     = str_replace( FB_URL_BOARD, '', $currentUrl );
			$hasPost 	   = strpos($boardSlug, '/');
			if ( false !== $hasPost ) {
				$postSlug  = substr($boardSlug, $hasPost + 1);
				$boardSlug = substr($boardSlug, 0, $hasPost);
				$functions     = Functions::getInstance();
				$boardId 	   = $functions->getTermIdByKey( $boardSlug );
				$boardSettings = $functions->getTermByKey( $boardSlug );
				$pageLoad      = PageLoad::getInstance();
				$listPosts     = $pageLoad->getPosts( $boardId );
				foreach ( $listPosts as $post ) {
					if ( $post['post_slug'] === $postSlug ) {
						$graph_piece = [
							'@type' => 'WebPage',
							'@id' => $boardSettings['board_URL'] . '/' . $postSlug,
							'url' => $boardSettings['board_URL'] . '/' . $postSlug,
							'name' => $post['post_title'] . ' - ' . $boardSettings['name'] . ' | ' . get_bloginfo( 'name' ),
							'isPartOf' => $graph_piece['isPartOf'],
							'breadcrumb' => $graph_piece['breadcrumb']
						];
						break;
					}
				}
			} else {
				$functions     = Functions::getInstance();
				$boardSettings = $functions->getTermByKey( $boardSlug );
				$graph_piece = [
					'@type' => 'CollectionPage',
					'@id' => $boardSettings['board_URL'],
					'url' => $boardSettings['board_URL'],
					'name' => $boardSettings['name'],
					'isPartOf' => $graph_piece['isPartOf'],
					'breadcrumb' => $graph_piece['breadcrumb']
				];
			}
		}
		return $graph_piece;
	}

	public function edit_canonical_urls( $canonical, $presentation ) {
		$currentUrl = $_SERVER['REQUEST_URI'];
		if(preg_match('#/board/(.*?)#', $currentUrl)) {
			if ( '/' === substr( $currentUrl, -1 ) ) {
				$currentUrl = substr( $currentUrl, 0, strlen( $currentUrl ) - 1 );
			}
			$boardSlug     = str_replace( FB_URL_BOARD, '', $currentUrl );
			$hasPost 	   = strpos($boardSlug, '/');
			if ( false !== $hasPost ) {
				$postSlug  = substr($boardSlug, $hasPost + 1);
				$boardSlug = substr($boardSlug, 0, $hasPost);
				$functions     = Functions::getInstance();
				$boardSettings = $functions->getTermByKey( $boardSlug );
				$canonical     = $boardSettings['board_URL'] . '/' . $postSlug;
			} else {
				$functions     = Functions::getInstance();
				$boardSettings = $functions->getTermByKey( $boardSlug );
				$canonical     = $boardSettings['board_URL'];
			}
			
		}
		return $canonical;
	
	}

	public function add_meta() {
		?>
		<meta property="og:image" content="<?php echo FB_PLUGIN_URL . 'assets/img/feedbo_banner.png'; ?>" />
		<meta property="fb:app_id" content="741859919960916" />
		<?php
	}
}
