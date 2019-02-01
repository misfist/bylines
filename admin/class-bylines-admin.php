<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/misfist
 * @since      1.0.0
 *
 * @package    Bylines
 * @subpackage Bylines/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bylines
 * @subpackage Bylines/admin
 * @author     Pea <pea@misfist.com>
 */
class Bylines_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		if ( is_admin() ) {

			$this->load_dependencies();

			new Bylines_Taxonomy_Meta( $this->plugin_name, $this->version );
		}

	}

	/**
	 * Load Dependent Files
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bylines-settings.php';
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bylines-meta.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bylines_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bylines_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bylines-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bylines_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bylines_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bylines-admin.js', array( 'jquery', 'wp-i18n' ), $this->version, false );

	}

}
