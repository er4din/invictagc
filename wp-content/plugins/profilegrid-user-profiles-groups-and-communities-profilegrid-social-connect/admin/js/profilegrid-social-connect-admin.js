(function( $ ) {
	'use strict';

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
	 * $( window ).on( 'load', function() {
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

	jQuery(function($) {
		// Function to copy text to clipboard and show the notification
		$('.pm-copy-btn').on('click', function() {
			var targetId = $(this).data('target'); // Get target element ID from data attribute
			var targetText = $('#' + targetId).text(); // Get the text content from the target element
	
			// Use the Clipboard API to copy text to clipboard
			if (navigator.clipboard) {
				navigator.clipboard.writeText(targetText).then(function() {
					// Show the notification and hide it after 2 seconds
					$('#copyNotification').fadeIn().delay(2000).fadeOut();
				}).catch(function(err) {
					console.error('Error copying text: ', err); // Log error if copying fails
				});
			} else {
				// Fallback for browsers that do not support Clipboard API
				console.warn('Clipboard API is not supported in this browser');
			}
		});
	});

})( jQuery );

function add_social_section_validation()
{
    
	jQuery('.errortext').html('');
	jQuery('.errortext').hide();
	jQuery('.all_error_text').html('');
	jQuery('input').removeClass('warning');
	
	jQuery('.pm_required').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('input').val();
		var value2 = jQuery.trim(value);
		if (value2== "") {
			jQuery(this).children('.errortext').html(pm_error_object.required_field);
			jQuery(this).children('input').addClass('warning');
			jQuery(this).children('.errortext').show();
		}
		
	});
	var b = '';
		b = jQuery('.errortext').each(function () {
			var a = jQuery(this).html();
			b = a + b;
			jQuery('.all_error_text').html(b);
		});
		var error = jQuery('.all_error_text').html();
		if (error == '') {
			return true;
		} else {
			return false;
		}
                
}
