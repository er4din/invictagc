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
function get_button_id(id)
{
    //alert(id.value);
    if(id.value != 10)
    {
        jQuery("#tabs_privacy_user_roles_html").slideUp(500);
        
    }
    if(id.value != 9)
    {
        jQuery("#tabs_privacy_groups_html").slideUp(500);
        
    }
    if(id.value != 8 )
    {
        jQuery("#tabs_privacy_users_html").slideUp(500);
    }
    
    
}