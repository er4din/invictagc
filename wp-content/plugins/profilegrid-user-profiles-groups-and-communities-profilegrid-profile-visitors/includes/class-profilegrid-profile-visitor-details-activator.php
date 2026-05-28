<?php
/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_profile_visitor_details
 * @subpackage Profilegrid_profile_visitor_details/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Profilegrid_profile_visitor_details
 * @subpackage Profilegrid_profile_visitor_details/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_profile_visitor_details_Activator {

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
         
         public function create_table() {

            global $wpdb;
            if ( version_compare( get_bloginfo( 'version' ), '6.1' ) < 0 ) {
                    require_once ABSPATH . 'wp-includes/wp-db.php';
            } else {
                    require_once ABSPATH . 'wp-includes/class-wpdb.php';
            }
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
           $PM_Helper_profile_visitor_details = new PM_Helper_PROFILE_VISITOR_DETAILS;
               $charset_collate = $wpdb->get_charset_collate();
             $table_name = $PM_Helper_profile_visitor_details->get_db_table_name('PROFILE_VISITORS_DETAILS');
            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `uid` int(11) NOT NULL,
                  `visitor_id` int(11) DEFAULT 0,
		  `timestamp` datetime NOT NULL,
                  `meta_details` longtext DEFAULT NULL,
                  `ip_address` varchar(255) default NUll,
                   PRIMARY KEY (id)
		)$charset_collate;";
              dbDelta($sql);
	}	
}