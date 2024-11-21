<?php
/**
 * Plugin Name: Feedbo Plugin
 * Description: Vote on existing ideas or suggest new ones.
 * Author: Ninja Team
 * Text Domain: feedbo
 * Version: 2.0.0
 */

namespace Feedbo;

defined( 'ABSPATH' ) || exit;

define( 'FB_PREFIX', 'feedbo' );
define( 'FB_URL_BOARD', '/board/' );
define( 'FB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'FB_PLUGIN_NAME', plugin_basename( __FILE__ ) );
define( 'FB_PLUGIN_VERSION', '2.0.0' );
if ( ! defined( 'FB_PLUGIN_FILE' ) ) {
	define( 'FB_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'FB_PLUGIN_DIR' ) ) {
	define( 'FB_PLUGIN_DIR', plugin_dir_path( FB_PLUGIN_FILE ) );
}

// define( 'FB_RECAPTCHA_KEY', '6LfoiLslAAAAAPlhxIS_HDwuUCxa415E3iWGqqJu' );
// define( 'FB_RECAPTCHA_SECRET', '6LfoiLslAAAAANprMfE3iPeD2aYW5VEaikHOu90U' );
define( 'FB_RECAPTCHA_KEY', '6Ld6a-YkAAAAAMH-zAsjPMz3Oh4IczQeNZtGh5Rg' );
define( 'FB_RECAPTCHA_SECRET', '6Ld6a-YkAAAAANxnVlWfNZdunOA6ppYsdX_F0PQ0' );
spl_autoload_register(
	function ( $class ) {
		$prefix   = __NAMESPACE__; // project-specific namespace prefix
		$base_dir = __DIR__ . '/includes'; // base directory for the namespace prefix
		$len      = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) { // does the class use the namespace prefix?
			return; // no, move to the next registered autoloader
		}
		$relative_class_name = substr( $class, $len );
		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php
		$file = $base_dir . str_replace( '\\', '/', $relative_class_name ) . '.php';
		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);
function init() {
	Plugin::getInstance();
	I18n::getInstance();
	Page\Frontend::getInstance();
	Page\Backend::getInstance();
	Page\GoogleOnTab::getInstance();
	Api\GetApi::getInstance();
	Api\AddApi::getInstance();
	Api\DeleteApi::getInstance();
	YoastSEO::getInstance();
}
add_action( 'plugins_loaded', 'Feedbo\\init' );
add_filter( 'show_admin_bar', '__return_false' );
register_activation_hook( __FILE__, array( 'Feedbo\\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Feedbo\\Plugin', 'deactivate' ) );
