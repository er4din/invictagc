<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$path =  plugin_dir_url(__FILE__);
$identifier = 'SETTINGS';
if(filter_input(INPUT_POST,'submit_settings'))
{
	$retrieved_nonce = filter_input(INPUT_POST,'_wpnonce');
	if (!wp_verify_nonce($retrieved_nonce, 'save_display_name_settings' ) ) die( 'Failed security check' );
	$exclude = array("_wpnonce","_wp_http_referer","submit_settings");
	  if(!isset($_POST['pm_enable_display_name'])) $_POST['pm_enable_display_name'] = 0;
          if(!isset($_POST['pm_enable_prefix'])) $_POST['pm_enable_prefix'] = 0;
          if(!isset($_POST['pm_enable_postfix'])) $_POST['pm_enable_postfix'] = 0;
          if(!isset($_POST['pm_enable_display_name_style_prefix_suffix'])) $_POST['pm_enable_display_name_style_prefix_suffix'] = 0;
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
  <form name="pm_display_settings" id="pm_display_settings" method="post">
    <!-----Dialogue Box Starts----->
    <div class="content">
      <div class="uimheader">
        <?php _e( 'User Display Name Settings','profilegrid-user-display-name' ); ?>
      </div>
     
      <div class="uimsubheader">
        <?php
		//Show subheadings or message or notice
		?>
      </div>
    
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable Display Name','profilegrid-user-display-name' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_display_name" id="pm_enable_display_name" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_display_name','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_display_name_html')" />
          <label for="pm_enable_display_name"></label>
        </div>
        <div class="uimnote"><?php _e("Turn on customized display names for user profiles.",'profilegrid-user-display-name');?></div>
      </div>
        
      <div class="childfieldsrow" id="pm_display_name_html" style="<?php if($dbhandler->get_global_option_value('pm_enable_display_name','0')=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
      
      
   	 <div class="uimrow">
        <div class="uimfield">
          <?php _e('Name Pattern','profilegrid-user-display-name');?>
        </div>
        <div class="uiminput">
          <select name="pm_display_name_pattern" id="pm_display_name_pattern">
              <option value="1" <?php selected($dbhandler->get_global_option_value('pm_display_name_pattern',1), 1 ); ?>><?php _e('FirstName LastName','profilegrid-user-display-name');?></option>
              <option value="2" <?php selected($dbhandler->get_global_option_value('pm_display_name_pattern',1), 2 ); ?>><?php _e('LastName, FirstName','profilegrid-user-display-name');?></option>
              <option value="3" <?php selected($dbhandler->get_global_option_value('pm_display_name_pattern',1), 3 ); ?>><?php _e('F. LastName','profilegrid-user-display-name');?></option>
              <option value="4" <?php selected($dbhandler->get_global_option_value('pm_display_name_pattern',1), 4 ); ?>><?php _e('FirstName L.','profilegrid-user-display-name');?></option>
              <option value="5" <?php selected($dbhandler->get_global_option_value('pm_display_name_pattern',1), 5 ); ?>><?php _e('F.L.','profilegrid-user-display-name');?></option>
              <option value="6" <?php selected($dbhandler->get_global_option_value('pm_display_name_pattern',1), 6 ); ?>><?php _e('NickName','profilegrid-user-display-name');?></option>
              
              <option value="7" <?php selected($dbhandler->get_global_option_value('pm_display_name_pattern',1), 7 ); ?>><?php _e('UserName','profilegrid-user-display-name');?></option>
              <option value="8" <?php selected($dbhandler->get_global_option_value('pm_display_name_pattern',1), 8 ); ?>><?php _e('Email','profilegrid-user-display-name');?></option>
          </select>
          <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e("Select a pattern for displaying names.",'profilegrid-user-display-name');?></div>
      </div>
            
          <div class="uimrow">
        <div class="uimfield">
          <?php _e('Display Name Style','profilegrid-user-display-name');?>
        </div>
        <div class="uiminput">
          <select name="pm_display_name_style" id="pm_display_name_style">
              <option value="0" <?php selected($dbhandler->get_global_option_value('pm_display_name_style',0), 0 ); ?>><?php _e('Default','profilegrid-user-display-name');?></option>
              <option value="1" <?php selected($dbhandler->get_global_option_value('pm_display_name_style',0), 1 ); ?>><?php _e('Capitalized','profilegrid-user-display-name');?></option>
              <option value="2" <?php selected($dbhandler->get_global_option_value('pm_display_name_style',0), 2 ); ?>><?php _e('Uppercase','profilegrid-user-display-name');?></option>
              <option value="3" <?php selected($dbhandler->get_global_option_value('pm_display_name_style',0), 3 ); ?>><?php _e('lowercase','profilegrid-user-display-name');?></option>
              <option value="4" <?php selected($dbhandler->get_global_option_value('pm_display_name_style',0), 4 ); ?>><?php _e('Underlined','profilegrid-user-display-name');?></option>
              <option value="5" <?php selected($dbhandler->get_global_option_value('pm_display_name_style',0), 5 ); ?>><?php _e('Bold','profilegrid-user-display-name');?></option>
          </select>
          <div class="errortext"></div>
        </div>
        <div class="uimnote"><?php _e("Select a text style for the user display name on their profiles.",'profilegrid-user-display-name');?></div>
      </div>
          
          <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable Display Style for Prefix and Suffix','profilegrid-user-display-name' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_display_name_style_prefix_suffix" id="pm_enable_display_name_style_prefix_suffix" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_display_name_style_prefix_suffix','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_enable_display_name_style_prefix_suffix"></label>
        </div>
        <div class="uimnote"><?php _e("Apply a seperate style to the Prefix and Suffix you are using with display names.",'profilegrid-user-display-name');?></div>
      </div>
      
          <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Add Prefix', 'profilegrid-user-display-name' ); ?>
        </div>
        <div class="uiminput">
        <input name="pm_enable_prefix" id="pm_enable_prefix" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_prefix','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'prefix_html')" />
          <label for="pm_enable_prefix"></label>
          
        </div>
        <div class="uimnote"><?php _e("Define a common prefix for all display names.","profilegrid-user-display-name"); ?></div>
      </div>
       <div class="childfieldsrow" id="prefix_html" style=" <?php if($dbhandler->get_global_option_value('pm_enable_prefix','0')=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
      <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Prefix', 'profilegrid-user-display-name' ); ?>
        </div>
        <div class="uiminput">
         <input name="pm_set_prefix" id="pm_set_prefix" type="text" value="<?php  echo ( $dbhandler->get_global_option_value('pm_set_prefix'));?>" />
       
          
        </div>
        <div class="uimnote"><?php _e('Define prefix text.','profilegrid-user-display-name');?></div>
      </div>
      </div>
          
          
           <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Add Suffix', 'profilegrid-user-display-name' ); ?>
        </div>
        <div class="uiminput">
        <input name="pm_enable_postfix" id="pm_enable_postfix" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_postfix','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'postfix_html')" />
          <label for="pm_enable_postfix"></label>
          
        </div>
        <div class="uimnote"><?php _e('Define a common suffix for all display names.','profilegrid-user-display-name');?></div>
      </div>
       <div class="childfieldsrow" id="postfix_html" style=" <?php if($dbhandler->get_global_option_value('pm_enable_postfix',0)==1){echo 'display:block;';} else { echo 'display:none;';} ?>">
      <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Suffix ', 'profilegrid-user-display-name' ); ?>
        </div>
        <div class="uiminput">
         <input name="pm_set_postfix" id="pm_set_postfix" type="text" value="<?php echo( $dbhandler->get_global_option_value('pm_set_postfix'));?>" />
       
          
        </div>
        <div class="uimnote"><?php _e('Define suffix text.','profilegrid-user-display-name');?></div>
      </div>
      </div>
            
            
      </div>
        
 
      <div class="buttonarea"> 
          <a href="admin.php?page=pm_settings">
        <div class="cancel">&#8592; &nbsp;
          <?php _e('Cancel','profilegrid-user-display-name');?>
        </div>
        </a>
        <?php wp_nonce_field('save_display_name_settings'); ?>
        <input type="submit" value="<?php _e('Save','profilegrid-user-display-name');?>" name="submit_settings" id="submit_settings" />
        <div class="all_error_text" style="display:none;"></div>
      </div>
    </div>
   
  </form>
</div>