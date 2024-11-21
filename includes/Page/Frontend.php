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
			add_filter( 'pre_get_document_title', array( $this, 'generate_custom_title' ), 1000 );
			add_action( 'template_redirect', array( $this, 'custom_redirect_homepage' ) );

			add_filter( 'wp_head', array( $this, 'wp_head' ) );
			add_action( 'login_head', array( $this, 'wp_head' ) );
			add_action( 'register_form', array( $this, 'add_re_captcha_fields' ) );
			add_filter( 'registration_errors', array( $this, 'custom_registration_errors' ), 10, 3 );

			add_filter( 'feedbo_redirect_url', array( $this, 'feedbo_redirect_url' ), 10, 1 );

		}

		public function feedbo_redirect_url( $redirectUrl ) {
			if ( is_user_logged_in() ) {
				if ( wp_safe_redirect( home_url( '/' ) ) ) {
					exit;
				}
			}
			$linkRedirect = get_option( 'mo_openid_relative_login_redirect_url' );
			$redirectUrl  = site_url() . '' . $linkRedirect;
			if ( isset( $_COOKIE['feedbo_previous_url'] ) ) {
				$feedbo_previous_url = sanitize_text_field( $_COOKIE['feedbo_previous_url'] );
				if ( ! str_contains( $feedbo_previous_url, 'wp-login.php' ) && ! str_contains( $feedbo_previous_url, 'wp-register.php' ) ) {
					$redirectUrl = $feedbo_previous_url;
				}
			}
			return $redirectUrl;
		}

		public function custom_redirect_homepage() {
			$requestURL = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
			if ( FB_URL_BOARD === $requestURL ) {
				wp_redirect( home_url(), 301 );
				exit();
			}
		}

		public function generate_custom_title( $title ) {
			$requestURL = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
			$boardId    = '';
			if ( str_starts_with( $requestURL, FB_URL_BOARD ) ) {
				$boardId = str_replace( FB_URL_BOARD, '', $requestURL );
				if ( str_ends_with( $requestURL, '/' ) ) {
					$boardId = substr( $boardId, 0, strlen( $boardId ) - 1 );
				}
				if ( ! empty( $boardId ) ) {
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
							if ( $boardURL === site_url() . FB_URL_BOARD . $boardId ) {
								$title = $boardMeta[0]['name'] . ' | ' . get_bloginfo( 'name' );
							}
						}
					}
				}
			}

			return $title;
		}

		public function add_re_captcha_fields() {
			?>
			<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
			<?php
		}

		public function custom_registration_errors( $errors, $sanitized_user_login, $user_email ) {
			if ( ! array_key_exists( 'feedbo-gotl-signin', $_GET ) ) {
				if ( ! Captcha::isValid( $_POST['recaptcha_response'] ) ) {
					$errors->add( 'captcha_validation_failed', __( 'Captcha validation failed.', 'feedbo' ) );
				}
			}

			return $errors;
		}

		public function wp_head() {
			?>
			<script src="https://www.google.com/recaptcha/api.js?render=<?php echo FB_RECAPTCHA_KEY; ?>"></script>
			<script type="text/javascript">var FB_RECAPTCHA_KEY = '<?php echo FB_RECAPTCHA_KEY; ?>';</script>
			<?php
		}

		public function enqueueScripts() {
			$axiosURL = site_url() . '/wp-json';
			wp_dequeue_style( 'twentytwenty-style' );
			wp_deregister_style( 'twentytwenty-style' );
			wp_enqueue_style( 'feedbo_front_end_style', FB_PLUGIN_URL . 'assets/css/feedbo_frontend_style.css', null, true );
			wp_enqueue_style( 'style_main', FB_PLUGIN_URL . 'assets/dist/css/main.css', null, true );
			wp_enqueue_style( 'hide-header-footer', FB_PLUGIN_URL . 'assets/headerfooter.css', null, true );

			wp_enqueue_script( 'js_main', FB_PLUGIN_URL . 'assets/dist/js/main.js', array( 'jquery' ), null, true );
			wp_localize_script(
				'js_main',
				'bigNinjaVoteWpdata',
				array(
					'axiosUrl'       => $axiosURL,
					'pluginUrl'      => FB_PLUGIN_URL,
					'siteUrl'        => site_url(),
					'siteName'       => get_bloginfo( 'name' ),
					'logoutUrl'      => wp_logout_url(),
					'apiNonce'       => wp_create_nonce( 'wp_rest' ),
					'defineUrlBoard' => FB_URL_BOARD,
				)
			);
			wp_enqueue_media();
		}

		public function my_custom_login_stylesheet() {
			?>
			<script src="https://www.google.com/recaptcha/api.js?render=<?php echo FB_RECAPTCHA_KEY; ?>"></script>
			<script type="text/javascript">
				grecaptcha.ready(function () {
					grecaptcha.execute('<?php echo FB_RECAPTCHA_KEY; ?>', { action: 'submit' }).then(function (token) {
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
			wp_enqueue_style( 'custom-login', FB_PLUGIN_URL . 'assets/style-login.css' );
			wp_enqueue_style( 'style_main', FB_PLUGIN_URL . 'assets/dist/css/main.css', null, true );
			wp_enqueue_script( 'custom-login-js', FB_PLUGIN_URL . 'assets/js-login.js', array( 'jquery' ), null, true );
			wp_localize_script(
				'custom-login-js',
				'bigNinjaVoteUrl',
				array(
					'ajaxUrl'   => $axiosURL,
					'pluginUrl' => FB_PLUGIN_URL,
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
			if ( is_user_logged_in() ) {
				if ( wp_safe_redirect( home_url( '/' ) ) ) {
					exit;
				}
			}
			$linkRedirect = get_option( 'mo_openid_relative_login_redirect_url' );
			$redirectTo   = site_url() . '' . $linkRedirect;
			if ( isset( $_COOKIE['feedbo_previous_url'] ) ) {
				$feedbo_previous_url = sanitize_text_field( $_COOKIE['feedbo_previous_url'] );
				if ( ! str_contains( $feedbo_previous_url, 'wp-login.php' ) && ! str_contains( $feedbo_previous_url, 'wp-register.php' ) ) {
					$redirectTo = $feedbo_previous_url;
				}
			}
			return $redirectTo;
		}

		public function custom_loginlogo_url( $url ) {
			return site_url();
		}

	}


}
