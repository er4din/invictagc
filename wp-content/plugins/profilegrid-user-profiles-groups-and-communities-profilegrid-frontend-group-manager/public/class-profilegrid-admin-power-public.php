<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Admin_Power
 * @subpackage Profilegrid_Admin_Power/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Admin_Power
 * @subpackage Profilegrid_Admin_Power/public
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Admin_Power_Public {

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
     * Core ProfileGrid plugin slug for shared helpers/templates.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $profile_magic
     */
    private $profile_magic;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $profilegrid_admin_power       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_admin_power, $version ) {

		$this->profilegrid_admin_power = $profilegrid_admin_power;
		$this->version = $version;
        $this->profile_magic = 'profilegrid-user-profiles-groups-and-communities';

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->profilegrid_admin_power, plugin_dir_url( __FILE__ ) . 'css/profilegrid-admin-power-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
            
                wp_enqueue_script( $this->profilegrid_admin_power, plugin_dir_url( __FILE__ ) . 'js/profilegrid-admin-power-public.js', array( 'jquery' ), $this->version, false );
             
	}
       
        public function pg_show_group_setting_tab($uid,$gid)
        {
            $dbhandler = new PM_DBhandler;
            $pmrequests = new PM_request;
            $current_user = wp_get_current_user();
            $group_leaders = $pmrequests->pg_get_group_leaders($gid);
            if(is_user_logged_in() && ( in_array($current_user->ID,$group_leaders) || is_super_admin()) && $dbhandler->get_global_option_value('pm_show_group_settings_tab','1')=='1' && !class_exists("Profilegrid_Credit")):?>
            <li class="pg-group-setting pm-profile-tab pm-pad10"><a class="pm-dbfr" href="#pg_group_setting"><?php _e('Settings','profilegrid-user-profiles-groups-and-communities');?></a></li>
            <?php endif;
        }
        
        public function pm_show_group_setting_content($uid,$gid)
        {
            $dbhandler = new PM_DBhandler;
            $pmrequests = new PM_request;
            $pm_profile_magic_public = new Profile_Magic_Public($this->profilegrid_admin_power,$this->version);
            $pmhtmlcreator = new PM_HTML_Creator($this->profilegrid_admin_power,$this->version);
            $group_leaders = $pmrequests->pg_get_group_leaders($gid);
            $current_user = wp_get_current_user();
            $group_type = $pmrequests->profile_magic_get_group_type($gid);
            if(is_user_logged_in() && ( in_array($current_user->ID,$group_leaders) || is_super_admin()) && $dbhandler->get_global_option_value('pm_show_group_settings_tab','1')=='1' && !class_exists("Profilegrid_Credit")):
            include 'group-settings-html.php';
            endif;
        }
        
        public function register_shortcodes()
        {
            add_shortcode( 'profilegrid_group_settings', array( $this, 'profile_magic_shortcode_group_settings' ) );
        }
        
        public function pg_check_edit_user_profile_user_id($user_id,$post)
        {
            if(isset($post['gid']) && !empty($post['gid']))
            {
                $pmrequests = new PM_request;
                $group_leaders = $pmrequests->pg_get_group_leaders($post['gid']);
                if(isset($post['euid']) && is_user_logged_in() && ( in_array($user_id,$group_leaders) || is_super_admin()))
                {
                    $is_group_member = $pmrequests->profile_magic_check_is_group_member($post['gid'],$post['euid']);
                    if($is_group_member)
                    {
                        $user_id = $post['euid'];
                    }
                }
            }
            return $user_id;
        }
        
        public function profile_magic_shortcode_group_settings($content)
        {
            $dbhandler = new PM_DBhandler;
            $pmrequests = new PM_request;
            $pm_activator = new Profile_Magic_Activator;
            $identifier = 'GROUPS';
            if(isset($_POST['remove_image']))
            {
                    $retrieved_nonce = filter_input(INPUT_POST,'_wpnonce');
                    if (!wp_verify_nonce($retrieved_nonce, 'save_pm_edit_group' ) ) die( __('Failed security check','profilegrid-user-profiles-groups-and-communities') );
                    $groupid = filter_input(INPUT_POST,'group_id');

                    if($groupid!=0)
                    {
                            $data = array('group_icon'=>'');
                            $arg = array('%d');
                        $dbhandler->update_row($identifier,'id',$groupid,$data,$arg,'%d');
                    }
                    $redirect_url = $pmrequests->profile_magic_get_frontend_url('pm_group_page','',$groupid);
                    $redirect_url = add_query_arg('gid',$groupid,$redirect_url);
                    wp_redirect( esc_url_raw( $redirect_url ) );
                    exit;

            }
            
            if(isset($_POST['edit_group']))
            {

                    $retrieved_nonce = filter_input(INPUT_POST,'_wpnonce');
                    if (!wp_verify_nonce($retrieved_nonce, 'save_pm_edit_group' ) ) die( __('Failed security check','profilegrid-user-profiles-groups-and-communities') );
                    $groupid = filter_input(INPUT_POST,'group_id');
                    $exclude = array("_wpnonce","_wp_http_referer","edit_group","group_id");
                    $post = $pmrequests->sanitize_request($_POST,$identifier,$exclude);
                    $filefield = $_FILES['group_icon'];
                    $allowed_ext ='jpg|jpeg|png|gif';
                    if(isset($filefield) && !empty($filefield))
                    {
                            $attachment_id = $pmrequests->make_upload_and_get_attached_id($filefield,$allowed_ext);
                            $post['group_icon'] = $attachment_id;
                    }

                    if($post!=false)
                    {
                            foreach($post as $key=>$value)
                            {
                              $data[$key] = $value;
                              $arg[] = $pm_activator->get_db_table_field_type($identifier,$key);
                            }
                    }
                    if($groupid!=0)
                    {
                        $dbhandler->update_row($identifier,'id',$groupid,$data,$arg,'%d');
                    }
                    $redirect_url = $pmrequests->profile_magic_get_frontend_url('pm_group_page','',$groupid);
                    $redirect_url = add_query_arg('gid',$groupid,$redirect_url);
                    wp_redirect( get_permalink() );
                    //exit;	
            }
            ob_start();
            $default_attributes = array( 'gid' => 1  );
            $attributes = shortcode_atts( $default_attributes, $content );
            $gid = $attributes['gid'];
            ?>
            <div class="pmagic">
                 
            <?php
            $this->pm_show_group_setting_content('',$gid);
            ?>
            
               </div>
            <div class="pm-popup-mask"></div>    

<div id="pm-edit-group-popup" style="display: none;">
    <div class="pm-popup-container" id="pg_edit_group_html_container">
     
        
    </div>
</div>
        <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
}
