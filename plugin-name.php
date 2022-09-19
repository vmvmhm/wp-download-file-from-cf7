<?php

/**
 *
 * The plugin bootstrap file
 *
 * This file is responsible for starting the plugin using the main plugin class file.
 *
 * @since 0.0.1
 * @package download_file_for_cf7
 *
 * @wordpress-plugin
 * Plugin Name:     Donwload Files for CF7
 * Description:     Download files from a CF7 form after submitting
 * Version:         0.0.1
 * Author:          Víctor Valera
 * Author URI:      https://www.example.com
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     wp-download-file-from-cf7
 * Domain Path:     /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not permitted.' );
}

if ( ! class_exists( 'download_file_for_cf7' ) ) {

	/*
	 * main download_file_for_cf7 class
	 *
	 * @class download_file_for_cf7
	 * @since 0.0.1
	 */
	class download_file_for_cf7 {

		/*
		 * download_file_for_cf7 plugin version
		 *
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 * The single instance of the class.
		 *
		 * @var download_file_for_cf7
		 * @since 0.0.1
		 */
		protected static $instance = null;

		/**
		 * Main download_file_for_cf7 instance.
		 *
		 * @since 0.0.1
		 * @static
		 * @return download_file_for_cf7 - main instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * download_file_for_cf7 class constructor.
		 */
		public function __construct() {
			$this->load_plugin_textdomain();
			$this->define_constants();
			$this->includes();
			$this->define_actions();
		}

		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'plugin-name', false, basename( dirname( __FILE__ ) ) . '/lang/' );
		}

		/**
		 * Include required core files
		 */
		public function includes() {
            // Example
			require_once __DIR__ . '/includes/loader.php';

			// Load custom functions and hooks
			require_once __DIR__ . '/includes/includes.php';
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}


		/**
		 * Define download_file_for_cf7 constants
		 */
		private function define_constants() {
			define( 'download_file_for_cf7_PLUGIN_FILE', __FILE__ );
			define( 'download_file_for_cf7_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			define( 'download_file_for_cf7_VERSION', $this->version );
			define( 'download_file_for_cf7_PATH', $this->plugin_path() );
		}

		/**
		 * Define download_file_for_cf7 actions
		 */
		public function define_actions() {
			//
		}

		/**
		 * Define download_file_for_cf7 menus
		 */
		public function define_menus() {
            //
		}
	}

	$download_file_for_cf7 = new download_file_for_cf7();
}
