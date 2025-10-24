<?php
namespace Feedbo;

use Feedbo\SingletonTrait;
use Feedbo\Register\ScriptName;

/**
 * Feedbo Plugin Initializer
 *
 * @method static Initialize get_instance()
 */
class Initialize {

	use SingletonTrait;

	/**
	 * The Constructor that load the engine classes
	 */
	protected function __construct() {
		\Feedbo\Register\RegisterFacade::get_instance();
		// \YayRev\RestAPI::get_instance();
		// \YayRev\Ajax::get_instance();

		$this->init_hooks();
		$this->maybe_create_tables();
	}

	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( $this, 'add_rewrite_rules' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
		add_action( 'template_redirect', array( $this, 'handle_react_routes' ) );
	}

	private function maybe_create_tables() {
		$version_key     = 'feedbo_db_version';
		$current_version = get_option( $version_key, '0' );
		// if ( version_compare( $current_version, FB_PLUGIN_VERSION, '<' ) ) {
		// 	Plugin::create_tables();
		// }
	}

	public function enqueue_scripts() {

		wp_enqueue_script( ScriptName::PAGE_FEEDBO );

		wp_localize_script(
			ScriptName::PAGE_FEEDBO,
			'feedbo',
			array(
				'axiosUrl'       => site_url() . '/wp-json',
				'pluginUrl'      => FB_PLUGIN_URL,
				'siteUrl'        => site_url(),
				'siteName'       => get_bloginfo( 'name' ),
				'logoutUrl'      => wp_logout_url(),
				'apiNonce'       => wp_create_nonce( 'wp_rest' ),
				'defineUrlBoard' => FB_URL_BOARD,
			)
		);

		wp_enqueue_style( ScriptName::STYLE_FEEDBO );

	}

	/**
	 * Add rewrite rules for React Router routes
	 */
	public function add_rewrite_rules() {
		// Add rewrite rules for all React Router routes
		add_rewrite_rule( '^new-board/?$', 'index.php?feedbo_route=new-board', 'top' );
		add_rewrite_rule( '^board/([^/]+)/?$', 'index.php?feedbo_route=board&board_id=$matches[1]', 'top' );
		add_rewrite_rule( '^board/([^/]+)/([^/]+)/?$', 'index.php?feedbo_route=board&board_id=$matches[1]&post_slug=$matches[2]', 'top' );
		add_rewrite_rule( '^private/?$', 'index.php?feedbo_route=private', 'top' );
		add_rewrite_rule( '^not-found/?$', 'index.php?feedbo_route=not-found', 'top' );

		// Flush rewrite rules if this is the first time adding them
		if ( ! get_option( 'feedbo_rewrite_rules_flushed' ) ) {
			flush_rewrite_rules();
			update_option( 'feedbo_rewrite_rules_flushed', true );
		}
	}

	/**
	 * Add custom query vars
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'feedbo_route';
		$vars[] = 'board_id';
		$vars[] = 'post_slug';
		return $vars;
	}

	/**
	 * Handle React Router routes
	 */
	public function handle_react_routes() {
		$feedbo_route = get_query_var( 'feedbo_route', false );
		
		if ( $feedbo_route ) {
			// Load the same template that would be used for the homepage
			// This ensures the React app loads and React Router handles the actual routing
			include get_template_directory() . '/index.php';
			exit;
		}
	}
}