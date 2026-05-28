<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Social_Connect
 * @subpackage Profilegrid_Social_Connect/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_Social_Connect
 * @subpackage Profilegrid_Social_Connect/admin
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Social_Connect_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $profilegrid_social_connect    The ID of this plugin.
	 */
	private $profilegrid_social_connect;

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
	 * @param      string    $profilegrid_social_connect       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_social_connect, $version ) {

		$this->profilegrid_social_connect = $profilegrid_social_connect;
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
		 * defined in Profilegrid_Social_Connect_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Social_Connect_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->profilegrid_social_connect, plugin_dir_url( __FILE__ ) . 'css/profilegrid-social-connect-admin.css', array(), $this->version, 'all' );

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
		 * defined in Profilegrid_Social_Connect_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_Social_Connect_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->profilegrid_social_connect, plugin_dir_url( __FILE__ ) . 'js/profilegrid-social-connect-admin.js', array( 'jquery' ), $this->version, false );

	}
        
        public function profilegrid_social_connect_admin_menu()
        {
           add_submenu_page("profilegrid_social_connect_admin_menu_hide",__("Social Connect Settings","profilegrid-social-connect"),__("Social Connect Settings","profilegrid-social-connect"),"manage_options","pm_social_connect_settings",array( $this, 'pm_social_connect_settings' ));
         }

       	
        public function pm_social_connect_settings()
        {
            include 'partials/profilegrid-social-connect-admin-display.php';
        }
        
        public function profilegrid_social_connect_add_option_setting_page()
        {
            include 'partials/profilegrid-social-connect-setting-option.php';
        }
        
        public function profile_magic_social_connect_notice_fun()
        {
            if (!class_exists('Profile_Magic') ) {
                $this->Social_Connect_installation();
                    //wp_die( "ProfileGrid Stripe won't work as unable to locate ProfileGrid plugin files." );
            }
        }
        
        function pg_load_social_widget()
        {
            register_widget('profilegrid_social_login');
        }
        
        public function Social_Connect_installation()
        {
            $plugin_slug= 'profilegrid-user-profiles-groups-and-communities';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo sprintf(__( "Profilegrid Social Connect work with ProfileGrid Plugin. You can install it  from <a href='%s'>Here</a>.", 'profilegrid-social-connect'),$installUrl ); ?></p>
            </div>
            <?php
           // deactivate_plugins('profilegrid-user-profiles-groups-and-communities-profilegrid-social-connect/profilegrid-social-connect.php'); 
         $plugin = trim(basename(plugin_dir_path(dirname( __FILE__))).'/profilegrid-social-connect.php');
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
        
        public function pg_social_filter_html()
        { 
            $connections = array('facebook','google','twitter','linkedin'); ?>
            <div class="sb-filter"> <?php _e("Connected via:",'profilegrid-social-connect');
            foreach($connections as $connection)
            {
        ?>
                <div class="filter-row">
                <input type="radio" class="sel_pm_user_status" name="connection" value="<?php echo $connection; ?>" <?php if(isset($_GET['connection']) && $_GET['connection']== $connection) echo 'checked="checked"';?>>
            <?php echo ucfirst($connection);?> 
                </div>                
           
        <?php } ?>
                 </div>
                 
      <?php   }
}
