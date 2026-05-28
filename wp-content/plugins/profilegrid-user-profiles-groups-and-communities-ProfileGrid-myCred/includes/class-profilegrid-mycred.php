<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Mycred
 * @subpackage Profilegrid_Mycred/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Profilegrid_Mycred
 * @subpackage Profilegrid_Mycred/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Mycred {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Profilegrid_Mycred_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $profilegrid_mycred    The string used to uniquely identify this plugin.
	 */
	protected $profilegrid_mycred;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->profilegrid_mycred = 'profilegrid-mycred-integration';
		$this->version = '2.0.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Profilegrid_Mycred_Loader. Orchestrates the hooks of the plugin.
	 * - Profilegrid_Mycred_i18n. Defines internationalization functionality.
	 * - Profilegrid_Mycred_Admin. Defines all hooks for the admin area.
	 * - Profilegrid_Mycred_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-mycred-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-mycred-i18n.php';
                require_once plugin_dir_path(  dirname( __FILE__ )) . 'includes/class-profilegrid-mycred-activator.php';
                require_once plugin_dir_path( dirname( __FILE__ )   ) . 'includes/class-profilegrid-mycred-deactivator.php';
                require_once plugin_dir_path( dirname( __FILE__ )   ) . 'includes/class-profilegrid-mycred-functions.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-profilegrid-mycred-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-profilegrid-mycred-public.php';

		
                $this->loader = new Profilegrid_Mycred_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Profilegrid_Mycred_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Profilegrid_Mycred_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Profilegrid_Mycred_Admin( $this->get_profilegrid_mycred(), $this->get_version() );
                
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
                $this->loader->add_action( 'admin_menu', $plugin_admin, 'profilegrid_mycred_admin_menu' );
                $this->loader->add_action( 'profile_magic_setting_option', $plugin_admin, 'profilegrid_mycred_add_option_setting_page' );
                $this->loader->add_action( 'admin_notices', $plugin_admin, 'profile_magic_mycred_notice_fun' );
                $this->loader->add_action( 'network_admin_notices', $plugin_admin, 'profile_magic_mycred_notice_fun' );
                //$this->loader->add_action( 'profile_magic_group_option', $plugin_admin, 'profile_magic_mycred_group_option',10,2 );
                $this->loader->add_action('wpmu_new_blog', $plugin_admin, 'activate_sitewide_plugins');
                $this->loader->add_filter( 'mycred_all_references',$plugin_admin, 'profilegrid_add_references' );
                $this->loader->add_filter('pm_profile_tabs', $plugin_admin, 'pm_mycred_tabs_filters');
                
        }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
            $plugin_public = new Profilegrid_Mycred_Public( $this->get_profilegrid_mycred(), $this->get_version() );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
            //$this->loader->add_action('profile_magic_profile_tab',$plugin_public, 'pg_show_badge_tab',10,2);
            //$this->loader->add_action('profile_magic_profile_tab_content',$plugin_public, 'pg_show_badge_tab_content',10,2);
            $this->loader->add_action('profile_magic_profile_settings_tab', $plugin_public,'pg_points_tab',10,2);
            $this->loader->add_action('profile_magic_profile_settings_tab_content', $plugin_public,'pg_points_tab_content',10,2);
            
            $this->loader->add_filter('mycred_badge_user_value', $plugin_public,'pg_new_badge_awarded_notification',10,3);

            
            $this->loader->add_action('pm_update_profile_image',$plugin_public,'pm_update_mycred_points_on_update_profile_image',10,1);
            $this->loader->add_action('pm_remove_profile_image',$plugin_public,'pm_update_mycred_points_on_remove_profile_image',10,1);
            $this->loader->add_action('pm_remove_cover_image',$plugin_public,'pm_update_mycred_points_on_remove_cover_image',10,1);
            $this->loader->add_action('pm_update_cover_image',$plugin_public,'pm_update_mycred_points_on_update_cover_image',10,1);
            $this->loader->add_action('pm_update_user_profile',$plugin_public,'pm_update_mycred_points_on_update_user_profile',10,1);
            $this->loader->add_action('profile_magic_join_group_additional_process',$plugin_public,'pm_update_mycred_points_on_join_group',10,2);
            $this->loader->add_action('publish_profilegrid_blogs', $plugin_public,'pm_update_mycred_points_on_user_blog_post_published',10,2);
            $this->loader->add_action('pm_assign_group_manager_privilege', $plugin_public,'pm_update_mycred_points_on_user_promoted_group_manager',10,2);
            $this->loader->add_action('pm_friend_request_accepted', $plugin_public,'pm_update_mycred_points_on_user_friend_request_accepted',10,2);
            $this->loader->add_action('pg_user_upload_group_photo', $plugin_public,'pm_update_mycred_points_on_user_upload_group_photo');
            $this->loader->add_action('publish_pg_groupwalls', $plugin_public,'pm_update_mycred_points_on_user_groupwall_post_published',10,2);
            $this->loader->add_action('pg_user_leave_group', $plugin_public,'pm_update_mycred_points_on_user_leave_group',10,2);
            $this->loader->add_action('pm_friend_request_rejected', $plugin_public,'pm_update_mycred_points_on_user_friend_request_rejected',10,2);
            $this->loader->add_action('pg_user_suspended', $plugin_public,'pm_update_mycred_points_on_user_suspended',10,1);
            $this->loader->add_action('profile_magic_show_additional_header_info2',$plugin_public,'profile_magic_show_rank_and_points',10,1);
            $this->loader->add_action('wp_ajax_pm_load_mycred_log',$plugin_public,'pm_load_mycred_log');
            $this->loader->add_action('profile_magic_profile_tab_link',$plugin_public, 'profile_magic_profile_tab_link_fun',10,5);
            $this->loader->add_action('profile_magic_profile_tab_extension_content',$plugin_public, 'profile_magic_profile_tab_extension_content_fun',10,5);
            
            
        }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_profilegrid_mycred() {
		return $this->profilegrid_mycred;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Profilegrid_Mycred_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}