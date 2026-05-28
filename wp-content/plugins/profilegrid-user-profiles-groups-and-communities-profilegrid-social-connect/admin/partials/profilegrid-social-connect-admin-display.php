<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$path =  plugin_dir_url(__FILE__);
$groups =  $dbhandler->get_all_result('GROUPS', array('id', 'group_name'));
if(!is_array($groups))
{
    $groups = array();
}
$current_default_group = $dbhandler->get_global_option_value('pm_social_default_group', '0');
$identifier = 'SETTINGS';
if(filter_input(INPUT_POST,'submit_settings'))
{
	$retrieved_nonce = filter_input(INPUT_POST,'_wpnonce');
	if (!wp_verify_nonce($retrieved_nonce, 'save_social_connect_settings' ) ) die( 'Failed security check' );
	$exclude = array("_wpnonce","_wp_http_referer","submit_settings");
	  if(!isset($_POST['pm_enable_social_account_tab'])) $_POST['pm_enable_social_account_tab'] = 0;
          if(!isset($_POST['pm_enable_social_on_registration'])) $_POST['pm_enable_social_on_registration'] = 0;
          if(!isset($_POST['pm_enable_social_on_login'])) $_POST['pm_enable_social_on_login'] = 0;
            if(!isset($_POST['pm_social_default_group'])) $_POST['pm_social_default_group'] = 0;
          if(!isset($_POST['pm_enable_autofill_connect'])) $_POST['pm_enable_autofill_connect'] = 0;
          if(!isset($_POST['pm_enable_facebook_connect'])) $_POST['pm_enable_facebook_connect'] = 0;
          if(!isset($_POST['pm_enable_twitter_connect'])) $_POST['pm_enable_twitter_connect'] = 0;
          if(!isset($_POST['pm_enable_google_connect'])) $_POST['pm_enable_google_connect'] = 0;
          if(!isset($_POST['pm_enable_linkedin_connect'])) $_POST['pm_enable_linkedin_connect'] = 0;
         
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
    <form name="pm_social_connect_settings" id="pm_social_connect_settings" method="post" onsubmit="return add_social_section_validation()">
    <!-----Dialogue Box Starts----->
    <div class="content">
      <div class="uimheader">
        <?php _e( 'Social Login','profilegrid-social-connect' ); ?>
      </div>
     
      <div class="uimsubheader">
        <?php
		//Show subheadings or message or notice
		?>
      </div>
      <!-- Popup notification for "Copied to Clipboard" -->
<div id="copyNotification" class="copy-notification">
    <?php _e('Copied to clipboard!', 'profilegrid-social-connect'); ?>
</div>
    <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Allow Registering through Social Networks','profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_social_on_registration" id="pm_enable_social_on_registration" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_social_on_registration','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_enable_social_on_registration"></label>
        </div>
        <div class="uimnote"><?php _e("Social buttons will appear above registration forms, allowing users to complete registration by authenticating through their social network accounts.",'profilegrid-social-connect');?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Allow Login through Social Networks','profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_social_on_login" id="pm_enable_social_on_login" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_social_on_login','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_enable_social_on_login"></label>
        </div>
        <div class="uimnote"><?php _e("Social Login buttons will appear above login box, allowing users to login to their ProfileGrid profiles using their connected social network accounts.",'profilegrid-social-connect');?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Display Social Tab','profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_social_account_tab" id="pm_enable_social_account_tab" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_social_account_tab','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_enable_social_account_tab"></label>
        </div>
        <div class="uimnote"><?php _e("A new settings tab will appear in Settings section of user profiles, allowing users to manage social accounts connected to their user profile.",'profilegrid-social-connect');?></div>
      </div>
             
        <div class="uimrow">
        <div class="uimfield">
          <?php _e('Default Group','profilegrid-social-connect');?>
        </div>
        <div class="uiminput">
          <select name="pm_social_default_group" id="pm_social_default_group">
          <option value="0"><?php esc_html_e('Select a group', 'profilegrid-social-connect'); ?></option>
          <?php
            if ( ! empty( $groups ) ) {
                foreach($groups as $group) {
                    ?>
            <option value="<?php echo esc_attr($group->id);?>" <?php selected($current_default_group, $group->id);?>><?php echo esc_html($group->group_name); ?></option>
            <?php
                }
            }
            ?>        
  	</select>
          <div class="errortext"></div>
          <?php if ( empty( $groups ) ) : ?>
            <div class="pm-form-note"><?php esc_html_e( 'No ProfileGrid groups are available yet. Create at least one group before selecting a default social-login group.', 'profilegrid-social-connect' ); ?></div>
          <?php endif; ?>
        </div>
        <div class="uimnote"><?php _e('When a visitor tries to login using a social account, and is not yet registered on the site, he/ she will be asked first to fill registration form of this group.','profilegrid-social-connect');?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable Autofilling','profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_autofill_connect" id="pm_enable_autofill_connect" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_autofill_connect','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_enable_autofill_connect"></label>
        </div>
        <div class="uimnote"><?php _e("Automatically fill values from social account to corresponding fields in registration form after successful authentication.",'profilegrid-social-connect');?></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable Facebook','profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
            <input name="pm_enable_facebook_connect" id="pm_enable_facebook_connect" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_facebook_connect','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onclick="pm_show_hide(this,'pm_facebook_connect_html')" />
          <label for="pm_enable_facebook_connect"></label>
        </div>
        <div class="uimnote"><?php _e("Check this box to allow new users to register or current users to connect their profiles with their Facebook account. Once enabled, additional options will appear to configure the required Facebook API keys in the settings.",'profilegrid-social-connect');?></div>
      </div>
        
    <div  class="childfieldsrow" id="pm_facebook_connect_html" style=" <?php if($dbhandler->get_global_option_value('pm_enable_facebook_connect','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">      
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Facebook App ID', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_facebook_connect',0)==1){echo 'pm_required';} ?>">
         <input name="pm_facebook_app_id" id="pm_facebook_app_id" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_facebook_app_id',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Enter the App ID for your Facebook application. You can generate this ID by creating an app on the','profilegrid-social-connect');?><a target="blank" class="rm_help_link" href="https://developers.facebook.com/"><?php esc_html_e( 'Facebook Developer Platform.', 'profilegrid-social-connect' ); ?></a></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Facebook App Secret', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_facebook_connect',0)==1){echo 'pm_required';} ?>">
         <input name="pm_facebook_app_secret" id="pm_facebook_app_secret" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_facebook_app_secret',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Enter the App Secret for your Facebook application. You can find this key on the','profilegrid-social-connect');?><a target="blank" class="rm_help_link" href="https://developers.facebook.com/"><?php esc_html_e( 'Facebook Developer Platform.', 'profilegrid-social-connect' ); ?></a></div>
      </div>

      <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Callback URL', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
        <label for="pm_callback_registration" class="url-description" style="font-weight: bold;">
            <?php _e('Callback for Registration Page:','profilegrid-social-connect');?>
        </label><br>
        
        <!-- First URL displayed as plain text with copy button -->
        <span id="pm_callback_registration" class="url-text"><?php echo esc_html($pmrequests->profile_magic_get_frontend_url('pm_registration_page', '')) ?></span>
        <a type="button" class="pm-copy-btn" data-target="pm_callback_registration"><?php _e('Copy', 'profilegrid-social-connect'); ?></a><br><br>

        <!-- Second URL displayed as plain text with copy button -->
        <label for="pm_callback_profile" class="url-description" style="font-weight: bold;">
            <?php _e('Callback for User Profile Page:','profilegrid-social-connect');?>
        </label><br>
        <span id="pm_callback_profile" class="url-text"><?php echo esc_html($pmrequests->profile_magic_get_frontend_url('pm_user_profile_page', '')) ?></span>
        <a type="button" class="pm-copy-btn" data-target="pm_callback_profile"><?php _e('Copy', 'profilegrid-social-connect'); ?></a>
    </div>
        <div class="uimnote"><?php _e('This is the URL required by Facebook to redirect users back to your website after authentication. Copy and paste this URL into the Callback URL(Redirect URI) field in your Facebook account settings to ensure proper functionality. This field is read-only and auto-generated based on your website configuration.','profilegrid-social-connect');?></div>
      </div>
    </div>
    
        
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable X','profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
            <input name="pm_enable_twitter_connect" id="pm_enable_twitter_connect" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_twitter_connect','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onclick="pm_show_hide(this,'pm_twitter_connect_html')" />
          <label for="pm_enable_twitter_connect"></label>
        </div>
        <div class="uimnote"><?php _e("Check this box to allow new users to register or current users to connect their profiles with their X account. Once enabled, additional options will appear to configure the required X API keys in the settings.",'profilegrid-social-connect');?></div>
      </div>
        
    <div  class="childfieldsrow" id="pm_twitter_connect_html" style=" <?php if($dbhandler->get_global_option_value('pm_enable_twitter_connect','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">      
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'X API Key', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_twitter_connect',0)==1){echo 'pm_required';} ?>">
         <input name="pm_twitter_consumer_key" id="pm_twitter_consumer_key" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_twitter_consumer_key',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Enter the API Key for your X application. You can generate this key by creating an app on the','profilegrid-social-connect');?><a target="blank" class="rm_help_link" href="https://developer.x.com/"><?php esc_html_e( 'X Developer Portal.', 'profilegrid-social-connect' ); ?></a></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield <?php if($dbhandler->get_global_option_value('pm_enable_twitter_connect',0)==1){echo 'pm_required';} ?>">
          <?php _e( 'X API Secret', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_twitter_connect',0)==1){echo 'pm_required';} ?>">
         <input name="pm_twitter_consumer_secret" id="pm_twitter_consumer_secret" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_twitter_consumer_secret',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Enter the API Secret Key for your X application. You can find this key on the','profilegrid-social-connect');?><a target="blank" class="rm_help_link" href="https://developer.x.com/"><?php esc_html_e( 'X Developer Portal.', 'profilegrid-social-connect' ); ?></a></div>
      </div>
      <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Callback URL', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
        <label for="pm_callback_registration" class="url-description" style="font-weight: bold;">
            <?php _e('Callback for Registration Page:','profilegrid-social-connect');?>
        </label><br>
        
        <!-- First URL displayed as plain text with copy button -->
        <span id="pm_callback_registration" class="url-text"><?php echo esc_html($pmrequests->profile_magic_get_frontend_url('pm_registration_page', '')) ?></span>
        <a type="button" class="pm-copy-btn" data-target="pm_callback_registration"><?php _e('Copy', 'profilegrid-social-connect'); ?></a><br><br>

        <!-- Second URL displayed as plain text with copy button -->
        <label for="pm_callback_profile" class="url-description" style="font-weight: bold;">
            <?php _e('Callback for User Profile Page:','profilegrid-social-connect');?>
        </label><br>
        <span id="pm_callback_profile" class="url-text"><?php echo esc_html($pmrequests->profile_magic_get_frontend_url('pm_user_profile_page', '')) ?></span>
        <a type="button" class="pm-copy-btn" data-target="pm_callback_profile"><?php _e('Copy', 'profilegrid-social-connect'); ?></a>
    </div>
        <div class="uimnote"><?php _e('This is the URL required by X (formerly Twitter) to redirect users back to your website after authentication. Copy and paste this URL into the Callback URL(Redirect URI) field in your X account settings to ensure proper functionality. This field is read-only and auto-generated based on your website configuration.','profilegrid-social-connect');?></div>
      </div>
    </div>
        
         
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable Google','profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
            <input name="pm_enable_google_connect" id="pm_enable_google_connect" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_google_connect','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onclick="pm_show_hide(this,'pm_google_connect_html')" />
          <label for="pm_enable_google_connect"></label>
        </div>
        <div class="uimnote"><?php _e("Check this box to allow new users to register or current users to connect their profiles with their Google account. Once enabled, additional options will appear to configure the required Google API keys in the settings.",'profilegrid-social-connect');?></div>
      </div>
        
    <div  class="childfieldsrow" id="pm_google_connect_html" style=" <?php if($dbhandler->get_global_option_value('pm_enable_google_connect','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">      
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Google Client ID', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_google_connect',0)==1){echo 'pm_required';} ?>">
         <input name="pm_google_client_id" id="pm_google_client_id" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_google_client_id',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Enter the Client ID for your Google application. You can generate this ID by creating credentials on the','profilegrid-social-connect');?><a target="blank" class="rm_help_link" href="https://console.cloud.google.com/"><?php esc_html_e( 'Google Cloud Console.', 'profilegrid-social-connect' ); ?></a></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Google Client Secret', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_google_connect',0)==1){echo 'pm_required';} ?>">
         <input name="pm_google_client_secret" id="pm_google_client_secret" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_google_client_secret',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Enter the Client Secret for your Google application. You can find this key on the','profilegrid-social-connect');?><a target="blank" class="rm_help_link" href="https://console.cloud.google.com/"><?php esc_html_e( 'Google Cloud Console.', 'profilegrid-social-connect' ); ?></a></div>
      </div>

      <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Callback URL', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
        <label for="pm_callback_registration" class="url-description" style="font-weight: bold;">
            <?php _e('Callback for Registration Page:','profilegrid-social-connect');?>
        </label><br>
        
        <!-- First URL displayed as plain text with copy button -->
        <span id="pm_callback_registration" class="url-text"><?php echo esc_html($pmrequests->profile_magic_get_frontend_url('pm_registration_page', '')) ?></span>
        <a type="button" class="pm-copy-btn" data-target="pm_callback_registration"><?php _e('Copy', 'profilegrid-social-connect'); ?></a><br><br>

        <!-- Second URL displayed as plain text with copy button -->
        <label for="pm_callback_profile" class="url-description" style="font-weight: bold;">
            <?php _e('Callback for User Profile Page:','profilegrid-social-connect');?>
        </label><br>
        <span id="pm_callback_profile" class="url-text"><?php echo esc_html($pmrequests->profile_magic_get_frontend_url('pm_user_profile_page', '')) ?></span>
        <a type="button" class="pm-copy-btn" data-target="pm_callback_profile"><?php _e('Copy', 'profilegrid-social-connect'); ?></a>
    </div>
        <div class="uimnote"><?php _e('This is the URL required by Google to redirect users back to your website after authentication. Copy and paste this URL into the Callback URL(Redirect URI) field in your Google account settings to ensure proper functionality. This field is read-only and auto-generated based on your website configuration.','profilegrid-social-connect');?></div>
      </div>
    </div>
        
         
    <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable LinkedIn','profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
            <input name="pm_enable_linkedin_connect" id="pm_enable_linkedin_connect" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_linkedin_connect','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onclick="pm_show_hide(this,'pm_linkedin_connect_html')" />
          <label for="pm_enable_linkedin_connect"></label>
        </div>
        <div class="uimnote"><?php _e("Check this box to allow new users to register or current users to connect their profiles with their LinkedIn account. Once enabled, additional options will appear to configure the required LinkedIn API keys in the settings.",'profilegrid-social-connect');?></div>
    </div>
        
    <div  class="childfieldsrow" id="pm_linkedin_connect_html" style=" <?php if($dbhandler->get_global_option_value('pm_enable_linkedin_connect','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">      
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'LinkedIn Client ID', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_linkedin_connect',0)==1){echo 'pm_required';} ?>">
         <input name="pm_linkedin_api_key" id="pm_linkedin_api_key" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_linkedin_api_key',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Enter the Client ID for your LinkedIn application. You can generate this ID by creating an app on the','profilegrid-social-connect');?><a target="blank" class="rm_help_link" href="https://developer.linkedin.com/"><?php esc_html_e( 'LinkedIn Developer Platform.', 'profilegrid-social-connect' ); ?></a></div>
      </div>
        
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'LinkedIn Client Secret', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput <?php if($dbhandler->get_global_option_value('pm_enable_linkedin_connect',0)==1){echo 'pm_required';} ?>">
         <input name="pm_linkedin_api_secret" id="pm_linkedin_api_secret" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_linkedin_api_secret',''); ?>" />
        <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e('Enter the Client Secret for your LinkedIn application. You can find this key on the','profilegrid-social-connect');?><a target="blank" class="rm_help_link" href="https://developer.linkedin.com/"><?php esc_html_e( 'LinkedIn Developer Platform.', 'profilegrid-social-connect' ); ?></a></div>
      </div>

      <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Callback URL', 'profilegrid-social-connect' ); ?>
        </div>
        <div class="uiminput">
        <label for="pm_callback_registration" class="url-description" style="font-weight: bold;">
            <?php _e('Callback for Registration Page:','profilegrid-social-connect');?>
        </label><br>
        
        <!-- First URL displayed as plain text with copy button -->
        <span id="pm_callback_registration" class="url-text"><?php echo esc_html($pmrequests->profile_magic_get_frontend_url('pm_registration_page', '')) ?></span>
        <a type="button" class="pm-copy-btn" data-target="pm_callback_registration"><?php _e('Copy', 'profilegrid-social-connect'); ?></a><br><br>

        <!-- Second URL displayed as plain text with copy button -->
        <label for="pm_callback_profile" class="url-description" style="font-weight: bold;">
            <?php _e('Callback for User Profile Page:','profilegrid-social-connect');?>
        </label><br>
        <span id="pm_callback_profile" class="url-text"><?php echo esc_html($pmrequests->profile_magic_get_frontend_url('pm_user_profile_page', '')) ?></span>
        <a type="button" class="pm-copy-btn" data-target="pm_callback_profile"><?php _e('Copy', 'profilegrid-social-connect'); ?></a>
    </div>
        <div class="uimnote"><?php _e('This is the URL required by LinkedIn to redirect users back to your website after authentication. Copy and paste this URL into the Callback URL(Redirect URI) field in your LinkedIn account settings to ensure proper functionality. This field is read-only and auto-generated based on your website configuration.','profilegrid-social-connect');?></div>
      </div>
    </div>
    <div class="buttonarea"> 
          <a href="admin.php?page=pm_settings">
        <div class="cancel">&#8592; &nbsp;
          <?php _e('Cancel','profilegrid-social-connect');?>
        </div>
        </a>
        <?php wp_nonce_field('save_social_connect_settings'); ?>
        <input type="submit" value="<?php _e('Save','profilegrid-social-connect');?>" name="submit_settings" id="submit_settings" />
        <div class="all_error_text" style="display:none;"></div>
      </div>
    </div>
   
  </form>
</div>
