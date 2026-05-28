<?php
$path =  plugin_dir_url(__FILE__);
?>
<div class="uimrow"> <a href="admin.php?page=pm_user_content_settings">
  <div class="pm_setting_image"> <img src="<?php echo $path;?>images/custom-profile-tab.png" class="options" alt="options"> </div>
  <div class="pm-setting-heading"> <span class="pm-setting-icon-title">
    <?php _e( 'Custom User Profile Tabs','profilegrid-custom-profile-tabs' ); ?>
    </span> <span class="pm-setting-description">
    <?php _e( "Display user authored custom post type data or shortcode.", "profilegrid-custom-profile-tabs" ); ?>
    </span> </div>
  </a> </div>