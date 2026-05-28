<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$path =  plugin_dir_url(__FILE__);
$identifier = 'SETTINGS';
if (filter_input(INPUT_POST, 'submit_settings')) {
  $retrieved_nonce = filter_input(INPUT_POST, '_wpnonce');
  if (!wp_verify_nonce($retrieved_nonce, 'save_profile_visitor_settings')) die('Failed security check');
  $exclude = array("_wpnonce", "_wp_http_referer", "submit_settings");
  if (!isset($_POST['pm_enable_profile_visitor_details'])) $_POST['pm_enable_profile_visitor_details'] = 0;
  if (!isset($_POST['pm_profile_visitor_details_to_users'])) $_POST['pm_profile_visitor_details_to_users'] = 0;
  if (!isset($_POST['pm_profile_visitor_details_tab_title'])) $_POST['pm_profile_visitor_details_tab_title'] = '';
  if (!isset($_POST['pm_record_visitor_ip'])) $_POST['pm_record_visitor_ip'] = 0;
  if (!isset($_POST['pm_enable_user_to_reset_visitors'])) $_POST['pm_enable_user_to_reset_visitors'] = 0;
  if (!isset($_POST['pm_enable_user_to_opt_out'])) $_POST['pm_enable_user_to_opt_out'] = 0;
  if (!isset($_POST['pm_enable_specific_group_opt_out'])) $_POST['pm_enable_specific_group_opt_out'] = 0;
  if (!isset($_POST['pm_enable_visit_spam_control'])) $_POST['pm_enable_visit_spam_control'] = 0;
  $post = $pmrequests->sanitize_request($_POST, $identifier, $exclude);
  if ($post != false) {
    foreach ($post as $key => $value) {
      $dbhandler->update_global_option_value($key, $value);
    }
  }
  //update_option('pm_selected_groups', $post['pm_selected_groups']);
  wp_redirect('admin.php?page=pm_settings');
  exit;
}
?>

