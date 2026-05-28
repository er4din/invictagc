function pg_update_social_connection(a)
 {  
//     jQuery('.pg-social-connect-wrap').hide();
    var key  = a.id;
    var val = 1;
    var conf = confirm(pm_social_connect_error_object.conf_disconnect);
    if(conf)
    {
        params = {action: 'pg_social_update_user_connections',key:key,value:val}
        jQuery.post(pm_ajax_object.ajax_url, params, function(response){
          // window.location.reload(true);
          if(response.data !== ''){
          window.location.href= response.data.redirect;
          }else{
            window.location.reload(true);
          }
       });
    }
    else
    {
        return;
    }
 }
 
function pg_social_login_redirect(url,gid,provider)
{
    var data = {action:'pg_save_temp_login_data',gid:gid,provider:provider};
    jQuery.post(pm_ajax_object.ajax_url, data, function (resp) 
    {
        if(resp)
        {
            window.location.href = url;
        }
    });
}
 
 jQuery(function(){
    var pgWidget_ParentWidth =  jQuery('.widget_profilegrid_social_login').width();
    if (pgWidget_ParentWidth < 145) {
         jQuery('.widget_profilegrid_social_login #pg_social_wrapper').addClass('pg-narrow-widget');
    }
});