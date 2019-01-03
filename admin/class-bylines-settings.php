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

 // Exit if accessed directly
 defined( 'ABSPATH' ) || exit;
 
 class Site_Configuration_Settings {

	/**
	 * Constructor
	 */
 	public function __construct() {
 		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
 		add_action( 'admin_init', array( $this, 'init_settings'  ) );
 	}

	/**
	 * Add Settings Menu
	 *
	 * @since 1.0.0
	 * @return void
	 */
 	public function add_admin_menu() {
 		add_options_page(
 			esc_html__( 'Site Configuration', 'core-functionality' ),
 			esc_html__( 'Site Configuration', 'core-functionality' ),
 			'manage_options',
 			'site-configuration',
 			array( $this, 'page_layout' )
 		);
 	}

	/**
	 * Initialize Settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
 	public function init_settings() {
 		register_setting(
 			'site_config_group',
 			'core_site_config'
 		);

 		add_settings_section(
 			'site_config_section',
 			'',
 			false,
 			'core_site_config'
 		);

 		add_settings_field(
 			'custom-post-types-active',
 			__( 'Activate Custom Post Types', 'core-functionality' ),
 			array( $this, 'render_cpt_field' ),
 			'core_site_config',
 			'site_config_section'
 		);
 	}

	/**
	 * Settings Page Markup
	 *
	 * @since 1.0.0
	 * @return void
	 */
 	public function page_layout() {
 		if ( !current_user_can( 'manage_options' ) )  {
 			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'core-functionality' ) );
 		}

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/bylines-settings.php';
 	}

	/**
	 * Custom Post Type Field Selector
	 *
	 * @since 1.0.0
	 * @return void
	 */
 	function render_cpt_field() {
 		$options = get_option( 'core_site_config' );

 		$value = isset( $options['custom-post-types-active'] ) ? $options['custom-post-types-active'] : '';

 		echo '<select name="site_config[custom-post-types-active]" class="custom-post-types-active_field">';
 		echo '	<option value="issue" ' . selected( $value, 'issue', false ) . '> ' . __( 'Issue', 'core-functionality' ) . '</option>';
 		echo '	<option value="book" ' . selected( $value, 'book', false ) . '> ' . __( 'Book', 'core-functionality' ) . '</option>';
 		echo '</select>';
 		echo '<p class="description">' . __( 'Select the post types to activate', 'core-functionality' ) . '</p>';
 	}

 }

 new Site_Configuration_Settings;
