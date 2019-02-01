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

 class Bylines_Taxonomy_Meta {

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
    * Taxonomy
    *
    * @since 1.0.0
    * @var string $taxonomy
    */
   public $taxonomy = 'guest-author';

   /**
    * Biography Label
    *
    * @since 1.0.0
    * @var array $bio_label
    */
   public $taxonomy_meta_labels;

	 /**
	  * Constructor
	  */
 	public function __construct( $plugin_name, $version, $taxonomy = null ) {

    $this->plugin_name = $plugin_name;
		$this->version = $version;

    add_action( $this->taxonomy . '_add_form_fields',  array( $this, 'create_screen_fields'), 10, 1 );
    add_action( $this->taxonomy . '_edit_form_fields', array( $this, 'edit_screen_fields' ),  10, 2 );

    add_action( 'created_' . $this->taxonomy, array( $this, 'save_data' ), 10, 1 );
    add_action( 'edited_' . $this->taxonomy,  array( $this, 'save_data' ), 10, 1 );

    add_action( 'admin_head-edit-tags.php', array( $this, 'modify_term_labels' ) );
    add_action( 'admin_head-term.php', array( $this, 'modify_term_labels' ) );

    if( $taxonomy ) {
      $this->taxonomy = $taxonomy;
    }

    add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
    add_action( 'admin_footer', array( $this, 'add_script' ) );

    add_action( 'restrict_manage_posts', array( $this, 'admin_post_list_filters' ), 10, 2);
 	}

	/**
	 * Create the Custom Fields
	 *
	 * @since 1.0.0
	 * @param  string $taxonomy
	 * @return void
	 */
 	public function create_screen_fields( $taxonomy ) {
 		// $author_meta_first_name = '';
 		// $author_meta_last_name = '';
 		// $author_meta_nickname = '';
 		// $author_meta_biography = '';

    if( $fields = $this->set_fields() ) {

      foreach( $fields as $field ) {
        $author_meta_{$field['key']} = '';

        switch( $field['type'] ) {
          case 'image':

            echo '<div class="form-field term-group">';
            echo  '<label for="author_meta_' . $field['key'] . '">' . esc_attr__( $field['label'] ) . '</label>';
            echo  '<input type="hidden" id="author_meta_' . $field['key'] . '" name="author_meta_' . $field['key'] . '" class="custom_media_url" value="">';
            echo  '<div id="term-image-wrapper"></div>';
            echo  '<p>';
            echo    '<input type="button" class="button button-secondary author-tax_media_button" id="author-tax_media_button" name="author-tax_media_button" value="' . __( 'Add Image', 'byline' ) . '" />';
            echo    '<input type="button" class="button button-secondary author-tax_media_remove" id="author-tax_media_remove" name="author-tax_media_remove" value="' . __( 'Remove Image', 'byline' ) . '" />';
            echo  '</p>';
            echo '</div>';

            break;
          default :

            echo '<div class="form-field term-author_meta_' . $field['key'] . '-wrap">';
         		echo '	<label for="author_meta_' . $field['key'] . '">' . esc_attr__( $field['label'] ) . '</label>';
         		echo '	<input type="text" id="author_meta_' . $field['key'] . '" name="author_meta_' . $field['key'] . '" placeholder="' . esc_attr__( $field['label'] ) . '" value="' . esc_attr( $author_meta_{$field['key']} ) . '">';
         		echo '</div>';

            break;
        }
      }

    }

 		// echo '<div class="form-field term-author_meta_first_name-wrap">';
 		// echo '	<label for="author_meta_first_name">' . __( 'First Name', 'bylines' ) . '</label>';
 		// echo '	<input type="text" id="author_meta_first_name" name="author_meta_first_name" placeholder="' . esc_attr__( 'First Name', 'bylines' ) . '" value="' . esc_attr( $author_meta_{$field['key'] ) . '">';
 		// echo '</div>';
    //
 		// echo '<div class="form-field term-author_meta_last_name-wrap">';
 		// echo '	<label for="author_meta_last_name">' . __( 'Last Name', 'bylines' ) . '</label>';
 		// echo '	<input type="text" id="author_meta_last_name" name="author_meta_last_name" placeholder="' . esc_attr__( 'Last Name', 'bylines' ) . '" value="' . esc_attr( $author_meta_last_name ) . '">';
 		// echo '</div>';
    //
 		// echo '<div class="form-field term-author_meta_nickname-wrap">';
 		// echo '	<label for="author_meta_nickname">' . __( 'Nickname', 'bylines' ) . '</label>';
 		// echo '	<input type="text" id="author_meta_nickname" name="author_meta_nickname" placeholder="' . esc_attr__( 'Nickname', 'bylines' ) . '" value="' . esc_attr( $author_meta_nickname ) . '">';
 		// echo '</div>';
    //
 		// echo '<div class="form-field term-author_meta_biography-wrap">';
 		// echo '	<label for="author_meta_biography">' . __( 'Biographical Info', 'bylines' ) . '</label>';
 		// echo '	<textarea id="author_meta_biography" name="author_meta_biography" placeholder="' . esc_attr__( 'Biographical Info', 'bylines' ) . '">' . $author_meta_biography . '</textarea>';
 		// echo '</div>';
 	}

	/**
	 * Fields
	 *
	 * @since 1.0.0
	 * @param  object $term
	 * @param  string $taxonomy
	 * @return void
	 */
 	public function edit_screen_fields( $term, $taxonomy ) {
 		// $author_meta_first_name = get_term_meta( $term->term_id, 'author_meta_first_name', true );
 		// $author_meta_last_name = get_term_meta( $term->term_id, 'author_meta_last_name', true );
 		// $author_meta_nickname = get_term_meta( $term->term_id, 'author_meta_nickname', true );
 		// $author_meta_biography = get_term_meta( $term->term_id, 'author_meta_biography', true );

    if( $fields = $this->set_fields() ) {

      // var_dump( $fields );

      foreach( $fields as $field ) {
        $author_meta_{$field['key']} = get_term_meta( $term->term_id, $field['key'], true );
        if( empty( $author_meta_{$field['key']} ) ) $author_meta_{$field['key']} = '';

        switch( $field['type'] ) {
          case 'image':

            // echo '<div class="form-field term-group">';
            // echo  '<label for="showcase-taxonomy-' . $field['key'] . '">' . esc_attr__( $field['label'] ) . '</label>';
            // echo  '<input type="hidden" id="showcase-taxonomy-' . $field['key'] . '" name="showcase-taxonomy-' . $field['key'] . '" class="custom_media_url" value="">';
            // echo  '<div id="term-image-wrapper"></div>';
            // echo  '<p>';
            // echo    '<input type="button" class="button button-secondary author-tax_media_button" id="author-tax_media_button" name="author-tax_media_button" value="' . __( 'Add Image', 'byline' ) . '" />';
            // echo    '<input type="button" class="button button-secondary author-tax_media_remove" id="author-tax_media_remove" name="author-tax_media_remove" value="' . __( 'Remove Image', 'byline' ) . '" />';
            // echo  '</p>';
            // echo '</div>';

            echo '<tr class="form-field term-author_meta_' . $field['key'] . '-wrap">';
            echo '<th scope="row">';
            echo '	<label for="author_meta_' . $field['key'] . '">' . esc_attr__( $field['label'] ) . '</label>';
            echo '</th>';
            echo '<td>';

            $image_id = get_term_meta( $term->term_id, 'image', true );
            echo '<input type="hidden" id="author_meta_' . $field['key'] . '" name="author_meta_' . $field['key'] . '" value="'. $image_id .'">';
            echo '<div id="term-image-wrapper">';
            if( $image_id ) {
              echo wp_get_attachment_image( $image_id, 'thumbnail' );
            }
            echo '</div>';

            echo  '<p>';
            echo    '<input type="button" class="button button-secondary author-tax_media_button" id="author-tax_media_button" name="author-tax_media_button" value="' . __( 'Add Image', 'byline' ) . '" />';
            echo    '<input type="button" class="button button-secondary author-tax_media_remove" id="author-tax_media_remove" name="author-tax_media_remove" value="' . __( 'Remove Image', 'byline' ) . '" />';
            echo  '</p>';
            echo '</td>';
            echo '</tr>';

            break;
          default :

            echo '<tr class="form-field term-author_meta_' . $field['key'] . '-wrap">';
            echo '<th scope="row">';
            echo '	<label for="author_meta_' . $field['key'] . '">' . esc_attr__( $field['label'] ) . '</label>';
            echo '</th>';
            echo '<td>';
            echo '	<input type="text" id="author_meta_' . $field['key'] . '" name="author_meta_' . $field['key'] . '" placeholder="' . esc_attr__( $field['label'] ) . '" value="' . esc_attr( $author_meta_{$field['key']} ) . '">';
            echo '</td>';
            echo '</tr>';

            break;
        }
      }
    }

 		// if( empty( $author_meta_first_name ) ) $author_meta_first_name = '';
 		// if( empty( $author_meta_last_name ) ) $author_meta_last_name = '';
 		// if( empty( $author_meta_nickname ) ) $author_meta_nickname = '';
 		// if( empty( $author_meta_biography ) ) $author_meta_biography = '';
    //
 		// echo '<tr class="form-field term-author_meta_first_name-wrap">';
 		// echo '<th scope="row">';
 		// echo '	<label for="author_meta_first_name">' . __( 'First Name', 'bylines' ) . '</label>';
 		// echo '</th>';
 		// echo '<td>';
 		// echo '	<input type="text" id="author_meta_first_name" name="author_meta_first_name" placeholder="' . esc_attr__( 'First Name', 'bylines' ) . '" value="' . esc_attr( $author_meta_first_name ) . '">';
 		// echo '</td>';
 		// echo '</tr>';
    //
 		// echo '<tr class="form-field term-author_meta_last_name-wrap">';
 		// echo '<th scope="row">';
 		// echo '	<label for="author_meta_last_name">' . __( 'Last Name', 'bylines' ) . '</label>';
 		// echo '</th>';
 		// echo '<td>';
 		// echo '	<input type="text" id="author_meta_last_name" name="author_meta_last_name" placeholder="' . esc_attr__( 'Last Name', 'bylines' ) . '" value="' . esc_attr( $author_meta_last_name ) . '">';
 		// echo '</td>';
 		// echo '</tr>';
    //
 		// echo '<tr class="form-field term-author_meta_nickname-wrap">';
 		// echo '<th scope="row">';
 		// echo '	<label for="author_meta_nickname">' . __( 'Nickname', 'bylines' ) . '</label>';
 		// echo '</th>';
 		// echo '<td>';
 		// echo '	<input type="text" id="author_meta_nickname" name="author_meta_nickname" placeholder="' . esc_attr__( 'Nickname', 'bylines' ) . '" value="' . esc_attr( $author_meta_nickname ) . '">';
 		// echo '</td>';
 		// echo '</tr>';
    //
 		// echo '<tr class="form-field term-author_meta_biography-wrap">';
 		// echo '<th scope="row">';
 		// echo '	<label for="author_meta_biography">' . __( 'Biographical Info', 'bylines' ) . '</label>';
 		// echo '</th>';
 		// echo '<td>';
 		// echo '	<textarea id="author_meta_biography" name="author_meta_biography" placeholder="' . esc_attr__( 'Biographical Info', 'bylines' ) . '">' . $author_meta_biography . '</textarea>';
 		// echo '</td>';
 		// echo '</tr>';
 	}

  /**
   * Save Data
   *
   * @since 1.0.0
   * @param  integer $term_id
   * @return void
   */
 	public function save_data( $term_id ) {
    if( $fields = $this->set_fields() ) {
      foreach( $fields as $field ) {
        $author_meta_new_{$field['key']} = isset( $_POST[ "author_meta_{$field['key']}" ] ) ? sanitize_text_field( $_POST[ "author_meta_{$field['key']}" ] ) : '';
        update_term_meta( $term_id, $field['key'], $author_meta_new_{$field['key']} );
      }
    }

 		// $author_meta_new_first_name = isset( $_POST[ 'author_meta_first_name' ] ) ? sanitize_text_field( $_POST[ 'author_meta_first_name' ] ) : '';
 		// $author_meta_new_last_name = isset( $_POST[ 'author_meta_last_name' ] ) ? sanitize_text_field( $_POST[ 'author_meta_last_name' ] ) : '';
 		// $author_meta_new_nickname = isset( $_POST[ 'author_meta_nickname' ] ) ? sanitize_text_field( $_POST[ 'author_meta_nickname' ] ) : '';

 		// update_term_meta( $term_id, 'author_meta_first_name', $author_meta_new_first_name );
 		// update_term_meta( $term_id, 'author_meta_last_name', $author_meta_new_last_name );
 		// update_term_meta( $term_id, 'author_meta_nickname', $author_meta_new_nickname );
 		// update_term_meta( $term_id, 'author_meta_biography', $author_meta_new_biography );
 	}

  public function load_media() {
     if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] !== $this->taxonomy ) {
       return;
     }
     wp_enqueue_media();
   }

  public function add_script() {
     if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] !== $this->taxonomy ) {
       return;
     }

     wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bylines-admin.js', array( 'jquery', 'wp-i18n' ), $this->version, false );
  }

  /**
   * Set up Custom Fields
   *
   * @return array
   */
  public function set_fields() {

    $fields = array(
      array(
        'key'     => 'first_name',
        'label'   => __( 'First Name', 'bylines' ),
        'type'    => 'text',
      ),
      array(
        'key'     => 'last_name',
        'label'   => __( 'Last Name', 'bylines' ),
        'type'    => 'text',
      ),
      array(
        'key'     => 'nickname',
        'label'   => __( 'Nickname', 'bylines' ),
        'type'    => 'text',
      ),
      array(
        'key'     => 'display_name',
        'label'   => __( 'Display Name', 'bylines' ),
        'type'    => 'text',
      ),
      array(
        'key'     => 'image',
        'label'   => __( 'Profile Picture', 'bylines' ),
        'type'    => 'image',
      ),
    );
    return apply_filters( 'bylines_set_meta_fields', $fields );
  }

  /**
   * Add Filter to Replace Label Text
   * @return void
   */
  function modify_term_labels() {
    add_filter( 'gettext', array( $this, 'term_label_gettext' ), 10, 2 );
  }
  /**
   * Replace Label Text
   *
   * @param  string $translation
   * @param  string $original
   * @return string
   */
  function term_label_gettext( $translation, $original ) {
    global $current_screen;

    if( 'edit-guest-author' === $current_screen->id ) {
      if ( 'Name' == $original ) {
          return __( 'Display Name', 'byline' );
      }
      if ( 'Description' == $original ) {
          return __( 'Biographical Info', 'byline' );
      }
    }

    return $translation;
  }

  function admin_post_list_filters( $post_type, $which ) {

    $post_types = get_post_types( array( 'public' => true ) );
    if( !in_array( $post_type, $post_types ) ) {
      return;
    }

  	// A list of taxonomy slugs to filter by
  	$taxonomies = array( 'guest-author' );

  	foreach ( $taxonomies as $taxonomy_slug ) {

  		// Retrieve taxonomy data
  		$taxonomy_obj = get_taxonomy( $taxonomy_slug );
  		$taxonomy_name = $taxonomy_obj->labels->name;

  		// Retrieve taxonomy terms
  		$terms = get_terms( $taxonomy_slug );

  		// Display filter HTML
  		echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
  		echo '<option value="">' . sprintf( esc_html__( 'All %s', 'bylines' ), $taxonomy_name ) . '</option>';
  		foreach ( $terms as $term ) {
  			printf(
  				'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
  				$term->slug,
  				( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
  				$term->name,
  				$term->count
  			);
  		}
  		echo '</select>';
  	}

  }


}
