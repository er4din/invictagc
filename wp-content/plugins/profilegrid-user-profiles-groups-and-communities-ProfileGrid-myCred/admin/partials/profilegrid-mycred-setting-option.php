<?php
$path =  plugin_dir_url(__FILE__);
?>
<div class="uimrow"> <a href="admin.php?page=pm_mycred_settings">
  <div class="pm_setting_image"> <img src="<?php echo $path;?>images/pg-mycred-integration.png" class="options" alt="options"> </div>
  <div class="pm-setting-heading"> <span class="pm-setting-icon-title">
    <?php _e( 'myCred Integration','profilegrid-mycred-integration' ); ?>
    </span> <span class="pm-setting-description">
    <?php _e( 'Integrate myCRED with User Profiles.', 'profilegrid-mycred-integration' ); ?>
    </span> </div>
  </a> </div>