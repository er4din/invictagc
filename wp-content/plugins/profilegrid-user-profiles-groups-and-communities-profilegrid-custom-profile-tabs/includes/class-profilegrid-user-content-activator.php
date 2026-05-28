<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_User_Content
 * @subpackage Profilegrid_User_Content/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Profilegrid_User_Content
 * @subpackage Profilegrid_User_Content/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_User_Content_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0e
	 */
         public function activate()
         {
            global $wpdb;
            if (class_exists('Profile_Magic') ) 
            {
                if ( is_multisite()) {
                    // Get all blogs in the network and activate plugin on each one
                    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
                    foreach ( $blog_ids as $blog_id ) {
                        switch_to_blog( $blog_id );
                        $this->create_table();
                        restore_current_blog();
                    }
                } else {
                   $this->create_table();
                }
            }
         }
         
         public function create_table()
         {
            global $wpdb;
            if(version_compare(get_bloginfo('version'),'6.1') < 0)
            {
                require_once ABSPATH . 'wp-includes/wp-db.php';
            }
            else
            {
                require_once( ABSPATH . 'wp-includes/class-wpdb.php');
            }
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            $pm_helper_customtabs = new PM_Helper_CUSTOMTABS;
            //Ensures proper charset support. Also limits support for WP v3.5+.
            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $pm_helper_customtabs->get_db_table_name('CUSTOMTABS');
            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `tab_label` varchar(255) NOT NULL,
                  `tab_content_from` varchar(255) NOT NULL,
                  `tab_data_type` varchar(255) NOT NULL,
                  `tab_content` longtext DEFAULT NULL,
                  `tab_meta` longtext DEFAULT NULL,
		   PRIMARY KEY (`id`)
		)$charset_collate;";
              dbDelta($sql);
         }
         
         
	
}