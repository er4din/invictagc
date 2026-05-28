<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$path =  plugin_dir_url(__FILE__);
$identifier = 'SETTINGS';
if(filter_input(INPUT_POST,'submit_settings'))
{
	$retrieved_nonce = filter_input(INPUT_POST,'_wpnonce');
	if (!wp_verify_nonce($retrieved_nonce, 'save_mycred_settings' ) ) die( 'Failed security check' );
	$exclude = array("_wpnonce","_wp_http_referer","submit_settings");
	if(!isset($_POST['pm_enable_mycred'])) $_POST['pm_enable_mycred'] = 0;
        if(!isset($_POST['pm_mycred_display_rank_on_profile'])) $_POST['pm_mycred_display_rank_on_profile'] = 0;
        if(!isset($_POST['pm_mycred_display_point_history'])) $_POST['pm_mycred_display_point_history'] = 0;
	if(!isset($_POST['pm_mycred_display_badges'])) $_POST['pm_mycred_display_badges'] = 0;
        if(!isset($_POST['pm_mycred_display_all_available_badges'])) $_POST['pm_mycred_display_all_available_badges'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_profile_image'])) $_POST['pm_mycred_enable_points_profile_image'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_cover_image'])) $_POST['pm_mycred_enable_points_cover_image'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_update_profile'])) $_POST['pm_mycred_enable_points_update_profile'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_user_approved'])) $_POST['pm_mycred_enable_points_user_approved'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_user_blog_post_published'])) $_POST['pm_mycred_enable_points_user_blog_post_published'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_promoted_user_to_group_manager'])) $_POST['pm_mycred_enable_points_promoted_user_to_group_manager'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_friend_request_approved'])) $_POST['pm_mycred_enable_points_friend_request_approved'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_upload_group_photo'])) $_POST['pm_mycred_enable_points_upload_group_photo'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_post_on_group_wall'])) $_POST['pm_mycred_enable_points_post_on_group_wall'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_user_access_restricted_content'])) $_POST['pm_mycred_enable_points_user_access_restricted_content'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_user_leave_a_group'])) $_POST['pm_mycred_enable_points_user_leave_a_group'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_user_remove_profile_image'])) $_POST['pm_mycred_enable_points_user_remove_profile_image'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_user_remove_cover_image'])) $_POST['pm_mycred_enable_points_user_remove_cover_image'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_friend_request_rejected'])) $_POST['pm_mycred_enable_points_friend_request_rejected'] = 0;
        if(!isset($_POST['pm_mycred_enable_points_user_suspended'])) $_POST['pm_mycred_enable_points_user_suspended'] = 0;
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
  <form name="pm_mycred_settings" id="pm_mycred_settings" method="post">
    <!-----Dialogue Box Starts----->
    <div class="content">
        <div class="uimheader" style="text-transform: none;">
        <?php _e( 'myCRED INTEGRATION','profilegrid-mycred-integration' ); ?>
      </div>
     
      <div class="uimsubheader">
        <?php
		//Show subheadings or message or notice
		?>
      </div>
    
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'myCRED Integration','profilegrid-mycred-integration' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_mycred" id="pm_enable_mycred" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_mycred','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_html')" />
          <label for="pm_enable_mycred"></label>
        </div>
        <div class="uimnote"><?php _e("Turn on to start myCRED Integration. Once on, a new ProfileGrid menu item
named ‘myCRED’ will appear. Clicking on it will allow you to set point reward rules for your users.",'profilegrid-mycred-integration');?></div>
      </div>
        
     <div class="childfieldsrow" id="pm_mycred_html" style=" <?php  if($dbhandler->get_global_option_value('pm_enable_mycred','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
          <?php $mycred_types = get_option( 'mycred_types' ); 
          
          ?>
      <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Select Point Type','profilegrid-mycred-integration' ); ?>
        </div>
       <div class="uiminput">
              <select name="pm_mycred_type" id="pm_mycred_type">
                  <?php 
                  if($mycred_types):
                  foreach($mycred_types as $key=>$type)
                  {
                    ?>
                        <option value="<?php echo $key; ?>" <?php selected($dbhandler->get_global_option_value('pm_mycred_type'),$key); ?>><?php echo $type;?> </option>
                    <?php
                  }
                  else:
                       ?>
                        <option value="mycred_default" <?php selected($dbhandler->get_global_option_value('pm_mycred_type'),'mycred_default'); ?>><?php echo 'myCRED';?> </option>
                    <?php
                  endif;
                  
                  ?>
              </select>
            </div>
        <div class="uimnote"><?php _e("Select the myCRED Point Type you want to use with ProfileGrid.",'profilegrid-mycred-integration');?></div>
      </div>
         
         
      <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Display Rank on the User Profile','profilegrid-mycred-integration' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_mycred_display_rank_on_profile" id="pm_mycred_display_rank_on_profile" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_display_rank_on_profile','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_mycred_display_rank_on_profile"></label>
        </div>
        <div class="uimnote"><?php _e("Display user rank based on the Point Type selected above on User Profiles publicly. Only ranks associated with Point Type defined above will be displayed.",'profilegrid-mycred-integration');?></div>
      </div>   
         
       <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Display Points History in User Profile Settings','profilegrid-mycred-integration' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_mycred_display_point_history" id="pm_mycred_display_point_history" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_display_point_history','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_mycred_display_point_history"></label>
        </div>
        <div class="uimnote"><?php _e("Display Point history in private area of User Profiles. A new settings tab will appear displaying current points and history.",'profilegrid-mycred-integration');?></div>
      </div>      
         
       <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Display Badges','profilegrid-mycred-integration' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_mycred_display_badges" id="pm_mycred_display_badges" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_display_badges','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_display_badges_html')" />
          <label for="pm_mycred_display_badges"></label>
        </div>
        <div class="uimnote"><?php _e("Display a new tab in User Profiles with badges earned by the user.",'profilegrid-mycred-integration');?></div>
      </div>  
         
         
         <div class="childfieldsrow" id="pm_mycred_display_badges_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_display_badges','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Title of the Tab','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_display_badges_tab_title" id="pm_mycred_display_badges_tab_title" type="text" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_display_badges_tab_title',__('Badges','profilegrid-mycred-integration')); ?>" />
                </div>
                <div class="uimnote"><?php _e("Set name of the profile tab which will display badges.",'profilegrid-mycred-integration');?></div>
              </div> 
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Display All Available Badges','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                   <input name="pm_mycred_display_all_available_badges" id="pm_mycred_display_all_available_badges" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_display_all_available_badges','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
                  <label for="pm_mycred_display_all_available_badges"></label>
                </div>
                <div class="uimnote"><?php _e("Display all available badges including those not earned by the user. Earned badges will be highlighted.",'profilegrid-mycred-integration');?></div>
              </div>  
             
             
         </div>
         
         <h4><?php _e('Rules','profilegrid-mycred-integration');?></h4>
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Uploads Profile Image','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_profile_image" id="pm_mycred_enable_points_profile_image" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_profile_image','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_profile_image_html')" />
              <label for="pm_mycred_enable_points_profile_image"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user on uploading a new or changing an existing Profile Image.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_profile_image_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_profile_image','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_profile_image_points" id="pm_mycred_profile_image_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_profile_image_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_profile_image_points_limit" id="pm_mycred_profile_image_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_profile_image_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
          <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Uploads Cover Image','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_cover_image" id="pm_mycred_enable_points_cover_image" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_cover_image','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_cover_image_html')" />
              <label for="pm_mycred_enable_points_cover_image"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user on uploading a new or changing an existing Cover Image",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_cover_image_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_cover_image','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_cover_image_points" id="pm_mycred_cover_image_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_cover_image_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_cover_image_points_limit" id="pm_mycred_cover_image_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_cover_image_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Updates Profile','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_update_profile" id="pm_mycred_enable_points_update_profile" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_update_profile','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_update_profile_html')" />
              <label for="pm_mycred_enable_points_update_profile"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user on filling an empty profile field or updating an existing value.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_update_profile_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_update_profile','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_update_profile_points" id="pm_mycred_update_profile_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_update_profile_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_update_profile_points_limit" id="pm_mycred_update_profile_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_update_profile_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User is Approved by Group or Site Admin for Closed Group','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_user_approved" id="pm_mycred_enable_points_user_approved" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_user_approved','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_user_approved_html')" />
              <label for="pm_mycred_enable_points_user_approved"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user when his group joining request is approved by site admin or Group Manager.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_user_approved_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_approved','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_approved_points" id="pm_mycred_user_approved_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_approved_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_approved_points_limit" id="pm_mycred_user_approved_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_approved_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
         
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Blog Post is Published','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_user_blog_post_published" id="pm_mycred_enable_points_user_blog_post_published" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_user_blog_post_published','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_user_blog_post_published_html')" />
              <label for="pm_mycred_enable_points_user_blog_post_published"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user on successfully publishing a blog post using the post submission form. Applies if the post is automatically published or approved by the Group Manager.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_user_blog_post_published_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_blog_post_published','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_blog_post_published_points" id="pm_mycred_user_blog_post_published_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_blog_post_published_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_blog_post_published_points_limit" id="pm_mycred_user_blog_post_published_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_blog_post_published_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User is Promoted to Group Manager','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_promoted_user_to_group_manager" id="pm_mycred_enable_points_promoted_user_to_group_manager" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_promoted_user_to_group_manager','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_promoted_user_to_group_manager_html')" />
              <label for="pm_mycred_enable_points_promoted_user_to_group_manager"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user when he or she is promoted to Group Manager",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_promoted_user_to_group_manager_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_promoted_user_to_group_manager','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_promoted_user_to_group_manager_points" id="pm_mycred_promoted_user_to_group_manager_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_promoted_user_to_group_manager_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_promoted_user_to_group_manager_points_limit" id="pm_mycred_promoted_user_to_group_manager_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_promoted_user_to_group_manager_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Friend Request is Approved','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_friend_request_approved" id="pm_mycred_enable_points_friend_request_approved" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_friend_request_approved','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_friend_request_approved_html')" />
              <label for="pm_mycred_enable_points_friend_request_approved"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user when his/ her friend request is approved by another user.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_friend_request_approved_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_friend_request_approved','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_promoted_friend_request_approved_points" id="pm_mycred_promoted_friend_request_approved_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_promoted_friend_request_approved_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_promoted_friend_request_approved_points_limit" id="pm_mycred_promoted_friend_request_approved_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_promoted_friend_request_approved_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         <?php if(class_exists('Profilegrid_Group_photos')):  ?>
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Uploads Group Photo','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_upload_group_photo" id="pm_mycred_enable_points_upload_group_photo" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_upload_group_photo','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_upload_group_photo_html')" />
              <label for="pm_mycred_enable_points_upload_group_photo"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user on uploading a new Group Photo",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_upload_group_photo_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_upload_group_photo','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_upload_group_photo_points" id="pm_mycred_upload_group_photo_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_upload_group_photo_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_upload_group_photo_points_limit" id="pm_mycred_upload_group_photo_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_upload_group_photo_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         <?php endif; ?>
         <!-- end rule -->
         <?php if(class_exists('Profilegrid_Group_Wall')):  ?>
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Post on Group Wall','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_post_on_group_wall" id="pm_mycred_enable_points_post_on_group_wall" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_post_on_group_wall','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_post_on_group_wall_html')" />
              <label for="pm_mycred_enable_points_post_on_group_wall"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user on uploading a new Group Photo",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_post_on_group_wall_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_post_on_group_wall','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_post_on_group_wall_points" id="pm_mycred_post_on_group_wall_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_post_on_group_wall_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_post_on_group_wall_points_limit" id="pm_mycred_post_on_group_wall_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_post_on_group_wall_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         <?php endif;?>
         <!-- start Rule  -->
         <?php /*
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Access Restricted Content','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_user_access_restricted_content" id="pm_mycred_enable_points_user_access_restricted_content" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_user_access_restricted_content','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_user_access_restricted_content_html')" />
              <label for="pm_mycred_enable_points_user_access_restricted_content"></label>
            </div>
            <div class="uimnote"><?php _e("Award points to the user when accessing restricted content.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_user_access_restricted_content_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_access_restricted_content','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_access_restricted_content_points" id="pm_mycred_user_access_restricted_content_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_access_restricted_content_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_access_restricted_content_points_limit" id="pm_mycred_user_access_restricted_content_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_access_restricted_content_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         <?php */?>
         <!-- end rule -->
         
          <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Leaves a Group','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_user_leave_a_group" id="pm_mycred_enable_points_user_leave_a_group" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_user_leave_a_group','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_user_leave_a_group_html')" />
              <label for="pm_mycred_enable_points_user_leave_a_group"></label>
            </div>
            <div class="uimnote"><?php _e("Award or deduct points from the user on leaving a Group he or she is a member of.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_user_leave_a_group_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_leave_a_group','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_leave_a_group_points" id="pm_mycred_user_leave_a_group_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_leave_a_group_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user. Negative numbers can be used to deduct points.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_leave_a_group_points_limit" id="pm_mycred_user_leave_a_group_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_leave_a_group_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded/ deducted. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Removes Profile Image','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_user_remove_profile_image" id="pm_mycred_enable_points_user_remove_profile_image" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_user_remove_profile_image','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_user_remove_profile_image_html')" />
              <label for="pm_mycred_enable_points_user_remove_profile_image"></label>
            </div>
            <div class="uimnote"><?php _e("Award or deduct points from the user on removing his/ her profile image.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_user_remove_profile_image_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_remove_profile_image','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_remove_profile_image_points" id="pm_mycred_user_remove_profile_image_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_remove_profile_image_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user. Negative numbers can be used to deduct points.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_remove_profile_image_points_limit" id="pm_mycred_user_remove_profile_image_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_remove_profile_image_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded/ deducted. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Removes Cover Image','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_user_remove_cover_image" id="pm_mycred_enable_points_user_remove_cover_image" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_user_remove_cover_image','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_user_remove_cover_image_html')" />
              <label for="pm_mycred_enable_points_user_remove_cover_image"></label>
            </div>
            <div class="uimnote"><?php _e("Award or deduct points from the user on removing his/ her cover image.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_user_remove_cover_image_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_remove_cover_image','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_remove_cover_image_points" id="pm_mycred_user_remove_cover_image_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_remove_cover_image_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user. Negative numbers can be used to deduct points.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_remove_cover_image_points_limit" id="pm_mycred_user_remove_cover_image_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_remove_cover_image_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded/ deducted. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
          <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User Friend Request is Rejected','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_friend_request_rejected" id="pm_mycred_enable_points_friend_request_rejected" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_friend_request_rejected','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_friend_request_rejected_html')" />
              <label for="pm_mycred_enable_points_friend_request_rejected"></label>
            </div>
            <div class="uimnote"><?php _e("Award or deduct points from the user when his/ her friend request is rejected.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_friend_request_rejected_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_friend_request_rejected','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_friend_request_rejected_points" id="pm_mycred_friend_request_rejected_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_friend_request_rejected_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user. Negative numbers can be used to deduct points.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_friend_request_rejected_points_limit" id="pm_mycred_friend_request_rejected_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_friend_request_rejected_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded/ deducted. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
         <!-- start Rule  -->
         <div class="uimrow">
            <div class="uimfield">
              <?php _e( 'User is Suspended','profilegrid-mycred-integration' ); ?>
            </div>
            <div class="uiminput">
               <input name="pm_mycred_enable_points_user_suspended" id="pm_mycred_enable_points_user_suspended" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_mycred_enable_points_user_suspended','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_mycred_enable_points_user_suspended_html')" />
              <label for="pm_mycred_enable_points_user_suspended"></label>
            </div>
            <div class="uimnote"><?php _e("Award or deduct points from the user when he/ she is suspended by site admin or Group Manager.",'profilegrid-mycred-integration');?></div>
          </div> 
         
          <div class="childfieldsrow" id="pm_mycred_enable_points_user_suspended_html" style=" <?php  if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_suspended','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">  
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Points','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_suspended_points" id="pm_mycred_user_suspended_points" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_suspended_points'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Points to be awarded to the user. Negative numbers can be used to deduct points.",'profilegrid-mycred-integration');?></div>
              </div>
              <div class="uimrow">
                <div class="uimfield">
                  <?php _e( 'Limit','profilegrid-mycred-integration' ); ?>
                </div>
                <div class="uiminput">
                    <input name="pm_mycred_user_suspended_points_limit" id="pm_mycred_user_suspended_points_limit" type="number" value="<?php echo $dbhandler->get_global_option_value('pm_mycred_user_suspended_points_limit','0'); ?>" />
                </div>
                <div class="uimnote"><?php _e("Maximum Number of times the points will be awarded/ deducted. Use 0 for no limits.",'profilegrid-mycred-integration');?></div>
              </div>
          </div>
         
         <!-- end rule -->
         
     </div>
        
 
      <div class="buttonarea"> 
          <a href="admin.php?page=pm_settings">
        <div class="cancel">&#8592; &nbsp;
          <?php _e('Cancel','profilegrid-mycred-integration');?>
        </div>
        </a>
        <?php wp_nonce_field('save_mycred_settings'); ?>
        <input type="submit" value="<?php _e('Save','profilegrid-mycred-integration');?>" name="submit_settings" id="submit_settings" />
        <div class="all_error_text" style="display:none;"></div>
      </div>
    </div>
   
  </form>
</div>
