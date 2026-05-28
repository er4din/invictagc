(function( $ ) {
	'use strict';

})( jQuery );

jQuery(document).on('click','.pm-dashboard-visitor_details_list ul li .page-numbers',function(event){
        event.preventDefault();
      // console.log($(this).text());
       var link = jQuery(this).attr("href");
       if(link !== undefined)
       {
           var newpagenum =link.split('pagenum=')[1];
            var user_id= jQuery("#visitor_details #profile_id").val();
            var data = {action: 'profilegrid_dashboard_profile_visitor_list',id:user_id,pagenum:newpagenum};
        jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
             jQuery('#visitor_details').html(resp);
           // alert(resp);
            
        }
       });
         }
    });

jQuery(document).on('click','.pm-dashboard-visitor_details_detailed_report ul li .page-numbers',function(event){
        event.preventDefault();
         var link = jQuery(this).attr("href");
       if(link !== undefined)
       {
           var newpagenum =link.split('pagenum=')[1];
           var visitor_id= jQuery("#visitor_details #visitor_id").val();
            var user_id= jQuery("#visitor_details #profile_id").val();
          //  alert(user_id);
           var data = {action: 'pm_dashboard_display_detailed_report',id:user_id,vid:visitor_id,pagenum:newpagenum};
        jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
            jQuery('#visitor_details').html(resp);
        }
       });
         }
    });
    

function pm_dashboard_display_detailed_report(id,visitor_id,pagenum)
{
    //alert("asdasd");
     var data = {action: 'pm_dashboard_display_detailed_report',id: id, vid: visitor_id,pagenum:pagenum};
    jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
           jQuery('#visitor_details').html(resp);
         //  alert(resp);
            
        }
       });
       
 }
 
 function profilegrid_back_to_visitor_details(id)
 {
    var data = {action: 'profilegrid_dashboard_profile_visitor_list',id:id,pagenum:1};
    jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
           jQuery('#visitor_details').html(resp);
         //  alert(resp);
            
        }
       });
 }
 
 function pm_reset_visitor_counter(id)
 {
   
    var x = confirm("Are you sure to delete/reset count for this user? (This is impossible to revert.)");
    if(x)
    {
        var data = {action: 'pm_reset_visitor_counter',uid:id};
        jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
             var data = {action: 'profilegrid_dashboard_profile_visitor_list',id:id,pagenum:1};
            jQuery.post(pm_ajax_visitors_object.ajax_url, data, function (resp) {
        if (resp)
        {
           jQuery('#visitor_details').html(resp);
         //  alert(resp);
       }
       });
        }
       }); 
    }
 }

 jQuery(document).on('ready', function(){
  jQuery("#pm_select_group").select2({
    tags: true,
    placeholder: "Select groups"
  });
});