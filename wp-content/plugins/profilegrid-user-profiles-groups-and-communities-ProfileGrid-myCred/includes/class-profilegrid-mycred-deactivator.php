<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Mycred
 * @subpackage Profilegrid_Mycred/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Profilegrid_Mycred
 * @subpackage Profilegrid_Mycred/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Mycred_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
            if(class_exists('PM_request')):
                $pmrequests = new PM_request;
                $profile_tabs = $pmrequests->pm_profile_tabs();
                $dbhandler = new PM_DBhandler;
                if(isset($profile_tabs['pg-mycred-badges']))
                {
                     unset($profile_tabs['pg-mycred-badges']);
                }
                $dbhandler->update_global_option_value('pm_profile_tabs_order_status',$profile_tabs);
            endif;
	}

}
