<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_User_Content
 * @subpackage Profilegrid_User_Content/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Profilegrid_User_Content
 * @subpackage Profilegrid_User_Content/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_User_Content_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
            if (class_exists('Profile_Magic') ) {
                $pmrequests = new PM_request;
                $profile_tabs = $pmrequests->pm_profile_tabs();

                $dbhandler = new PM_DBhandler;
                $tabs =  $dbhandler->get_all_result('CUSTOMTABS');
                $newtabs = array();
                if(!empty($tabs))
                {
                    $i = 1;
                    foreach($tabs as $tab)
                    {
                        $id = sanitize_key($tab->tab_label).$i;
                        if(isset($profile_tabs[$id]))
                        {
                           unset($profile_tabs[$id]);
                        }
                         $i++;
                    }
                }
                $dbhandler->update_global_option_value('pm_profile_tabs_order_status',$profile_tabs);
            }
	}

}
