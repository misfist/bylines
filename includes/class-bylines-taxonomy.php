<?php
/**
 * The file that registers custom taxonomy
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/misfist
 * @since      1.0.0
 *
 * @package    Bylines
 * @subpackage Bylines/includes
 */

class Bylines_Taxonomy {

	/**
	 * Taxonomy Name
	 * @var string $taxonomy
	 */
	public $taxonomy = 'author';

	/**
	 * Post Types
	 *
	 * @var array $post_types
	 */
	public $post_types = array( 'post', 'page', 'issue', 'book' );

	/**
	 * Constructor
	 *
	 * @since     1.0.0
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize
	 *
	 * @since     1.0.0
	 * @return void
	 */
	private function init() {
		add_action( 'init', array( $this, 'register' ), 0 );
	}

	/**
	 * Register the Taxonomy
	 *
	 * @since     1.0.0
	 * @return void
	 */
	public function register() {
		register_taxonomy( 'author', add_filters( 'bylines_supported_post_types', $this->post_types ), $args );
	}

	/**
	 * Labels
	 *
	 * @since     1.0.0
	 * @return array $labels
	 */
	public function labels() {
		$labels = array(
			'name'                       => _x( 'Authors', 'Taxonomy General Name', 'bylines' ),
			'singular_name'              => _x( 'Author', 'Taxonomy Singular Name', 'bylines' ),
			'menu_name'                  => __( 'Taxonomy', 'bylines' ),
			'all_items'                  => __( 'All Authors', 'bylines' ),
			'parent_item'                => __( 'Parent Author', 'bylines' ),
			'parent_item_colon'          => __( 'Parent Author:', 'bylines' ),
			'new_item_name'              => __( 'New Author Name', 'bylines' ),
			'add_new_item'               => __( 'Add New Author', 'bylines' ),
			'edit_item'                  => __( 'Edit Author', 'bylines' ),
			'update_item'                => __( 'Update Author', 'bylines' ),
			'view_item'                  => __( 'View Author', 'bylines' ),
			'separate_items_with_commas' => __( 'Separate authors with commas', 'bylines' ),
			'add_or_remove_items'        => __( 'Add or remove authors', 'bylines' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'bylines' ),
			'popular_items'              => __( 'Popular Authors', 'bylines' ),
			'search_items'               => __( 'Search Authors', 'bylines' ),
			'not_found'                  => __( 'Not Found', 'bylines' ),
			'no_terms'                   => __( 'No authors', 'bylines' ),
			'items_list'                 => __( 'Authors list', 'bylines' ),
			'items_list_navigation'      => __( 'Authors list navigation', 'bylines' ),
		);
		return add_filters( 'bylines_taxonomy_labels', $labels );
	}

	/**
	 * Capabilies
	 *
	 * @since     1.0.0
	 * @return array $capabilities
	 */
	public function capabilities() {
		$capabilities = array(
			'manage_terms'               => 'edit_users',
			'edit_terms'                 => 'edit_users',
			'delete_terms'               => 'delete_users',
			'assign_terms'               => 'edit_posts',
		);
		return add_filters( 'bylines_taxonomy_capabilities', $capabilities );
	}

	/**
	 * Args
	 *
	 * @since     1.0.0
	 * @return array $args
	 */
	public function args() {
		$args = array(
			'labels'                     => $this->labels(),
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'capabilities'               => $this->capabilities(),
			'show_in_rest'               => true,
			'rest_base'                  => 'authors',
		);
		return add_filters( 'bylines_taxonomy_args', $args );
	}

}

new Bylines_Taxonomy();
