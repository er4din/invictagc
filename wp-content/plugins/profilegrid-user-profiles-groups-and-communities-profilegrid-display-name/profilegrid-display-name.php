<?php

/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           ProfileGrid_Display_Name
 *
 * @wordpress-plugin
 * Plugin Name:       ProfileGrid User Display Name
 * Plugin URI:        http://profilegrid.co
 * Description:       Now take complete control of your users' display names. Mix and match patterns and add predefined suffixes and prefixes. There's a both global and per group option allowing display names in different groups stand out!.
 * Version:           2.1
 * Author:            profilegrid
 * Author URI:        http://profilegrid.co
 * License:           Commercial/ Proprietary
 * Text Domain:       profilegrid-user-display-name
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-profilegrid-display-name-activator.php
 */
function activate_profilegrid_display_name() {
	$pm_display_name_activator = new Profilegrid_Display_Name_Activator;
	$pm_display_name_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profilegrid-display-name-deactivator.php
 */
function deactivate_profilegrid_display_name() {
        $pm_display_name_deactivator = new Profilegrid_Display_Name_Deactivator();
	$pm_display_name_deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_profilegrid_display_name' );
register_deactivation_hook( __FILE__, 'deactivate_profilegrid_display_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-display-name.php';

if(!class_exists('Profilegrid_Plugin_Updater'))
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-plugin-updater.php';
}

$key = 'Profilegrid_Display_Name';

$license_status = get_option($key.'_license_status','');
if( ! empty( $license_status ) && $license_status == 'valid' ){
    add_action( 'init','Profilegrid_Display_Name_plugin_updater' );
}

function Profilegrid_Display_Name_plugin_updater()
{
    $key = 'Profilegrid_Display_Name';
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
            'version' => '2.1',  // current version number
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
function run_profilegrid_display_name() {

	$plugin = new Profilegrid_Display_Name();
	$plugin->run();

}
run_profilegrid_display_name();