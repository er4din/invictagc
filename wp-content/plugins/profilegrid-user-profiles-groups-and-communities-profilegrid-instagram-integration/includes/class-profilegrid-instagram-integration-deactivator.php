<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Instagram_Integration
 * @subpackage Profilegrid_Instagram_Integration/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Profilegrid_Instagram_Integration
 * @subpackage Profilegrid_Instagram_Integration/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Instagram_Integration_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
            if (class_exists('Profile_Magic') ) 
            {
                $pmrequests = new PM_request;
                $profile_tabs = $pmrequests->pm_profile_tabs();
                $dbhandler = new PM_DBhandler;
                if(isset($profile_tabs['pg_instagram_integration_tab_content']))
                {
                     unset($profile_tabs['pg_instagram_integration_tab_content']);
                }
                $dbhandler->update_global_option_value('pm_profile_tabs_order_status',$profile_tabs);
            }
	}

}
