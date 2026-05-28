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
 * @package    Profilegrid_profile_visitor_details
 * @subpackage Profilegrid_profile_visitor_details/includes
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
 * @package    Profilegrid_profile_visitor_details
 * @subpackage Profilegrid_profile_visitor_details/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_profile_visitor_details {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Profilegrid_profile_visitor_details_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $Profilegrid_profile_visitor_details    The string used to uniquely identify this plugin.
	 */
	protected $Profilegrid_profile_visitor_details;

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

		$this->Profilegrid_profile_visitor_details = 'profilegrid-profile-visitor-details';
                if (defined('PROFILEGRID_PROFILE_VISITORS')) {
                    $this->version = PROFILEGRID_PROFILE_VISITORS;
		} else {
                    $this->version = '1.0';
		}
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
	 * - Profilegrid_profile_visitor_details_Loader. Orchestrates the hooks of the plugin.
	 * - Profilegrid_profile_visitor_details_i18n. Defines internationalization functionality.
	 * - Profilegrid_profile_visitor_details_Admin. Defines all hooks for the admin area.
	 * - Profilegrid_profile_visitor_details_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-profile-visitor-details-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-profile-visitor-details-i18n.php';
                require_once plugin_dir_path(  dirname( __FILE__ )) . 'includes/class-profilegrid-profile-visitor-details-activator.php';
                require_once plugin_dir_path( dirname( __FILE__ )   ) . 'includes/class-profilegrid-profile-visitor-details-deactivator.php';
                require_once plugin_dir_path( dirname( __FILE__ )   ) . 'includes/class-profilegrid-profile-visitor-details-helper.php';
				require_once plugin_dir_path( dirname( __FILE__ )   ) . 'includes/class-profilegrid-profile-visitor-details-controler.php'; 
				require_once plugin_dir_path( dirname( __FILE__ )   ) . 'includes/class-profilegrid-profile-visitor-details-allowed-html-wp-kses.php';  
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-profilegrid-profile-visitor-details-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-profilegrid-profile-visitor-details-public.php';

		
                $this->loader = new Profilegrid_profile_visitor_details_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Profilegrid_profile_visitor_details_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Profilegrid_profile_visitor_details_i18n();
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

		$plugin_admin = new Profilegrid_profile_visitor_details_Admin( $this->get_Profilegrid_profile_visitor_details(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
                $this->loader->add_action( 'admin_menu', $plugin_admin, 'Profilegrid_profile_visitor_details_admin_menu', 999 );
                $this->loader->add_action( 'profile_magic_setting_option', $plugin_admin, 'Profilegrid_profile_visitor_details_add_option_setting_page' );
                $this->loader->add_action( 'admin_notices', $plugin_admin, 'profile_magic_profile_visitor_notifications' );
                $this->loader->add_action( 'network_admin_notices', $plugin_admin, 'profile_magic_profile_visitor_notifications' );
                $this->loader->add_action('wpmu_new_blog', $plugin_admin, 'activate_sitewide_plugins');
                $this->loader->add_action('profilegrid_dashboard_member_profile_top_menus', $plugin_admin, 'profilegrid_profile_visitor_top_menu');
                $this->loader->add_action('profilegrid_dashboard_member_profile_top_menus_content', $plugin_admin, 'profilegrid_dashboard_member_profile_visitor_content');
                $this->loader->add_action('wp_ajax_pm_dashboard_display_detailed_report',$plugin_admin, 'pm_dashboard_display_detailed_report',10,2);
                $this->loader->add_action('wp_ajax_profilegrid_dashboard_profile_visitor_list',$plugin_admin, 'profilegrid_dashboard_profile_visitor_list',10,2);
                $this->loader->add_action('wp_ajax_pm_reset_visitor_counter',$plugin_admin, 'pm_reset_visitor_counter',10,2);
				$this->loader->add_action('admin_init', $plugin_admin, 'pm_setup_cron_job');
				$this->loader->add_action('cleanup_old_visit_logs', $plugin_admin, 'pm_perform_cleanup');
				// $this->loader->add_filter('cron_schedules', $plugin_admin, 'my_custom_cron_schedule');
        }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
            $plugin_public = new Profilegrid_profile_visitor_details_Public( $this->get_Profilegrid_profile_visitor_details(), $this->get_version() );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        // $this->loader->add_action( 'init', $plugin_public, 'get_visitor_details' );
           $this->loader->add_action( 'wp', $plugin_public, 'pm_update_page_visit_stats' );
           $this->loader->add_action('profile_magic_profile_settings_tab', $plugin_public,'pg_visitors_details_tab',10,2);
           $this->loader->add_action('profile_magic_profile_settings_tab_content', $plugin_public,'pg_visitors_details_tab_content',10,2);
           $this->loader->add_action('wp_ajax_pm_display_detailed_report',$plugin_public, 'pm_display_detailed_report',10,2);
           $this->loader->add_action('wp_ajax_pm_display_visitor_list',$plugin_public, 'pm_display_visitor_list',10,2);
           $this->loader->add_action('wp_ajax_pm_reset_profile_visitor_counter',$plugin_public, 'pm_reset_profile_visitor_counter',10,2);
		   $this->loader->add_action('wp_ajax_pm_update_tracking_status', $plugin_public, 'pm_update_tracking_status');
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
	public function get_Profilegrid_profile_visitor_details() {
		return $this->Profilegrid_profile_visitor_details;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Profilegrid_profile_visitor_details_Loader    Orchestrates the hooks of the plugin.
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