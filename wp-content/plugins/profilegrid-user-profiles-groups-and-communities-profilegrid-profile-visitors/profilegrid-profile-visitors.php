<?php

/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           Profilegrid_profile_visitor_details
 *
 * @wordpress-plugin
 * Plugin Name:       ProfileGrid Profile Visitors
 * Plugin URI:        http://profilegrid.co
 * Description:       Enable users to see who visited their profile with detailed visitor information, enhancing engagement and interaction. Track and display profile visitors directly on the user profile page.
 * Version:           1.4
 * Author:            profilegrid
 * Author URI:        http://profilegrid.co
 * License:           Commercial/ Proprietary
 * Text Domain:       profilegrid-profile-visitor-details
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PROFILEGRID_PROFILE_VISITORS', '1.4' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-profilegrid-profile-visitor-details-activator.php
 */
function activate_Profilegrid_profile_visitor_details() {
	$pm_woocommerce_activator = new Profilegrid_profile_visitor_details_Activator;
	$pm_woocommerce_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profilegrid-profile-visitor-details-deactivator.php
 */
function deactivate_Profilegrid_profile_visitor_details() {
        $pm_woocommerce_deactivator = new Profilegrid_profile_visitor_details_Deactivator();
	$pm_woocommerce_deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_Profilegrid_profile_visitor_details' );
register_deactivation_hook( __FILE__, 'deactivate_Profilegrid_profile_visitor_details' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-profile-visitor-details.php';

if(!class_exists('Profilegrid_Plugin_Updater'))
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-plugin-updater.php';
}

$key = 'Profilegrid_profile_visitor_details';

$license_status = get_option($key.'_license_status','');
if( ! empty( $license_status ) && $license_status == 'valid' ){
    add_action( 'init','Profilegrid_Profile_Visitor_plugin_updater' );
}

function Profilegrid_Profile_Visitor_plugin_updater()
{
    $key = 'Profilegrid_profile_visitor_details';
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
            'version' => PROFILEGRID_PROFILE_VISITORS,  // current version number
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
function run_Profilegrid_profile_visitor_details() {

	$plugin = new Profilegrid_profile_visitor_details();
	$plugin->run();

}
run_Profilegrid_profile_visitor_details();