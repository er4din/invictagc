function load_more_pg_custom_blogs_tab_content(uid,post_type,pagenum)
{
    jQuery('.pm-load-more-blogs').hide();
    jQuery('.pg-load-more-container .pm-loader').show();
    var page = parseInt(pagenum);
    var nextpage = page + 1;
    var data = {action: 'pm_load_pg_custom_blogs_tab_content',uid: uid,post_type:post_type,page:page};
    jQuery.post(pm_ajax_object.ajax_url, data, function (response) {
        
        if(response)
        {
            
            jQuery('.pg-load-more-container .pm-loader').hide();
            jQuery('#pg_next_blog_page'+post_type).val(nextpage);
            jQuery('#pg_blog_container_for'+post_type).append(response);
        }
    });

}