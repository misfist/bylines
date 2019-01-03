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
 	public function __construct( $taxonomy = null ) {
 		if ( is_admin() ) {

 			add_action( $this->taxonomy . '_add_form_fields',  array( $this, 'create_screen_fields'), 10, 1 );
 			add_action( $this->taxonomy . '_edit_form_fields', array( $this, 'edit_screen_fields' ),  10, 2 );

 			add_action( 'created_' . $this->taxonomy, array( $this, 'save_data' ), 10, 1 );
 			add_action( 'edited_' . $this->taxonomy,  array( $this, 'save_data' ), 10, 1 );

      // add_action( 'admin_head-edit-tags.php', array( $this, 'register_filter' ) );
      //
      // edit-tags.php?taxonomy=guest-author
      add_action( 'admin_head-edit-tags.php', array( $this, 'setup_user_edit' ) );
      add_action( 'admin_head-term.php', array( $this, 'setup_user_edit' ) );
      //
      // add_filter( 'gettext', array( $this, 'wpse6096_gettext' ), 10, 2 );
      //
      // add_action( 'admin_head-edit-tags.php?taxonomy=guest-author', function() {
      //   global $hook_suffix;
      // 	echo 'I want to write a new post and I will be in ' , $hook_suffix;
      // } );


 		}

    $this->meta_label_args();

    if( $taxonomy ) {
      $this->taxonomy = $taxonomy;
    }
 	}


  function setup_user_edit() {
    add_filter( 'gettext', array( $this, 'wpse6096_gettext' ), 10, 2 );
  }

  function wpse6096_gettext( $translation, $original ) {
    global $current_screen;

    if( 'edit-guest-author' === $current_screen->id ) {
      if ( 'Description' == $original ) {
          return __( 'Biographical Info', 'byline' );
      }
    }

    // switch ( $current_screen->id ) {
    //     case 'edit-guest-author':
    //         // WE ARE AT /wp-admin/edit-tags.php?taxonomy=category
    //         // OR AT /wp-admin/edit-tags.php?action=edit&taxonomy=category&tag_ID=1&post_type=post
    //         break;
    //     case 'edit-post_tag':
    //         // WE ARE AT /wp-admin/edit-tags.php?taxonomy=post_tag
    //         // OR AT /wp-admin/edit-tags.php?action=edit&taxonomy=post_tag&tag_ID=3&post_type=post
    //         break;
    // }
    //

      return $translation;
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
      $author_meta_{$field['key']} = '';

      echo '<div class="form-field term-author_meta_' . $field['key'] . '-wrap">';
   		echo '	<label for="author_meta_' . $field['key'] . '">' . __( 'First Name', 'bylines' ) . '</label>';
   		echo '	<input type="text" id="author_meta_' . $field['key'] . '" name="author_meta_' . $field['key'] . '" placeholder="' . esc_attr__( $field['label'] ) . '" value="' . esc_attr( $author_meta_first_name ) . '">';
   		echo '</div>';
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
      foreach( $fields as $field ) {
        $author_meta_{$field['key']} = get_term_meta( $term->term_id, "author_meta_{$field['key']}", true );
        if( empty( $author_meta_{$field['key']} ) ) $author_meta_{$field['key']} = '';

        echo '<tr class="form-field term-author_meta_' . $field['key'] . '-wrap">';
        echo '<th scope="row">';
        echo '	<label for="author_meta_' . $field['key'] . '">' . esc_attr__( $field['label'] ) . '</label>';
        echo '</th>';
        echo '<td>';
        echo '	<input type="text" id="author_meta_' . $field['key'] . '" name="author_meta_' . $field['key'] . '" placeholder="' . esc_attr__( $field['label'] ) . '" value="' . esc_attr( $author_meta_{$field['key']} ) . '">';
        echo '</td>';
        echo '</tr>';
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
        update_term_meta( $term_id, "author_meta_{$field['key']}", $author_meta_new_{$field['key']} );
      }
    }

 		$author_meta_new_first_name = isset( $_POST[ 'author_meta_first_name' ] ) ? sanitize_text_field( $_POST[ 'author_meta_first_name' ] ) : '';
 		$author_meta_new_last_name = isset( $_POST[ 'author_meta_last_name' ] ) ? sanitize_text_field( $_POST[ 'author_meta_last_name' ] ) : '';
 		$author_meta_new_nickname = isset( $_POST[ 'author_meta_nickname' ] ) ? sanitize_text_field( $_POST[ 'author_meta_nickname' ] ) : '';

 		// update_term_meta( $term_id, 'author_meta_first_name', $author_meta_new_first_name );
 		// update_term_meta( $term_id, 'author_meta_last_name', $author_meta_new_last_name );
 		// update_term_meta( $term_id, 'author_meta_nickname', $author_meta_new_nickname );
 		// update_term_meta( $term_id, 'author_meta_biography', $author_meta_new_biography );
 	}

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
    );
    return apply_filters( 'bylines_set_meta_fields', $fields );
  }

  /**
   *  Meta Labels
   *
   * @since 1.0.0
   * @return array $this->taxonomy_meta_labels
   */
  public function meta_label_args() {
    $args = array (
      'context'       => array (
        'Taxonomy Description',
        'Taxonomy Name'
      ),
      'replacements'  => array (
        'Description'   => __( 'Biography', 'bylines' ),
        'Name'          => __( 'Display Name', 'bylines' )
      ),
      'taxonomy'        => $this->taxonomy
    );

    $this->taxonomy_meta_labels = apply_filters( 'bylines_author_labels', $args );
  }

  /**
   * Register the Filter
   * @return void
   */
  public function register_filter() {
    add_filter( 'gettext_with_context', array( $this, 'translate' ), 10, 4 );
  }

  /**
   * Translation
   *
   * @param  string $translated
   * @param  string $original
   * @param  array $context
   * @param  string $domain
   * @return string
   */
  function translate( $translated, $original, $context, $domain ) {
    $args = $this->taxonomy_meta_labels;

    if( $args['taxonomy'] !== $_GET['taxonomy'] ) {
      return $translated;
    }

    if ( 'default' !== $domain ) {
      return $translated;
    }

    if( !in_array( $context, $args['context'] ) ) {
      return $translated;
    }

    return strtr( $original, $args['replacements'] );
  }

}

 new Bylines_Taxonomy_Meta;
