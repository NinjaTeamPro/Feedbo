<?php

namespace Feedbo\Page;

use Feedbo\Classes\Captcha;
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Frontend' ) ) {
	class Frontend {

		protected static $instance = null;
		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
		private function __construct() {
			add_shortcode( 'feedbo', array( $this, 'showDashBoard' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ), 20 );
			add_filter( 'login_redirect', array( $this, 'redirectPreviousPage' ), 10, 3 );
			add_action( 'login_enqueue_scripts', array( $this, 'my_custom_login_stylesheet' ) );
			add_filter( 'login_headerurl', array( $this, 'custom_loginlogo_url' ) );

			add_filter( 'wp_head', array( $this, 'wp_head' ) );
			add_action( 'login_head', array( $this, 'wp_head' ) );
			add_action( 'register_form',  array( $this, 'add_re_captcha_fields' ) );
			add_filter( 'registration_errors', array( $this, 'custom_registration_errors' ), 10, 3 );
		}

		public function add_re_captcha_fields() {
			?>
			<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
			<?php
		}

		public function custom_registration_errors( $errors, $sanitized_user_login, $user_email ) {
			if ( ! Captcha::isValid( $_POST['recaptcha_response'] ) ) {
				$errors->add( 'captcha_validation_failed', __( 'Captcha validation failed.', 'feedbo' ) );
			}
			return $errors;
		}
		public function wp_head() {
			?>
			<script src="https://www.google.com/recaptcha/api.js?render=<?php echo MV_RECAPTCHA_KEY; ?>"></script>
			<script type="text/javascript">var mv_recaptcha_key = '<?php  echo MV_RECAPTCHA_KEY; ?>';</script>
			<?php
		}
		public function enqueueScripts() {
			$axiosURL = site_url() . '/wp-json';
			wp_dequeue_style( 'twentytwenty-style' );
			wp_deregister_style( 'twentytwenty-style' );
			wp_enqueue_style( 'feedbo_front_end_style', MV_PLUGIN_URL . 'assets/css/feedbo_frontend_style.css', null, true );
			wp_enqueue_style( 'style_main', MV_PLUGIN_URL . 'assets/dist/css/main.css', null, true );
			wp_enqueue_style( 'hide-header-footer', MV_PLUGIN_URL . 'assets/headerfooter.css', null, true );
			wp_enqueue_script( 'js_main', MV_PLUGIN_URL . 'assets/dist/js/main.js', array( 'jquery' ), null, true );
			wp_localize_script(
				'js_main',
				'bigNinjaVoteWpdata',
				array(
					'axiosUrl'       => $axiosURL,
					'pluginUrl'      => MV_PLUGIN_URL,
					'siteUrl'        => site_url(),
					'logoutUrl'      => wp_logout_url(),
					'apiNonce'       => wp_create_nonce( 'wp_rest' ),
					'defineUrlBoard' => MV_URL_BOARD,
				)
			);
			wp_enqueue_media();
		}
		public function my_custom_login_stylesheet() {
			?>
			<script src="https://www.google.com/recaptcha/api.js?render=<?php echo MV_RECAPTCHA_KEY; ?>"></script>
			<script type="text/javascript">
				grecaptcha.ready(function () {
					grecaptcha.execute('<?php echo MV_RECAPTCHA_KEY; ?>', { action: 'submit' }).then(function (token) {
						var recaptchaResponse = document.getElementById('recaptchaResponse');
						recaptchaResponse.value = token;
					});
				});
			</script>
			<?php
			$axiosURL = site_url() . '/wp-json';
			wp_dequeue_style( 'buttons' );
			wp_deregister_style( 'buttons' );
			wp_dequeue_style( 'mo_openid_admin_settings_style' );
			wp_deregister_style( 'mo_openid_admin_settings_style' );
			wp_dequeue_style( 'mo-wp-bootstrap-social' );
			wp_deregister_style( 'mo-wp-bootstrap-social' );
			wp_dequeue_style( 'mo-wp-bootstrap-main' );
			wp_deregister_style( 'mo-wp-bootstrap-main' );
			wp_enqueue_style( 'custom-login', MV_PLUGIN_URL . 'assets/style-login.css' );
			wp_enqueue_style( 'style_main', MV_PLUGIN_URL . 'assets/dist/css/main.css', null, true );
			wp_enqueue_script( 'custom-login-js', MV_PLUGIN_URL . 'assets/js-login.js', array( 'jquery' ), null, true );
			wp_localize_script(
				'custom-login-js',
				'bigNinjaVoteUrl',
				array(
					'ajaxUrl'   => $axiosURL,
					'pluginUrl' => MV_PLUGIN_URL,
					'siteURL'   => site_url(),
				)
			);
		}
		public function showDashBoard() {
			$dashboard  = '';
			$dashboard .= '<div id="app"></div>';
			return $dashboard;
		}
		public function redirectPreviousPage( $redirectTo, $request, $user ) {
			$linkRedirect = get_option( 'mo_openid_relative_login_redirect_url' );
			$redirectTo   = site_url() . '' . $linkRedirect;
			return $redirectTo;
		}
		public function custom_loginlogo_url( $url ) {
			return site_url();
		}
	}
}
