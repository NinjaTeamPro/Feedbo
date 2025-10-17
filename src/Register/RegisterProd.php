<?php
namespace Feedbo\Register;

use Feedbo\SingletonTrait;
use Feedbo\Register\ScriptName;

/** Register in Production Mode */
class RegisterProd {
	use SingletonTrait;

	/** Hooks Initialization */
	protected function __construct() {
		add_action( 'init', array( $this, 'register_all_scripts' ) );
	}

	public function register_all_scripts() {
		$deps = array( 'react', 'react-dom', 'wp-hooks', 'wp-i18n' );

		wp_register_script(
			ScriptName::PAGE_FEEDBO,
			FB_PLUGIN_URL . 'assets/dist/main.js',
			$deps,
			time(),
			true
		);
		wp_set_script_translations( ScriptName::PAGE_FEEDBO, 'feedbo', FB_PLUGIN_PATH . 'languages' );
		wp_script_add_data( ScriptName::PAGE_FEEDBO, 'type', 'module' );
	}
}
