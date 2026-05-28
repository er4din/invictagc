<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Display_Name
 * @subpackage Profilegrid_Display_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Display_Name
 * @subpackage Profilegrid_Display_Name/admin
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Display_Name_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $profilegrid_display_name    The ID of this plugin.
	 */
	private $profilegrid_display_name;

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
	 * @param      string    $profilegrid_display_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_display_name, $version ) {

		$this->profilegrid_display_name = $profilegrid_display_name;
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
		 * defined in Profilegrid_Display_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Display_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
             if (class_exists('Profile_Magic') ) {
                    wp_enqueue_style( $this->profilegrid_display_name, plugin_dir_url( __FILE__ ) . 'css/profilegrid-display-name-admin.css', array(), $this->version, 'all' );

             }
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
		 * defined in Profilegrid_Display_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Display_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
             if (class_exists('Profile_Magic') ) {
                wp_enqueue_script( $this->profilegrid_display_name, plugin_dir_url( __FILE__ ) . 'js/profilegrid-display-name-admin.js', array( 'jquery' ), $this->version, false );
            }
	}
        
        public function profilegrid_display_name_admin_menu()
	{
                add_submenu_page("",__("Display Name Settings","profilegrid-user-display-name"),__("Display Name Settings","profilegrid-user-display-name"),"manage_options","pm_display_name_settings",array( $this, 'pm_display_name_settings' ));
        }
	
        public function pm_display_name_settings()
        {
            include 'partials/profilegrid-display-name-admin-display.php';
        }
        
        public function profilegrid_display_name_add_option_setting_page()
        {
            include 'partials/profilegrid-display-name-setting-option.php';
        }
        
        public function profile_magic_display_name_notice_fun()
        {
            if (!class_exists('Profile_Magic') ) {
                    
                $this->Display_name_installation();
                    //wp_die( "ProfileGrid Stripe won't work as unable to locate ProfileGrid plugin files." );
            }
        }
        
        public function Display_name_installation()
        {
            $plugin_slug= 'profilegrid-user-profiles-groups-and-communities';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo sprintf(__( "ProfileGrid Display Name work with ProfileGrid Plugin. You can install it  from <a href='%s'>Here</a>.", 'profilegrid-user-display-name' ),$installUrl); ?></p>
            </div>
            <?php
            $plugin = trim(basename(plugin_dir_path(dirname(__FILE__))) . '/profilegrid-display-name.php');
        deactivate_plugins($plugin);
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
        
        public function profile_magic_display_name_group_option($id,$group_options)
        {
            $dbhandler = new PM_DBhandler;
            if($dbhandler->get_global_option_value('pm_enable_display_name','0')==1):
             include 'partials/profilegrid-display-name-group-option.php';
            endif;
        }

}
