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

 // Exit if accessed directly
 defined( 'ABSPATH' ) || exit;

class Bylines_Taxonomy {

	/**
	 * Taxonomy Name
	 * @var string $taxonomy
	 */
	public $author_taxonomy = 'guest-author';

	/**
	 * Taxonomy Args
	 * @var array $author_args
	 */
	public $author_args;

	/**
	 * Post Types
	 *
	 * @var array $post_types
	 */
	public $post_types = array( 'post', 'page', 'issue', 'book' );

  /**
   * Capabilities
   *
   * @var string $capabilities
   */
  public $capabilities;

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
    add_action( 'parent_file', array( $this, 'highlight_menu' ) );

		$this->authors_taxonomy();
		$this->load_dependencies();
    $this->capabilities = 'edit_users';
	}

	/**
	 * Load Dependencies
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_dependencies() {
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bylines-meta.php';
	}

	/**
	 * Register the Taxonomy
	 *
	 * @since     1.0.0
	 * @return void
	 */
	public function register() {
		register_taxonomy( $this->author_taxonomy, apply_filters( 'bylines_supported_post_types', $this->post_types ), $this->author_args );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Author Taxonomy
	 *
	 * @since     1.0.0
	 * @return array $args
	 */
	public function authors_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Authors', 'Taxonomy General Name', 'bylines' ),
			'singular_name'              => _x( 'Author', 'Taxonomy Singular Name', 'bylines' ),
			'menu_name'                  => __( 'Authors', 'bylines' ),
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
		$labels = apply_filters( 'bylines_taxonomy_labels', $labels );

		$capabilities = array(
			'manage_terms'               => 'edit_users',
			'edit_terms'                 => 'edit_users',
			'delete_terms'               => 'delete_users',
			'assign_terms'               => 'edit_posts',
		);
		$capabilities = apply_filters( 'bylines_taxonomy_capabilities', $capabilities );

		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
      'show_in_menu'               => false,
			'show_tagcloud'              => true,
			'capabilities'               => $capabilities,
			'show_in_rest'               => true,
			'rest_base'                  => 'authors',
      'rewrite'                    => array( 'slug' => 'guest-author' ),
		);
		$this->author_args = apply_filters( 'bylines_taxonomy_args', $args );
	}

  /**
   * Add Admin Menu
   *
   * @since 1.0.0
   * @return void
   */
	public function add_admin_menu() {
		add_menu_page(
        __( 'Authors', 'bylines' ),
        __( 'Authors', 'bylines' ),
        $this->capabilities,
        'edit-tags.php?taxonomy=guest-author',
        '',
        'dashicons-id',
        apply_filters( 'bylines_taxonomy_menu_position', 65 )
    );
	}

  /**
   * Highlight the Admin Menu
   *
   * @since 1.0.0
   * @param  string $parent_file
   * @return string $parent_file
   */
  public function highlight_menu( $parent_file ) {
    if ( get_current_screen()->taxonomy == 'guest-author' ) {
      $parent_file = 'edit-tags.php?taxonomy=guest-author';
    }
    return $parent_file;
  }

  public function rewrite_rules() {
    add_rewrite_rule( '^author/([0-9]+)/?', 'index.php?term_slug=$matches[1]', 'top' );
  }

}

new Bylines_Taxonomy();
