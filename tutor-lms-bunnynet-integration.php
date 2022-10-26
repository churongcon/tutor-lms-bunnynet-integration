<?php
/**
 * Plugin Name: Tutor LMS BunnyNet Integration
 * Plugin URI: https://www.themeum.com/product/tutor-lms/
 * Description: Tutor LMS BunnyNet Integration
 * Author: Themeum
 * Version: 1.0.0
 * Author URI: https://themeum.com
 * Requires at least: 5.3
 * Tested up to: 6.0.3
 * License: GPLv2 or later
 * Text Domain: tutor-lms-bunnynet-integration
 * Domain Path: /languages
 *
 * @package TutorLMSBunnyNetIntegration
 */

use Tutor\BunnyNetIntegration\AdminNotice\AdminNotice;
use Tutor\BunnyNetIntegration\Integration\BunnyNet;

if ( ! class_exists( 'TutorLMSBunnyNetIntegration' ) ) {

	/**
	 * PluginStarter main class that trigger the plugin
	 */
	final class TutorLMSBunnyNetIntegration {

		/**
		 * Plugin meta data
		 *
		 * @since v1.0.0
		 *
		 * @var $plugin_data
		 */
		private static $meta_data = array();

		/**
		 * Plugin instance
		 *
		 * @since v1.0.0
		 *
		 * @var $instance
		 */
		public static $instance = null;

		/**
		 * Register hooks and load dependent files
		 *
		 * @since v1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
            if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
				include_once __DIR__ . '/vendor/autoload.php';
			}
			// register_activation_hook( __FILE__, array( __CLASS__, 'register_activation' ) );
			// register_deactivation_hook( __FILE__, array( __CLASS__, 'register_deactivation' ) );

			add_action( 'plugins_loaded', array( $this, 'load_packages' ) );
			add_action( 'init', array( __CLASS__, 'load_textdomain' ) );
		}

		/**
		 * Plugin meta data
		 *
		 * @since v1.0.0
		 *
		 * @return array  contains plugin meta data
		 */
		public static function meta_data(): array {
			self::$meta_data['url']       = plugin_dir_url( __FILE__ );
			self::$meta_data['path']      = plugin_dir_path( __FILE__ );
			self::$meta_data['basename']  = plugin_basename( __FILE__ );
			self::$meta_data['templates'] = trailingslashit( plugin_dir_path( __FILE__ ) . 'templates' );
			self::$meta_data['views']     = trailingslashit( plugin_dir_path( __FILE__ ) . 'views' );
			self::$meta_data['assets']    = trailingslashit( plugin_dir_url( __FILE__ ) . 'assets' );

            return self::$meta_data;
		}

		/**
		 * Create and return instance of this plugin
		 *
		 * @return self  instance of plugin
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Load packages
		 *
		 * @return void
		 */
		public function load_packages() {
			// If tutor is not active then load notice only.
			if ( ! function_exists( 'tutor' ) ) {
				new AdminNotice();
			} else {
				new BunnyNet();
			}
		}

		/**
		 * Load plugin text domain
		 *
		 * @return void
		 */
		public static function load_textdomain() {
			load_plugin_textdomain( 'easy-poll', false, plugin_dir_path( __FILE__ ) . 'languages/' );
		}
	}
	// trigger.
	TutorLMSBunnyNetIntegration::instance();
}
