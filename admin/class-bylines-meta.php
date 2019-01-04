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
     if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != $this->taxonomy ) {
       return;
     }
     wp_enqueue_media();
   }

  public function add_script() {
     if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != $this->taxonomy ) {
       return;
     } ?>
     <script> jQuery(document).ready( function($) {
       _wpMediaViewsL10n.insertIntoPost = '<?php _e( "Insert", "bylines" ); ?>';
       function ct_media_upload(button_class) {
         var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;
         $('body').on('click', button_class, function(e) {
           var button_id = '#'+$(this).attr('id');
           var send_attachment_bkp = wp.media.editor.send.attachment;
           var button = $(button_id);
           _custom_media = true;
           wp.media.editor.send.attachment = function(props, attachment){
             if( _custom_media ) {
               $( '#author_meta_image' ).val(attachment.id);
               $( '#term-image-wrapper' ).html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
               $( '#term-image-wrapper .custom_media_image' ).attr( 'src',attachment.url ).css( 'display','block' );
             } else {
               return _orig_send_attachment.apply( button_id, [props, attachment] );
             }
           }
           wp.media.editor.open(button); return false;
         });
       }

       ct_media_upload('.author-tax_media_button.button');
       $('body').on('click','.author-tax_media_remove',function(){
         $('#author_meta_image').val('');
         $('#term-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
       });
       // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
       $(document).ajaxComplete(function(event, xhr, settings) {
         var queryStringArr = settings.data.split('&');
         if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
           var xml = xhr.responseXML;
           $response = $(xml).find('term_id').text();
           if($response!=""){
             // Clear the thumb image
             $('#term-image-wrapper').html('');
           }
          }
        });
      });
    </script>
   <?php
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
      array(
        'key'     => 'image',
        'label'   => __( 'Profile Picture', 'bylines' ),
        'type'    => 'image',
      ),
    );
    return apply_filters( 'bylines_set_meta_fields', $fields );
  }

  function modify_term_labels() {
    add_filter( 'gettext', array( $this, 'term_label_gettext' ), 10, 2 );
  }

  function term_label_gettext( $translation, $original ) {
    global $current_screen;

    if( 'edit-guest-author' === $current_screen->id ) {
      if ( 'Description' == $original ) {
          return __( 'Biographical Info', 'byline' );
      }
    }

    return $translation;
  }


}

 new Bylines_Taxonomy_Meta;