<div class="uimagic">
  <form name="pm_profile_visitor_details_settings" id="pm_profile_visitor_details_settings" method="post">
    <!-----Dialogue Box Starts----->
    <div class="content">
      <div class="uimheader">
        <?php esc_html_e('Profile Visitor Details', 'profilegrid-profile-visitor-details'); ?>
      </div>

      <div class="uimsubheader">
        <?php
        //Show subheadings or message or notice
        ?>
      </div>

      <div class="uimrow">
        <div class="uimfield">
          <?php esc_html_e('Enable Profile Visitors', 'profilegrid-profile-visitor-details'); ?>
        </div>
        <div class="uiminput">
          <input name="pm_enable_profile_visitor_details" id="pm_enable_profile_visitor_details" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_profile_visitor_details', '0'), '1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_visitor_details_child')" />
          <label for="pm_enable_profile_visitor_details"></label>
        </div>
        <div class="uimnote"><?php esc_html_e("Turn on this option to allow users to see a list of visitors who have viewed their profile. When enabled, profile owners will have access to detailed visitor information on their profile page.", 'profilegrid-profile-visitor-details'); ?></div>
      </div>

      <div class="childfieldsrow" id="pm_visitor_details_child" style="<?php if ($dbhandler->get_global_option_value('pm_enable_profile_visitor_details', '0') == '1') {
                                                                          echo 'display:block;';
                                                                        } else {
                                                                          echo 'display:none;';
                                                                        } ?>">

        <div class="uimrow">
          <div class="uimfield">
            <?php esc_html_e('Record Visitor IP Address', 'profilegrid-profile-visitor-details'); ?>
          </div>
          <div class="uiminput">
            <input name="pm_record_visitor_ip" id="pm_record_visitor_ip" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_record_visitor_ip', '0'), '1'); ?> class="pm_toggle" value="1" style="display:none;" />
            <label for="pm_record_visitor_ip"></label>
          </div>
          <div class="uimnote"><?php esc_html_e("Enable this option to record the visitor's IP address. For example: 192.168.0.1. This data can be useful for analytics and security. Ensure compliance with applicable privacy regulations before enabling this feature.", 'profilegrid-profile-visitor-details'); ?></div>
        </div>

        <div class="uimrow">
          <div class="uimfield">
            <?php esc_html_e('Title of the Profile Tab', 'profilegrid-profile-visitor-details'); ?>
          </div>
          <div class="uiminput">
            <input name="pm_profile_visitor_details_tab_title" id="pm_profile_visitor_details_tab_title" type="text" value="<?php echo esc_attr($dbhandler->get_global_option_value('pm_profile_visitor_details_tab_title', '')); ?>" />
            <label for="pm_profile_visitor_details_tab_title"></label>
          </div>
          <div class="uimnote"><?php esc_html_e("Enter the title that will appear on the profile tab displaying visitor details. This will be visible to the profile owner in their account area.", 'profilegrid-profile-visitor-details'); ?></div>
        </div>

        <div class="uimrow">
          <div class="uimfield">
            <?php esc_html_e('Display Visitor Details on Frontend Profiles', 'profilegrid-profile-visitor-details'); ?>
          </div>
          <div class="uiminput">
            <input name="pm_profile_visitor_details_to_users" id="pm_profile_visitor_details_to_users" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_profile_visitor_details_to_users', '0'), '1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_profile_visitor_details_to_users_child')" />
            <label for="pm_profile_visitor_details_to_users"></label>
          </div>
          <div class="uimnote"><?php esc_html_e("Enable this option to show the visitor details on frontend user profiles. If disabled, the visitor information will remain hidden from the profile owner.", 'profilegrid-profile-visitor-details'); ?></div>
        </div>

        <div class="childfieldsrow" id="pm_profile_visitor_details_to_users_child" style="<?php if ($dbhandler->get_global_option_value('pm_profile_visitor_details_to_users', '0') == '1') {
                                                                                            echo 'display:block;';
                                                                                          } else {
                                                                                            echo 'display:none;';
                                                                                          } ?>">

          <div class="uimrow">
            <div class="uimfield">
              <?php esc_html_e('Allow Users to Reset or Delete Visitor Data', 'profilegrid-profile-visitor-details'); ?>
            </div>
            <div class="uiminput">
              <input name="pm_enable_user_to_reset_visitors" id="pm_enable_user_to_reset_visitors" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_user_to_reset_visitors', '0'), '1'); ?> class="pm_toggle" value="1" style="display:none;" />
              <label for="pm_enable_user_to_reset_visitors"></label>
            </div>
            <div class="uimnote"><?php esc_html_e("Enable this option to let profile owners reset or permanently delete the list of visitors who have viewed their profile. This gives users more control over their visitor data.", 'profilegrid-profile-visitor-details'); ?></div>
          </div>

          <div class="uimrow">
            <div class="uimfield">
              <?php esc_html_e('Allow Users to Opt Out', 'profilegrid-profile-visitor-details'); ?>
            </div>
            <div class="uiminput">
              <input name="pm_enable_user_to_opt_out" id="pm_enable_user_to_opt_out" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_user_to_opt_out', '0'), '1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_enable_user_to_opt_out_child')" />
              <label for="pm_enable_user_to_opt_out"></label>
            </div>
            <div class="uimnote"><?php esc_html_e("Enable this option to allow users to opt out of being tracked as visitors on other profiles.", 'profilegrid-profile-visitor-details'); ?></div>
          </div>

          <div class="childfieldsrow" id="pm_enable_user_to_opt_out_child" style="<?php if ($dbhandler->get_global_option_value('pm_enable_user_to_opt_out', '0') == '1') {
                                                                                    echo 'display:block;';
                                                                                  } else {
                                                                                    echo 'display:none;';
                                                                                  } ?>">
            <div class="uimrow">
              <div class="uimfield">
                <?php esc_html_e('Allow Users Opt Out for Specific Groups', 'profilegrid-profile-visitor-details'); ?>
              </div>
              <div class="uiminput">
                <input name="pm_enable_specific_group_opt_out" id="pm_enable_specific_group_opt_out" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_specific_group_opt_out', '0'), '1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_enable_specific_group_opt_out_child')" />
                <label for="pm_enable_specific_group_opt_out"></label>
              </div>
              <div class="uimnote"><?php esc_html_e("Allow this option to choose the user groups whose members are allowed to opt out of visitor tracking.", 'profilegrid-profile-visitor-details'); ?></div>
            </div>

            <div class="childfieldsrow" id="pm_enable_specific_group_opt_out_child" style="<?php if ($dbhandler->get_global_option_value('pm_enable_specific_group_opt_out', '0') == '1') {
                                                                                              echo 'display:block;';
                                                                                            } else {
                                                                                              echo 'display:none;';
                                                                                            } ?>">

              <div class="uimrow">
                <div class="uimfield">
                  <?php esc_html_e('Select User Groups Whose Members Can Opt Out', 'profilegrid-profile-visitor-details'); ?>
                </div>
                <div class="uiminput">
                  <?php
                  $groups =  $dbhandler->get_all_result('GROUPS', array('id', 'group_name'));
                  $selected_groups = maybe_unserialize($dbhandler->get_global_option_value('pm_selected_groups', ''));

                  ?>
                  <select name="pm_selected_groups[]" id="pm_select_group" multiple style="width: 300px;">
                    <?php
                    foreach ($groups as $group) {
                    ?>
                      <option value="<?php echo esc_attr($group->id); ?>"
                        <?php
                        //var_dump($selected_groups);
                        if (!is_array($selected_groups)) {
                          $selected_groups = explode(',', $selected_groups);
                        }

                        if (in_array($group->id, $selected_groups)) {
                          echo 'selected';
                        }
                        ?>><?php echo esc_html($group->group_name); ?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="uimnote"><?php esc_html_e('Choose the user groups whose members are allowed to opt out of visitor tracking. Only members of the selected groups will have the option to disable visitor tracking and viewing on their profiles.', 'profilegrid-profile-visitor-details'); ?></div>
              </div>
            </div>
          </div>

        </div>

        <div class="uimrow">
          <div class="uimfield">
            <?php esc_html_e('No. of Visitor Details per Page', 'profilegrid-profile-visitor-details'); ?>
          </div>
          <div class="uiminput">
            <input name="pm_visitor_per_page" id="pm_visitor_per_page" type="number" min="1" value="<?php echo esc_attr(($dbhandler->get_global_option_value('pm_visitor_per_page') != '') ? $dbhandler->get_global_option_value('pm_visitor_per_page') : 10); ?>" />
            <label for="pm_visitor_per_page"></label>
          </div>
          <div class="uimnote"><?php esc_html_e("Set the number of visitor details to display per page on the profile. If the number of visitors exceeds this limit, pagination will be added to navigate through the list.", 'profilegrid-profile-visitor-details'); ?></div>
        </div>

        <div class="uimrow">
          <div class="uimfield">
            <?php esc_html_e('Minimum Time Between Visits', 'profilegrid-profile-visitor-details'); ?>
          </div>
          <div class="uiminput">
            <input name="pm_visitor_count_span" id="pm_visitor_count_span" type="number" min="1" value="<?php echo esc_attr(($dbhandler->get_global_option_value('pm_visitor_count_span') != '') ? $dbhandler->get_global_option_value('pm_visitor_count_span') : 10); ?>" />
            <label for="pm_visitor_count_span"></label>
          </div>
          <div class="uimnote"><?php esc_html_e("Set the minimum time (in minutes) that must pass before another visit from the same IP address is counted as a new visitor. Visits from the same IP within this time period will be treated as part of the same session.", 'profilegrid-profile-visitor-details'); ?></div>
        </div>
        <div class="uimrow">
          <div class="uimfield">
            <?php esc_html_e('Delete Details Older Than', 'profilegrid-profile-visitor-details'); ?>
          </div>
          <div class="uiminput">
            <input name="pm_visitor_logs_auto_delete" id="pm_visitor_logs_auto_delete" type="number" min="1" value="<?php echo esc_attr(($dbhandler->get_global_option_value('pm_visitor_logs_auto_delete') != '') ? $dbhandler->get_global_option_value('pm_visitor_logs_auto_delete') : 30); ?>" />
            <label for="pm_visitor_logs_auto_delete"></label>
          </div>
          <div class="uimnote"><?php esc_html_e("Specify the age (in days) of visitor data to automatically delete. Visitor details older than the set number of days will be removed from profiles.", 'profilegrid-profile-visitor-details'); ?></div>
        </div>
      </div>

      <div class="buttonarea">
        <a href="admin.php?page=pm_settings">
          <div class="cancel">&#8592; &nbsp;
            <?php esc_html_e('Cancel', 'profilegrid-profile-visitor-details'); ?>
          </div>
        </a>
        <?php wp_nonce_field('save_profile_visitor_settings'); ?>
        <input type="submit" value="<?php esc_html_e('Save', 'profilegrid-profile-visitor-details'); ?>" name="submit_settings" id="submit_settings" />
        <div class="all_error_text" style="display:none;"></div>
      </div>

  </form>
</div>