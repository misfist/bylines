(function( $ ) {
	'use strict';

	/**
	 * In order to use wp.i18n, WordPress 5.0+ is required
	 */
	const { __, _x, _n, _nx } = wp.i18n;

	$(function() {

		_wpMediaViewsL10n.insertIntoPost = __( 'Insert Profile Picture', 'bylines' );

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

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
