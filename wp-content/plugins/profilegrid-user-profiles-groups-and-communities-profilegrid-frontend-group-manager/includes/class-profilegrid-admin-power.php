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
 * @package    Profilegrid_Admin_Power
 * @subpackage Profilegrid_Admin_Power/includes
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
 * @package    Profilegrid_Admin_Power
 * @subpackage Profilegrid_Admin_Power/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Admin_Power {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Profilegrid_Admin_Power_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $profilegrid_admin_power    The string used to uniquely identify this plugin.
	 */
	protected $profilegrid_admin_power;

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

		$this->profilegrid_admin_power = 'profilegrid-frontend-group-manager';
		$this->version = '2.1';

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
	 * - Profilegrid_Admin_Power_Loader. Orchestrates the hooks of the plugin.
	 * - Profilegrid_Admin_Power_i18n. Defines internationalization functionality.
	 * - Profilegrid_Admin_Power_Admin. Defines all hooks for the admin area.
	 * - Profilegrid_Admin_Power_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-admin-power-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-admin-power-i18n.php';
                require_once plugin_dir_path(  dirname( __FILE__ )) . 'includes/class-profilegrid-admin-power-activator.php';
                require_once plugin_dir_path( dirname( __FILE__ )   ) . 'includes/class-profilegrid-admin-power-deactivator.php';
                
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-profilegrid-admin-power-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-profilegrid-admin-power-public.php';

		
                $this->loader = new Profilegrid_Admin_Power_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Profilegrid_Admin_Power_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Profilegrid_Admin_Power_i18n();
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

		$plugin_admin = new Profilegrid_Admin_Power_Admin( $this->get_profilegrid_admin_power(), $this->get_version() );
                $this->loader->add_action( 'profile_magic_group_option_footer', $plugin_admin, 'profile_magic_admin_power_group_option',10,2 );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'profile_magic_admin_power_notice_fun' );
                $this->loader->add_action( 'network_admin_notices', $plugin_admin, 'profile_magic_admin_power_notice_fun' );
                $this->loader->add_action('wpmu_new_blog', $plugin_admin, 'activate_sitewide_plugins');
        }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
            $plugin_public = new Profilegrid_Admin_Power_Public( $this->get_profilegrid_admin_power(), $this->get_version() );
            $this->loader->add_action('profile_magic_group_photos_tab',$plugin_public, 'pg_show_group_setting_tab',100,2);
            $this->loader->add_action('profile_magic_group_photos_tab_content',$plugin_public, 'pm_show_group_setting_content',100,2);
            $this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
            $this->loader->add_filter( 'pg_edit_user_profile_user_id', $plugin_public, 'pg_check_edit_user_profile_user_id',10,2 );
            
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
	public function get_profilegrid_admin_power() {
		return $this->profilegrid_admin_power;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Profilegrid_Admin_Power_Loader    Orchestrates the hooks of the plugin.
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