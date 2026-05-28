<?php
$path =  plugin_dir_url(__FILE__);
?>
<div class="uimrow"> <a href="admin.php?page=pm_display_name_settings">
  <div class="pm_setting_image"> <img src="<?php echo $path;?>images/display_name.png" class="options" alt="options"> </div>
  <div class="pm-setting-heading"> <span class="pm-setting-icon-title">
    <?php _e( 'User Display Name','profilegrid-user-display-name' ); ?>
    </span> <span class="pm-setting-description">
    <?php _e( 'Customize display names, define patterns.', 'profilegrid-user-display-name' ); ?>
    </span> </div>
  </a> </div>