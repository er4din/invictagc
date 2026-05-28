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
 * @package    Profilegrid_Social_Connect
 * @subpackage Profilegrid_Social_Connect/includes
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
 * @package    Profilegrid_Social_Connect
 * @subpackage Profilegrid_Social_Connect/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Social_Connect
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Profilegrid_Social_Connect_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $profilegrid_social_connect    The string used to uniquely identify this plugin.
	 */
	protected $profilegrid_social_connect;

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
	public function __construct()
	{

		$this->profilegrid_social_connect = 'profilegrid-social-connect';
		$this->version = '1.0.0';
		$this->load_services();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_services()
	{

		require_once plugin_dir_path(dirname(__FILE__)) . 'services/google/pg-social-login-google.php';

		//        require_once plugin_dir_path( dirname( __FILE__ ) ). 'services/twitter/pg-social-login-twitter.php';

	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Profilegrid_Social_Connect_Loader. Orchestrates the hooks of the plugin.
	 * - Profilegrid_Social_Connect_i18n. Defines internationalization functionality.
	 * - Profilegrid_Social_Connect_Admin. Defines all hooks for the admin area.
	 * - Profilegrid_Social_Connect_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-profilegrid-social-connect-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-profilegrid-social-connect-i18n.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-profilegrid-social-connect-activator.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-profilegrid-social-connect-deactivator.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-profilegrid-social-connect-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-profilegrid-social-connect-public.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'widgets/profilegrid-social-login.php';



		$this->loader = new Profilegrid_Social_Connect_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Profilegrid_Social_Connect_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{
		$plugin_i18n = new Profilegrid_Social_Connect_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Profilegrid_Social_Connect_Admin($this->get_profilegrid_social_connect(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('widgets_init', $plugin_admin, 'pg_load_social_widget');
		$this->loader->add_action('admin_menu', $plugin_admin, 'profilegrid_social_connect_admin_menu');
		$this->loader->add_action('profile_magic_setting_option', $plugin_admin, 'profilegrid_social_connect_add_option_setting_page');
		$this->loader->add_action('admin_notices', $plugin_admin, 'profile_magic_social_connect_notice_fun');
		$this->loader->add_action('network_admin_notices', $plugin_admin, 'profile_magic_social_connect_notice_fun');
		$this->loader->add_action('wpmu_new_blog', $plugin_admin, 'activate_sitewide_plugins');
		$this->loader->add_action('pg_social_filter', $plugin_admin, 'pg_social_filter_html');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		$plugin_public = new Profilegrid_Social_Connect_Public($this->get_profilegrid_social_connect(), $this->get_version());


		$this->loader->add_action('init', $plugin_public, 'pgStartSession', 1);
		$this->loader->add_action('init', $plugin_public, 'pg_social_connect_register_shortcode');
		$this->loader->add_action('wp_logout', $plugin_public, 'pgEndSession');
		$this->loader->add_action('wp_login', $plugin_public, 'pgEndSession');

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('profile_magic_profile_settings_tab', $plugin_public, 'pg_social_connect_tab', 10, 2);
		$this->loader->add_action('profile_magic_profile_settings_tab_content', $plugin_public, 'pg_social_connect_tab_content', 10, 2);
		$this->loader->add_action('profile_magic_before_profile-magic-registration-form', $plugin_public, 'pg_get_social_buttons', 10, 2);
		$this->loader->add_action('profile_magic_before_profile-magic-login-form', $plugin_public, 'pg_get_social_buttons', 10, 2);
		$this->loader->add_action('profile_magic_social_login_widget', $plugin_public, 'pg_get_social_widget', 10);
		$this->loader->add_action('pg_social_registration', $plugin_public, 'pg_social_registration_process', 10, 2);
		$this->loader->add_action('pg_add_social_connection', $plugin_public, 'pg_add_social_connection_process', 10, 2);
		$this->loader->add_filter('get_avatar', $plugin_public, 'pg_get_social_avatar', 1000000000000, 4);
		$this->loader->add_action('wp_ajax_pg_social_update_user_connections', $plugin_public, 'pg_social_update_user_connections');
		$this->loader->add_action('profile_magic_registration_process', $plugin_public, 'pm_submit_user_registration_social', 10, 7);
		$this->loader->add_action('wp_ajax_nopriv_pg_save_temp_login_data', $plugin_public, 'pg_save_temp_login_data');
		$this->loader->add_action('wp_ajax_pg_save_temp_login_data', $plugin_public, 'pg_save_temp_login_data');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_profilegrid_social_connect()
	{
		return $this->profilegrid_social_connect;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Profilegrid_Social_Connect_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
