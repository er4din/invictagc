<?php

/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           ProfileGrid_Frontend_Group_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       ProfileGrid Advanced Group Manager
 * Plugin URI:        http://profilegrid.co
 * Description:       Offer more power and control to your Group Managers. They can edit Groups, approve membership requests, moderate blogs, manage users, etc. from a dedicated frontend Group management area.
 * Version:           2.3
 * Author:            profilegrid
 * Author URI:        http://profilegrid.co
 * License:           Commercial/ Proprietary
 * Text Domain:       profilegrid-frontend-group-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-profilegrid-admin-power-activator.php
 */
function activate_profilegrid_admin_power() {
	$pm_admin_power_activator = new Profilegrid_Admin_Power_Activator;
	$pm_admin_power_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profilegrid-admin-power-deactivator.php
 */
function deactivate_profilegrid_admin_power() {
        $pm_admin_power_deactivator = new Profilegrid_Admin_Power_Deactivator();
	$pm_admin_power_deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_profilegrid_admin_power' );
register_deactivation_hook( __FILE__, 'deactivate_profilegrid_admin_power' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-admin-power.php';


if(!class_exists('Profilegrid_Plugin_Updater'))
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-plugin-updater.php';
}

$key = 'Profilegrid_Admin_Power';

$license_status = get_option($key.'_license_status','');
if( ! empty( $license_status ) && $license_status == 'valid' ){
    add_action( 'init','Profilegrid_Admin_Power_plugin_updater' );
}

function Profilegrid_Admin_Power_plugin_updater()
{
    $key = 'Profilegrid_Admin_Power';
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
            'version' => '2.3',  // current version number
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
function run_profilegrid_admin_power() {

	$plugin = new Profilegrid_Admin_Power();
	$plugin->run();

}
run_profilegrid_admin_power();
