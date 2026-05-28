<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Admin_Power
 * @subpackage Profilegrid_Admin_Power/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Admin_Power
 * @subpackage Profilegrid_Admin_Power/admin
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Admin_Power_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $profilegrid_admin_power    The ID of this plugin.
	 */
	private $profilegrid_admin_power;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $profilegrid_admin_power       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_admin_power, $version ) {

		$this->profilegrid_admin_power = $profilegrid_admin_power;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profilegrid_Admin_Power_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Admin_Power_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profilegrid_Admin_Power_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Admin_Power_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
             
	}
        
       
        
        public function profile_magic_admin_power_notice_fun()
        {
            if (!class_exists('Profile_Magic') ) {
                    
                $this->Admin_power_installation();
                    //wp_die( "ProfileGrid Stripe won't work as unable to locate ProfileGrid plugin files." );
            }
        }
        
        public function Admin_power_installation()
        {
            $plugin_slug= 'profilegrid-user-profiles-groups-and-communities';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo sprintf(__( "ProfileGrid Frontend Group Manager work with ProfileGrid Plugin. You can install it  from <a href='%s'>Here</a>.", 'profilegrid-frontend-group-manager' ),$installUrl); ?></p>
            </div>
            <?php
            deactivate_plugins(plugin_basename( plugin_dir_path( __DIR__ ) ) . '/profilegrid-frontend-group-manager.php'); 
        }
        
      
        
        public function activate_sitewide_plugins($blog_id)
        {
            // Switch to new website
            $dbhandler = new PM_DBhandler;
            $activator = new Profile_Magic_Activator;
            switch_to_blog( $blog_id );
            // Activate
            foreach( array_keys( get_site_option( 'active_sitewide_plugins' ) ) as $plugin ) {
                do_action( 'activate_'  . $plugin, false );
                do_action( 'activate'   . '_plugin', $plugin, false );
                $activator->activate();
                
            }
            // Restore current website 
            restore_current_blog();
        }
        
        public function profile_magic_admin_power_group_option($id,$group_options)
        {
           include 'partials/profilegrid-admin-power-group-option.php';
        }
        

}
