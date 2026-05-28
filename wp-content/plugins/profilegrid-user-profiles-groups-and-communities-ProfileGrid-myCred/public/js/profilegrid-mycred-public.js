(function( $ ) {
	'use strict';
        
    var pmDomColor = $(".pmagic").find("a").css('color');
    $(".mycred-rank-progress .pg-progress").css('background-color', pmDomColor);   
    
    
    
    $(document).on('click','#pg-mycred-log-table .mycred-history-wrapper ul.pagination li a',function(event){
        event.preventDefault();
      // console.log($(this).text());
       var link = $(this).attr("href");
       if(link !== undefined)
       {
           var newpagenum =link.split('page=')[1];
           pm_get_mycred_log(newpagenum);
       }
    });
    
    
        
})( jQuery );


function pm_get_mycred_log(page)
{
    var uid = jQuery('#pg_mycred_uid').val();
    var type = jQuery('#pg_mycred_type').val();
    var data = {action: 'pm_load_mycred_log',uid:uid,type:type,page:page};
    jQuery.get(pm_ajax_object.ajax_url, data, function (response) {
       // alert(response);
        jQuery('#pg-mycred-log-table').html(response);
       
    });
}