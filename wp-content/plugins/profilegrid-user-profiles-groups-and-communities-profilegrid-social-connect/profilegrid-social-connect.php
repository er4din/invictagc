<?php

/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           Profilegrid_Social_Connect
 *
 * @wordpress-plugin
 * Plugin Name:       ProfileGrid Social Login
 * Plugin URI:        http://profilegrid.co
 * Description:       Register and Login users using their social accounts. Allows them to connect or disconnect their social accounts with ProfileGrid.
 * Version:           3.3
 * Author:            profilegrid
 * Author URI:        http://profilegrid.co
 * License:           Commercial/ Proprietary
 * Text Domain:       profilegrid-social-connect
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-profilegrid-social-connect-activator.php
 */
function activate_profilegrid_social_connect()
{
    $pm_social_connect_activator = new Profilegrid_Social_Connect_Activator;
    $pm_social_connect_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profilegrid-social-connect-deactivator.php
 */
function deactivate_profilegrid_social_connect()
{
    $pm_social_connect_deactivator = new Profilegrid_Social_Connect_Deactivator();
    $pm_social_connect_deactivator->deactivate();
}

register_activation_hook(__FILE__, 'activate_profilegrid_social_connect');
register_deactivation_hook(__FILE__, 'deactivate_profilegrid_social_connect');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-profilegrid-social-connect.php';


if (!class_exists('Profilegrid_Plugin_Updater')) {
    require_once plugin_dir_path(__FILE__) . 'includes/class-profilegrid-plugin-updater.php';
}

$key = 'Profilegrid_Social_Connect';

$license_status = get_option($key . '_license_status', '');
if (! empty($license_status) && $license_status == 'valid') {
    add_action('init', 'Profilegrid_Social_Connect_plugin_updater');
}

function Profilegrid_Social_Connect_plugin_updater()
{
    $key = 'Profilegrid_Social_Connect';
    $doing_cron = defined('DOING_CRON') && DOING_CRON;
    if (! current_user_can('manage_options') && ! $doing_cron) {
        return;
    }

    // retrieve our license key from the global settings
    $license_key = get_option($key . '_license_key');
    $item_id = get_option($key . '_item_id');
    $site_url = 'https://profilegrid.co/';
    // setup the updater
    $profilegrid_updater = new Profilegrid_Plugin_Updater(
        $site_url,
        __FILE__,
        array(
            'version' => '3.3',  // current version number
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
function run_profilegrid_social_connect()
{

    $plugin = new Profilegrid_Social_Connect();
    $plugin->run();
}
run_profilegrid_social_connect();
