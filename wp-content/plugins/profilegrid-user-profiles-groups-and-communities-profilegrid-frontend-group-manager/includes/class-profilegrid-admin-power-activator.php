<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Admin_Power
 * @subpackage Profilegrid_Admin_Power/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Profilegrid_Admin_Power
 * @subpackage Profilegrid_Admin_Power/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Admin_Power_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
         public function activate()
         {
            if (!class_exists('Profile_Magic') ) 
            {
              //echo plugin_basename( 'profilegrid-woocommerce/profilegrid-woocommerce.php',__FILE__ );
               
               deactivate_plugins('profilegrid-frontend-group-manager/profilegrid-frontend-group-manager.php'); 
               $error_message = sprintf(__('This plugin requires <a href="%s">ProfileGrid</a> plugin to be active!', 'profile-magic'),'https://wordpress.org/plugins/profilegrid-user-profiles-groups-and-communities/');
               wp_die($error_message);
            }
            
            global $wpdb;
            if ( is_multisite() ) {
                // Get all blogs in the network and activate plugin on each one
                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
                foreach ( $blog_ids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    $this->create_table();
                    restore_current_blog();
                }
            } else { $this->create_table(); }  
         }
         
         public function create_table()
         {
            $dbhandler = new PM_DBhandler;
            $pmrequest = new PM_request;
            $gid = $dbhandler->get_all_result('GROUPS', 'id', 1, 'var', 0, 1, 'id', 'DESC');
            $is_created = $dbhandler->get_global_option_value('pg_email_templates_created','0');
            if($is_created=='0')
            {
                //$pmrequest->pg_auto_create_default_email_template($gid);
            }
              
         }
	
}