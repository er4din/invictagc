<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_User_Content
 * @subpackage Profilegrid_User_Content/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_User_Content
 * @subpackage Profilegrid_User_Content/admin
 * @author     Your Name <email@example.com>
 */
class Profilegrid_User_Content_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $profilegrid_user_content    The ID of this plugin.
	 */
	private $profilegrid_user_content;

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
	 * @param      string    $profilegrid_user_content       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_user_content, $version ) {

		$this->profilegrid_user_content = $profilegrid_user_content;
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
		 * defined in Profilegrid_User_Content_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_User_Content_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            if (class_exists('Profile_Magic') ) {
                wp_enqueue_style( $this->profilegrid_user_content, plugin_dir_url( __FILE__ ) . 'css/profilegrid-user-content-admin.css', array(), $this->version, 'all' );
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
		 * defined in Profilegrid_User_Content_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_User_Content_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            if (class_exists('Profile_Magic') ) {
                wp_enqueue_script( $this->profilegrid_user_content, plugin_dir_url( __FILE__ ) . 'js/profilegrid-user-content-admin.js', array( 'jquery' ), $this->version, false );
            }
	}
        public function display_user_roles()
        {
            $user_id = get_current_user_id();
            $user_info = get_userdata( $user_id );
            $user_roles = implode(', ', $user_info->roles);
            return $user_roles;
        }
        public function profilegrid_user_content_admin_menu()
	{
            if (class_exists('Profile_Magic') ) {
            $dbhandler = new PM_DBhandler;
            if($dbhandler->get_global_option_value('pm_enable_custom_post_tabs','0')=='1'):
                add_submenu_page("pm_manage_groups",__("Custom Tabs","profilegrid-custom-profile-tabs"),__("Custom Tabs","profilegrid-custom-profile-tabs"),"manage_options","pm_custom_profile_tabs",array( $this, 'pm_custom_profile_tabs' ));
                add_submenu_page("profilegrid_user_content_admin_menu_hide",__("Custom Tab","profilegrid-custom-profile-tabs"),__("Custom Tab","profilegrid-custom-profile-tabs"),"manage_options","pm_add_custom_tab",array( $this, 'pm_add_custom_tab' ));
            endif;
            add_submenu_page("profilegrid_user_content_admin_menu_hide",__("User Generated Data Settings","profilegrid-custom-profile-tabs"),__("User Generated Data Settings","profilegrid-custom-profile-tabs"),"manage_options","pm_user_content_settings",array( $this, 'pm_user_content_settings' ));
            }
            
        }
	
        public function pm_user_content_settings()
        {
            include 'partials/profilegrid-user-content-admin-display.php';
        }
        
        public function profilegrid_user_content_add_option_setting_page()
        {
            include 'partials/profilegrid-user-content-setting-option.php';
        }
        
        public function pm_custom_profile_tabs()
        {
            include 'partials/profilegrid-user-content-tabs-list.php';
        }
        
        public function pm_add_custom_tab()
        {
             include 'partials/profilegrid-user-content-add-tab.php';
        }

        public function profile_magic_user_content_notice_fun()
        {
            if (!class_exists('Profile_Magic') ) {
                    
                $this->User_Generated_data_installation();
                    //wp_die( "ProfileGrid Stripe won't work as unable to locate ProfileGrid plugin files." );
            }
            
        }
        
        public function User_Generated_data_installation()
        {
            $plugin_slug= 'profilegrid-user-profiles-groups-and-communities';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo sprintf(__( "Profilegrid Custom Profile Tabs works with ProfileGrid Plugin. You can install it  from <a href='%s'>Here</a>.", 'profilegrid-custom-profile-tabs'),$installUrl ); ?></p>
            </div>
            <?php
             $plugin = trim(basename(plugin_dir_path(dirname( __FILE__))).'/profilegrid-custom-profile-tabs.php'); 
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
        
        public function pm_custom_tabs_filters($pm_profile_tabs_status)
        {
             $pm_profile_tabs_status_array = maybe_unserialize($pm_profile_tabs_status);
            $dbhandler = new PM_DBhandler;
            $tabs =  $dbhandler->get_all_result('CUSTOMTABS');
            $newtabs = array();
            if($dbhandler->get_global_option_value('pm_enable_custom_post_tabs','0')==1 && !empty($tabs))
            {
                $i = 1;
                $check_ids = array();
                foreach($pm_profile_tabs_status_array as $oldtab)
                {
                    $check_ids[] =$oldtab['id'];
                }
                foreach($tabs as $tab)
                {
                    $id = sanitize_key($tab->tab_label).$i;
                    if(!in_array(sanitize_key($tab->tab_label).$i, $check_ids))
                    {
                        $pm_profile_tabs_status_array[$id] = array('id'=>$id,'title'=>__($tab->tab_label,'profilegrid-custom-profile-tabs'),'status'=>'1','class'=>$id);
                       
                    }
                     $i++;
                }
                
               
            }
            return $pm_profile_tabs_status_array;
        }

     public function profilegrid_user_content_maybe_create_tables() {
	if ( ! class_exists( 'Profile_Magic' )
		|| ! class_exists( 'Profilegrid_User_Content_Activator' )
		|| ! class_exists( 'PM_Helper_CUSTOMTABS' ) ) {
		return;
	}

	global $wpdb;
	$table_name = ( new PM_Helper_CUSTOMTABS() )->get_db_table_name( 'CUSTOMTABS' );
	$exists     = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) ) );

	if ( $exists !== $table_name ) {
		$activator = new Profilegrid_User_Content_Activator();
		$activator->create_table();
	}
}
        

}
