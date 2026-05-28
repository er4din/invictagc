 
 jQuery(document).on('click','.pm-visitor_details_list ul li .page-numbers',function(event){
        event.preventDefault();
      // console.log($(this).text());
       var link = jQuery(this).attr("href");
       if(link !== undefined)
       {
           var newpagenum =link.split('pagenum=')[1];
            var data = {action: 'pm_display_visitor_list',pagenum:newpagenum};
        jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
            jQuery('#pg_visitor_details_tab').html(resp);
            
        }
       });
         }
    });
    
    jQuery(document).on('click','.pm-visitor_details_detailed_report ul li .page-numbers',function(event){
        event.preventDefault();
      // console.log($(this).text());
       var link = jQuery(this).attr("href");
       if(link !== undefined)
       {
           var newpagenum =link.split('pagenum=')[1];
           var visitor_id= jQuery("#pg_visitor_details_tab #visitor_id").val();
           var data = {action: 'pm_display_detailed_report',vid:visitor_id,pagenum:newpagenum};
        jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
            jQuery('#pg_visitor_details_tab').html(resp);
        }
       });
         }
    });
    
    
    
function pm_display_detailed_report(visitor_id,pagenum)
{
    var data = {action: 'pm_display_detailed_report', vid: visitor_id,pagenum:pagenum};
    jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
            jQuery('#pg_visitor_details_tab').html(resp);
            
        }
       });
       
 }
  function pm_display_visitor_list()
  {
     var data = {action: 'pm_display_visitor_list',pagenum:1};
    jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
            jQuery('#pg_visitor_details_tab').html(resp);
      }
       });
       
  }
  
  function pm_reset_profile_visitor_counter(id)
 {
   
    var x = confirm("Are you sure to delete/reset count of visitor? (This is impossible to revert.) ");
    if(x)
    {
        var data = {action: 'pm_reset_profile_visitor_counter',uid:id};
        jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
             var data = {action: 'pm_display_visitor_list',pagenum:1};
            jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
           jQuery('#pg_visitor_details_tab').html(resp);
       }
       });
        }
       }); 
    }
 }

 (function( $ ) {
	// 'use strict';

    jQuery(document).on('change', '#pm_Disable_Tracking', function () {
        
        var isChecked = $(this).prop("checked");
        // console.log(isChecked)
        var  $checked = 0;
        if(isChecked)
        {
            $checked= 1;
        }

        jQuery.ajax({
                url: pm_ajax_visitors_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'pm_update_tracking_status',
                    disable_tracking: $checked,
                    _ajax_nonce: pm_ajax_visitors_object.nonce
                },
                success: function (response) {
                    //console.log(pm_ajax_visitors_object.nonce);
                    // console.log(response)
                    if (response.success) {
                        show_toast('success', 'Tracking status updated.', false);
                    } else {
                        show_toast('error', 'Failed to update tracking status.', false);
                        jQuery('#pm_Disable_Tracking').prop('checked', false);
                    }
                },
                error: function (xhr, status, error) {
                    // Handle errors (e.g., network issues or server errors)
                    // console.error('AJAX Error: ', status, error);
                    show_toast('error', 'An error occurred while updating the tracking status. Please try again.', false);
                    jQuery('#pm_Disable_Tracking').prop('checked', false);
                }
        
            });

    });
    // $(".pmagic").prepend("<a>");
    var pgColorRgbValue = $('.pmagic, #primary.content-area .entry-content, .entry-content .pmagic').find('a').css('color');
    // console.log(pgColorRgbValue);
    /*-- Theme Color Global--*/ 
    var pgColorRgb = pgColorRgbValue;
    var avoid = "rgb";
    if( pgColorRgb ) {
        var pgrgbRemover = pgColorRgb.replace(avoid, '');
        var emColor = pgrgbRemover.substring(pgrgbRemover.indexOf('(') + 1, pgrgbRemover.indexOf(')'));
        $(':root').css('--pg-visitor-themeColor', emColor );
    }

})( jQuery );
 