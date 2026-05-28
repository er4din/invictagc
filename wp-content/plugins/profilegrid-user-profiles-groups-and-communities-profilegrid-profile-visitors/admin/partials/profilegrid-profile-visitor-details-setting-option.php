<?php
$path =  plugin_dir_url(__FILE__);
?>
<div class="uimrow"> <a href="admin.php?page=pm_profile_visitor_details_settings">
  <div class="pm_setting_image"> <img src="<?php echo esc_html($path);?>images/profile-visitor-icon.png" class="options" alt="options"> </div>
  <div class="pm-setting-heading"> <span class="pm-setting-icon-title">
    <?php esc_html_e( 'Profile Visitors','profilegrid-profile-visitor-details' ); ?>
    </span> <span class="pm-setting-description">
    <?php esc_html_e( 'Manage visitor tracking options', 'profilegrid-profile-visitor-details' ); ?>
    </span> </div>
  </a> </div>