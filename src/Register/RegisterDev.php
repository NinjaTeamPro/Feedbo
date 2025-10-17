<?php
namespace Feedbo\Register;

use Feedbo\SingletonTrait;
use Feedbo\Register\ScriptName;

/**
 * Register in Development Mode
 * Will get deleted in production
 */
class RegisterDev {
	use SingletonTrait;

	/** Hooks Initialization */
	protected function __construct() {
		add_action( 'wp_head', array( $this, 'render_dev_refresh' ), 5 );
		add_action( 'init', array( $this, 'register_all_scripts' ) );
	}

	public function render_dev_refresh() {
		echo '<script type="module">
        import RefreshRuntime from "http://localhost:3001/@react-refresh"
        RefreshRuntime.injectIntoGlobalHook(window)
        window.$RefreshReg$ = () => {}
        window.$RefreshSig$ = () => (type) => type
        </script>';
	}

	public function register_all_scripts() {
		$deps = array( 'react', 'react-dom', 'wp-hooks', 'wp-i18n' );

		wp_register_script( ScriptName::PAGE_FEEDBO, 'http://localhost:3001/main.tsx', $deps, null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_set_script_translations( ScriptName::PAGE_FEEDBO, 'feedbo', FB_PLUGIN_PATH . 'languages' );
	}
}
