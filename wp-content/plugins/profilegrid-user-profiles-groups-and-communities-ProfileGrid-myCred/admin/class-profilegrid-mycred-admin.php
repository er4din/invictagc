<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Mycred
 * @subpackage Profilegrid_Mycred/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Mycred
 * @subpackage Profilegrid_Mycred/admin
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Mycred_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $profilegrid_mycred    The ID of this plugin.
	 */
	private $profilegrid_mycred;

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
	 * @param      string    $profilegrid_mycred       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_mycred, $version ) {

		$this->profilegrid_mycred = $profilegrid_mycred;
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
		 * defined in Profilegrid_Mycred_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Mycred_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            if (class_exists('Profile_Magic') ) {
                wp_enqueue_style( $this->profilegrid_mycred, plugin_dir_url( __FILE__ ) . 'css/profilegrid-mycred-admin.css', array(), $this->version, 'all' );
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
		 * defined in Profilegrid_Mycred_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Mycred_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            if (class_exists('Profile_Magic') ) {
                wp_enqueue_script( $this->profilegrid_mycred, plugin_dir_url( __FILE__ ) . 'js/profilegrid-mycred-admin.js', array( 'jquery' ), $this->version, false );
            }
	}
        
        public function profilegrid_mycred_admin_menu()
	{
                add_submenu_page("profilegrid_mycred_admin_menu_hide",__("Mycred Settings","profilegrid-mycred-integration"),__("Mycred Settings","profilegrid-mycred-integration"),"manage_options","pm_mycred_settings",array( $this, 'pm_mycred_settings' ));
        }
	
        public function pm_mycred_settings()
        {
               if ( !is_plugin_active( 'mycred/mycred.php' ) ) 
               {
                   ?>
                    <div class="notice notice-success">
                        <p><?php echo sprintf(__( "MyCRED is not installed. This extension requires installation of MyCRED extension from WordPress repository and at least one point type defined. <a href='%s' target='_blank'>Click here</a> to start installing MyCRED.", 'profilegrid-mycred-integration'),'https://wordpress.org/plugins/mycred/'); ?></p>
                    </div>
                    <?php
               }
               else
               {
                   if(is_mycred_ready())
                   {
                       include 'partials/profilegrid-mycred-admin-display.php';
                   }
                   else
                   {
                       ?>
                            <div class="notice notice-success">
                                <p><?php _e( "No Point Type is configured with MyCRED. Please define a Point Type to integrate with user profiles.", 'profilegrid-mycred-integration'); ?></p>
                            </div>
                       <?php
                   }
                   
               }
                   
            
        }
        
        public function profilegrid_mycred_add_option_setting_page()
        {
            include 'partials/profilegrid-mycred-setting-option.php';
        }
        
        public function profile_magic_mycred_notice_fun()
        {
            if (!class_exists('Profile_Magic') ) {
                    
                $this->Mycred_installation();
                    //wp_die( "ProfileGrid Stripe won't work as unable to locate ProfileGrid plugin files." );
            }
            
            if ( ! class_exists( 'myCRED_Core' )){
                $this->Mycred_installation2();
            }
        }
        
        public function Mycred_installation()
        {
            $plugin_slug= 'profilegrid-user-profiles-groups-and-communities';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo sprintf(__( "Profilegrid Mycred work with ProfileGrid Plugin. You can install it  from <a href='%s'>Here</a>.", 'profilegrid-mycred-integration'),$installUrl ); ?></p>
            </div>
            <?php
            $plugin = trim(basename(plugin_dir_path(dirname(__FILE__))) . '/profilegrid-mycred.php');
            deactivate_plugins($plugin);
            
        }
        
        public function Mycred_installation2()
        {
            $plugin_slug= 'mycred';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( "Since you have deactivated myCred, the ProfileGrid myCred Extension has been automatically deactivated. You will have to manually turn it on when you activate myCred.", 'profilegrid-mycred-integration' ); ?></p>
            </div>
            <?php
            $plugin = trim(basename(plugin_dir_path(dirname(__FILE__))) . '/profilegrid-mycred.php');
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
        
        public function profile_magic_mycred_group_option($id,$group_options)
        {
            $dbhandler = new PM_DBhandler;
            if($dbhandler->get_global_option_value('pm_enable_mycred','0')==1):
             include 'partials/profilegrid-mycred-group-option.php';
            endif;
        }
        
        public function profilegrid_add_references($list)
        {
             $dbhandler = new PM_DBhandler;
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_profile_image','0')==1):
                $list['pm_mycred_profile_image_points'] = __('User Uploads Profile Image','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_cover_image','0')==1):
                $list['pm_mycred_cover_image_points'] = __('User Uploads Cover Image','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_update_profile','0')==1):
                $list['pm_mycred_update_profile_points'] = __('User Updates Profile','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_approved','0')==1):
                $list['pm_mycred_user_approved_points'] = __('User is Approved by Group or Site Admin for Closed Group','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_blog_post_published','0')==1):
                $list['pm_mycred_user_blog_post_published_points'] = __('User Blog Post is Published','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_promoted_user_to_group_manager','0')==1):
               $list['pm_mycred_promoted_user_to_group_manager_points'] = __('User is Promoted to Group Manager','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_friend_request_approved','0')==1):
               $list['pm_mycred_promoted_friend_request_approved_points'] = __('User Friend Request is Approved','profilegrid-mycred-integration');
            endif;
            
            if(class_exists('Profilegrid_Group_photos') && $dbhandler->get_global_option_value('pm_mycred_enable_points_upload_group_photo','0')==1):
               $list['pm_mycred_upload_group_photo_points'] = __('User Uploads Group Photo','profilegrid-mycred-integration');
            endif;
            
            if(class_exists('Profilegrid_Group_Wall') && $dbhandler->get_global_option_value('pm_mycred_enable_points_post_on_group_wall','0')==1):
               $list['pm_mycred_post_on_group_wall_points'] = __('User Post on Group Wall','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_access_restricted_content','0')==1):
               $list['pm_mycred_user_access_restricted_content_points'] = __('User Access Restricted Content','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_leave_a_group','0')==1):
               $list['pm_mycred_user_leave_a_group_points'] = __('User Leaves a Group','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_remove_profile_image','0')==1):
               $list['pm_mycred_user_remove_profile_image_points'] = __('User Removes Profile Image','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_remove_cover_image','0')==1):
               $list['pm_mycred_user_remove_cover_image_points'] = __('User Removes Cover Image','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_friend_request_rejected','0')==1):
               $list['pm_mycred_friend_request_rejected_points'] = __('User Friend Request is Rejected','profilegrid-mycred-integration');
            endif;
            
            if($dbhandler->get_global_option_value('pm_mycred_enable_points_user_suspended','0')==1):
               $list['pm_mycred_user_suspended_points'] = __('User is Suspended','profilegrid-mycred-integration');
            endif;
            
            return $list;
        }
        
        public function pm_mycred_tabs_filters($pm_profile_tabs_status)
        {
            $dbhandler = new PM_DBhandler;
            $status = $dbhandler->get_global_option_value('pm_enable_mycred','0');
            $title = $dbhandler->get_global_option_value('pm_mycred_display_badges_tab_title','Badges');
            $check_ids = array();
            foreach($pm_profile_tabs_status as $oldtab)
            {
                $check_ids[] =$oldtab['id'];
            }
            if(!in_array('pg-mycred-badges',$check_ids))
            {
                $pm_profile_tabs_status['pg-mycred-badges'] = array('id'=>'pg-mycred-badges','title'=>__($title,'profilegrid-mycred-integration'),'status'=>$status,'class'=>'');
            }
           
            
            return $pm_profile_tabs_status;
           
        }

}
