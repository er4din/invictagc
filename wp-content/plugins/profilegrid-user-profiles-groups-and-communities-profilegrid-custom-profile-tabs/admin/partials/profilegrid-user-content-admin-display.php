<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$path =  plugin_dir_url(__FILE__);
$identifier = 'SETTINGS';
if(filter_input(INPUT_POST,'submit_settings'))
{
	$retrieved_nonce = filter_input(INPUT_POST,'_wpnonce');
	if (!wp_verify_nonce($retrieved_nonce, 'save_pm_user_content_settings' ) ) die( 'Failed security check' );
	$exclude = array("_wpnonce","_wp_http_referer","submit_settings");
	if(!isset($_POST['pm_enable_custom_post_tabs'])) $_POST['pm_enable_custom_post_tabs'] = 0;
        
        $post = $pmrequests->sanitize_request($_POST,$identifier,$exclude);
	if($post!=false)
	{
		foreach($post as $key=>$value)
		{
			$dbhandler->update_global_option_value($key,$value);
		}
	}
	
	wp_redirect('admin.php?page=pm_settings');exit;
}
?>

<div class="uimagic">
  <form name="pm_user_content_settings" id="pm_user_content_settings" method="post">
    <!-----Dialogue Box Starts----->
    <div class="content">
      <div class="uimheader">
        <?php _e( 'Custom User Profile Tabs','profilegrid-custom-profile-tabs' ); ?>
      </div>
     
      <div class="uimsubheader">
        <?php
		//Show subheadings or message or notice
		?>
      </div>
    
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Turn on Custom User Profile Tabs','profilegrid-custom-profile-tabs' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_custom_post_tabs" id="pm_enable_custom_post_tabs" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_custom_post_tabs','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_enable_custom_post_tabs"></label>
        </div>
        <div class="uimnote"><?php _e("Enable to display tabs with custom content inside user profiles.",'profilegrid-custom-profile-tabs');?></div>
      </div>
        
    
        
 
      <div class="buttonarea"> 
          <a href="admin.php?page=pm_settings">
        <div class="cancel">&#8592; &nbsp;
          <?php _e('Cancel','profilegrid-custom-profile-tabs');?>
        </div>
        </a>
        <?php wp_nonce_field('save_pm_user_content_settings'); ?>
        <input type="submit" value="<?php _e('Save','profilegrid-custom-profile-tabs');?>" name="submit_settings" id="submit_settings" />
        <div class="all_error_text" style="display:none;"></div>
      </div>
    </div>
   
  </form>
</div>