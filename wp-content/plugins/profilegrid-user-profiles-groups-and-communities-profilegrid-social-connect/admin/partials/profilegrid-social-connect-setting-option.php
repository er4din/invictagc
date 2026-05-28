<?php
$path =  plugin_dir_url(__FILE__);
?>
<div class="uimrow"> <a href="admin.php?page=pm_social_connect_settings">
  <div class="pm_setting_image"> <img src="<?php echo $path;?>images/social-connect.png" class="options" alt="options"> </div>
  <div class="pm-setting-heading"> <span class="pm-setting-icon-title">
    <?php _e( 'Social Login','profilegrid-social-connect' ); ?>
    </span> <span class="pm-setting-description">
    <?php _e( 'Configure social networks.', 'profilegrid-social-connect' ); ?>
    </span> </div>
  </a> </div>