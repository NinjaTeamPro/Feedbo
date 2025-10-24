<?php
/**
 * Plugin Name: Feedbo Plugin
 * Description: Vote on existing ideas or suggest new ones.
 * Version: 3.0.0
 * Author: Ninja Team
 * Text Domain: feedbo
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Feedbo;

defined( 'ABSPATH' ) || exit;

define( 'FB_PREFIX', 'feedbo' );
define( 'FB_URL_BOARD', '/board/' );
define( 'FB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'FB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'FB_PLUGIN_NAME', plugin_basename( __FILE__ ) );
define( 'FB_PLUGIN_VERSION', '3.0.0' );
define( 'FB_IS_DEVELOPMENT', true );

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

require_once FB_PLUGIN_PATH . 'vendor/autoload.php';

$fb_has_required_deps = true;

if ( version_compare( PHP_VERSION, '7.2', '<' ) ) {
	require_once FB_PLUGIN_PATH . 'views/plugin-requirements/fallback-minimum-php.php';
	$fb_has_required_deps = false;
}

if ( version_compare( $GLOBALS['wp_version'], '5.5', '<' ) ) {
	require_once FB_PLUGIN_PATH . 'views/plugin-requirements/fallback-minimum-wp.php';
	$fb_has_required_deps = false;
}

if ( ! $fb_has_required_deps ) {
	add_action(
		'admin_init',
		function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);

	// Return early to prevent loading the plugin.
	return;
}

add_action(
	'plugins_loaded',
	function () {
		Initialize::get_instance();
		Frontend::get_instance();
		Admin::get_instance();
	}
);

add_filter( 'show_admin_bar', '__return_false' );

// Flush rewrite rules on plugin activation
register_activation_hook( __FILE__, function() {
	flush_rewrite_rules();
} );

// register_activation_hook( __FILE__, array( 'Feedbo\Plugin', 'activate' ) );
// register_deactivation_hook( __FILE__, array( 'Feedbo\Plugin', 'deactivate' ) );
