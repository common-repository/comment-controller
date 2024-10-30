<?php
/**
 * Plugin Name:     Comment Controller
 * Plugin URI:      https://gitlab.com/widgitlabs/wordpress/comment-controller
 * Description:     Selectively disable comments on a per-user basis
 * Author:          Widgit Labs
 * Author URI:      https://widgit.io
 * Version:         1.1.4
 * Text Domain:     comment-controller
 * Domain Path:     languages
 *
 * @package         CommentController
 * @author          Daniel J Griffiths <dgriffiths@evertiro.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Comment_Controller' ) ) {


	/**
	 * Main Comment_Controller class
	 *
	 * @access      public
	 * @since       1.0.0
	 */
	final class Comment_Controller {


		/**
		 * The one true Comment_Controller
		 *
		 * @access      private
		 * @since       1.0.0
		 * @var         Comment_Controller $instance The one true Comment_Controller
		 */
		private static $instance;


		/**
		 * The settings object
		 *
		 * @access      public
		 * @since       1.1.0
		 * @var         object $settings The settings object
		 */
		public $settings;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @static
		 * @return      self::$instance The one true Comment_Controller
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Comment_Controller ) ) {
				self::$instance = new Comment_Controller();
				self::$instance->setup_constants();
				self::$instance->hooks();
				self::$instance->includes();
			}

			return self::$instance;
		}


		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is
		 * a single object. Therefore, we don't want the object to be cloned.
		 *
		 * @access      protected
		 * @since       1.1.0
		 * @return      void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?', 'comment-controller' ), '1.0.0' );
		}


		/**
		 * Disable unserializing of the class
		 *
		 * @access      protected
		 * @since       1.1.0
		 * @return      void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?', 'comment-controller' ), '1.0.0' );
		}


		/**
		 * Setup plugin constants
		 *
		 * @access      private
		 * @since       1.1.0
		 * @return      void
		 */
		private function setup_constants() {
			// Plugin version.
			if ( ! defined( 'COMMENT_CONTROLLER_VER' ) ) {
				define( 'COMMENT_CONTROLLER_VER', '1.1.4' );
			}

			// Plugin path.
			if ( ! defined( 'COMMENT_CONTROLLER_DIR' ) ) {
				define( 'COMMENT_CONTROLLER_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin URL.
			if ( ! defined( 'COMMENT_CONTROLLER_URL' ) ) {
				define( 'COMMENT_CONTROLLER_URL', plugin_dir_url( __FILE__ ) );
			}
		}


		/**
		 * Run plugin base hooks
		 *
		 * @access      private
		 * @since       1.1.0
		 * @return      void
		 */
		private function hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}


		/**
		 * Include required files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {
			global $comment_controller_options;

			// Load settings handler if necessary.
			if ( ! class_exists( 'Simple_Settings' ) ) {
				require_once COMMENT_CONTROLLER_DIR . 'vendor/widgitlabs/simple-settings/class-simple-settings.php';
			}

			require_once COMMENT_CONTROLLER_DIR . 'includes/admin/settings/register-settings.php';

			self::$instance->settings   = new Simple_Settings( 'comment_controller', 'settings' );
			$comment_controller_options = self::$instance->settings->get_settings();

			require_once COMMENT_CONTROLLER_DIR . 'includes/misc-functions.php';
			require_once COMMENT_CONTROLLER_DIR . 'includes/profile.php';
		}


		/**
		 * Internationalization
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public function load_textdomain() {
			// Set filter for language directory.
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$lang_dir = apply_filters( 'comment_controller_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter.
			$locale = apply_filters( 'plugin_locale', get_locale(), '' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'comment-controller', $locale );

			// Setup paths to current locale file.
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/comment-controller/' . $mofile;
			$mofile_core   = WP_LANG_DIR . '/plugins/comment-controller/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/comment-controller/ folder.
				load_textdomain( 'comment-controller', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/comment-controller/languages/ folder.
				load_textdomain( 'comment-controller', $mofile_local );
			} elseif ( file_exists( $mofile_core ) ) {
				// Look in core /wp-content/languages/plugins/comment-controller/ folder.
				load_textdomain( 'comment-controller', $mofile_core );
			} else {
				// Load the default language files.
				load_plugin_textdomain( 'comment-controller', false, $lang_dir );
			}
		}
	}
}


/**
 * The main function responsible for returning the one true Comment_Controller
 * instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without
 * needing to declare the global.
 *
 * Example: <?php $comment_controller = Comment_Controller(); ?>
 *
 * @since       1.1.0
 * @return      Comment_Controller The one true Comment_Controller
 */
function comment_controller() {
	return Comment_Controller::instance();
}

// Get things started.
Comment_Controller();
