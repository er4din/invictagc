<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Instagram_Integration
 * @subpackage Profilegrid_Instagram_Integration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Instagram_Integration
 * @subpackage Profilegrid_Instagram_Integration/admin
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Instagram_Integration_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $profilegrid_instagram_integration    The ID of this plugin.
	 */
	private $profilegrid_instagram_integration;

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
	 * @param      string    $profilegrid_instagram_integration       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_instagram_integration, $version ) {

		$this->profilegrid_instagram_integration = $profilegrid_instagram_integration;
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
		 * defined in Profilegrid_Instagram_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Instagram_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            if (class_exists('Profile_Magic') ) {
                wp_enqueue_style( $this->profilegrid_instagram_integration, plugin_dir_url( __FILE__ ) . 'css/profilegrid-instagram-integration-admin.css', array(), $this->version, 'all' );
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
		 * defined in Profilegrid_Instagram_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Instagram_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            if (class_exists('Profile_Magic') ) {
                wp_enqueue_script( $this->profilegrid_instagram_integration, plugin_dir_url( __FILE__ ) . 'js/profilegrid-instagram-integration-admin.js', array( 'jquery' ), $this->version, false );
            }
	}
        
      
      
        public function profile_magic_instagram_notice_fun()
        {
            if (!class_exists('Profile_Magic') ) {
                    
                $this->instagram_installation();
                    
            }
            
        }
        
        public function instagram_installation()
        {
            $plugin_slug= 'profilegrid-user-profiles-groups-and-communities';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo sprintf(__( "ProfileGrid Instagram Integration work with ProfileGrid Plugin. You can install it  from <a href='%s'>Here</a>.", 'profilegrid-instagram-integration'),$installUrl ); ?></p>
            </div>
            <?php
            $plugin = trim(basename(plugin_dir_path(dirname( __FILE__))).'/profilegrid-instagram-integration.php');
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
        
         public function profilegrid_instagram_integration_admin_menu()
	{
                add_submenu_page("profilegrid_instagram_integration_admin_menu_hide",__("Instagram Settings","profilegrid-instagram-integration"),__("Instagram Settings","profilegrid-instagram-integration"),"manage_options","pm_instagram_settings",array( $this, 'pm_instagram_settings' ));
        }
	
        public function pm_instagram_settings()
        {
            include 'partials/profilegrid-instagram-admin-display.php';
        }
        
        public function profilegrid_instagram_add_option_setting_page()
        {
            include 'partials/profilegrid-instagram-setting-option.php';
        }
        
        public function pm_instagram_tabs_filters($pm_profile_tabs_status)
        {
            $dbhandler = new PM_DBhandler;
            $status = $dbhandler->get_global_option_value('pm_enable_instagram_integration','0');
            $check_ids = array();
            foreach($pm_profile_tabs_status as $oldtab)
            {
                $check_ids[] =$oldtab['id'];
            }
            if(!in_array('pg_instagram_integration_tab_content',$check_ids))
            {
                $pm_profile_tabs_status['pg_instagram_integration_tab_content'] = array('id'=>'pg_instagram_integration_tab_content','title'=>__('Instagram','profilegrid-instagram-integration'),'status'=>$status,'class'=>'');
            }
           
            
            return $pm_profile_tabs_status;
           
        }
       
        
    
       
}
