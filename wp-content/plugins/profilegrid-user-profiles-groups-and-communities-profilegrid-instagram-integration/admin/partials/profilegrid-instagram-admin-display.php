<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$path =  plugin_dir_url(__FILE__);
$identifier = 'SETTINGS';
if(filter_input(INPUT_POST,'submit_settings'))
{
	$retrieved_nonce = filter_input(INPUT_POST,'_wpnonce');
	if (!wp_verify_nonce($retrieved_nonce, 'save_instagram_settings' ) ) die( 'Failed security check' );
	$exclude = array("_wpnonce","_wp_http_referer","submit_settings");
	if(!isset($_POST['pm_enable_instagram_integration'])) $_POST['pm_enable_instagram_integration'] = 0;
          
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
  <form name="pm_instagram_settings" id="pm_instagram_settings" method="post" onsubmit="return add_section_validation()">
    <!-----Dialogue Box Starts----->
    <div class="content">
      <div class="uimheader">
        <?php _e( 'Instagram Integration','profilegrid-instagram-integration' ); ?>
      </div>
     
      <div class="uimsubheader">
        <?php
		//Show subheadings or message or notice
		?>
      </div>
    
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable Instagram Integration','profilegrid-instagram-integration' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_instagram_integration" id="pm_enable_instagram_integration" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_instagram_integration','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_instagram_html')" />
          <label for="pm_enable_instagram_integration"></label>
        </div>
        <div class="uimnote"><?php _e("Enable Instagram integration with ProfileGrid Profiles.",'profilegrid-instagram-integration');?></div>
      </div>
        
        <div class="childfieldsrow" id="pm_instagram_html" style="<?php if($dbhandler->get_global_option_value('pm_enable_instagram_integration','0')=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
      
            <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Client ID', 'profilegrid-instagram-integration' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_instagram_integration',0)==1){echo 'pm_required';} ?>">
         <input name="pm_instagram_client_id" id="pm_instagram_client_id" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_instagram_client_id',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Paste your Client ID here after setting up your App on Instagram.','profilegrid-instagram-integration');?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Client Secret', 'profilegrid-instagram-integration' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_instagram_integration',0)==1){echo 'pm_required';} ?>">
         <input name="pm_instagram_client_secret" id="pm_instagram_client_secret" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_instagram_client_secret',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Paste your Client Secret here after setting up your App on Instagram.','profilegrid-instagram-integration');?></div>
      </div>
         
    
        </div>
 
      <div class="buttonarea"> 
          <a href="admin.php?page=pm_settings">
        <div class="cancel">&#8592; &nbsp;
          <?php _e('Cancel','profilegrid-instagram-integration');?>
        </div>
        </a>
        <?php wp_nonce_field('save_instagram_settings'); ?>
        <input type="submit" value="<?php _e('Save','profilegrid-instagram-integration');?>" name="submit_settings" id="submit_settings" />
        <div class="all_error_text" style="display:none;"></div>
      </div>
    </div>
   
  </form>
</div>