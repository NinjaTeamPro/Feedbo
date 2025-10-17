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
}