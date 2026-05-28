<?php

/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           Profilegrid_User_Content
 *
 * @wordpress-plugin
 * Plugin Name:       ProfileGrid Custom User Profile Tabs
 * Plugin URI:        http://profilegrid.co
 * Description:       Add personalized tabs to user profiles to suit your business or industry. Add user authored content from any custom post type, fetch content using shortcodes or insert static content. Open doors to endless possibilities - Integrate user profiles with other plugins supporting custom post or shortcode format.
 * Version:           2.9
 * Author:            profilegrid
 * Author URI:        http://profilegrid.co
 * License:           Commercial/ Proprietary
 * Text Domain:       profilegrid-custom-profile-tabs
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-profilegrid-user-content-activator.php
 */
function activate_profilegrid_user_content() {
	$pm_user_content_activator = new Profilegrid_User_Content_Activator;
	$pm_user_content_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profilegrid-user-content-deactivator.php
 */
function deactivate_profilegrid_user_content() {
        $pm_user_content_deactivator = new Profilegrid_User_Content_Deactivator();
	$pm_user_content_deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_profilegrid_user_content' );
register_deactivation_hook( __FILE__, 'deactivate_profilegrid_user_content' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-user-content.php';

if(!class_exists('Profilegrid_Plugin_Updater'))
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-plugin-updater.php';
}

$key = 'Profilegrid_User_Content';

$license_status = get_option($key.'_license_status','');
if( ! empty( $license_status ) && $license_status == 'valid' ){
    add_action( 'init','Profilegrid_User_Content_plugin_updater' );
}

/**
 * Ensure the custom tabs table exists even if activation hook was skipped.
 */

function Profilegrid_User_Content_plugin_updater()
{
    $key = 'Profilegrid_User_Content';
    $doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
    if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
        return;
    }

    // retrieve our license key from the global settings
    $license_key = get_option($key.'_license_key');
    $item_id = get_option($key.'_item_id');
    $site_url = 'https://profilegrid.co/';
    // setup the updater
    $profilegrid_updater = new Profilegrid_Plugin_Updater(
        $site_url,
        __FILE__,
        array(
            'version' => '2.9',  // current version number
            'license' => $license_key,  // license key
            'item_id' => $item_id,       // ID of the product
            'author'  => 'profilegrid', // author of this plugin
            'beta'    => false,
        ),
        $key
    );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_profilegrid_user_content() {

	$plugin = new Profilegrid_User_Content();
	$plugin->run();

}
run_profilegrid_user_content();
