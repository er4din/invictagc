<?php

/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           Profilegrid_Instagram_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       ProfileGrid Instagram Integration
 * Plugin URI:        http://profilegrid.co
 * Description:       Show Instagram tab on User Profile page with user’s Instagram photos displayed in the tab.
 * Version:           2.3
 * Author:            profilegrid
 * Author URI:        http://profilegrid.co
 * License:           Commercial/ Proprietary
 * Text Domain:       profilegrid-instagram-integration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/*WC HPOS compatibility*/
add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-profilegrid-instagram-integration-activator.php
 */
function activate_profilegrid_instagram_integration() {
	$pm_instagram_activator = new Profilegrid_Instagram_Integration_Activator;
	$pm_instagram_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profilegrid-instagram-integration-deactivator.php
 */
function deactivate_profilegrid_instagram_integration() {
        $pm_instagram_deactivator = new Profilegrid_Instagram_Integration_Deactivator();
	$pm_instagram_deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_profilegrid_instagram_integration' );
register_deactivation_hook( __FILE__, 'deactivate_profilegrid_instagram_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-instagram-integration.php';


if(!class_exists('Profilegrid_Plugin_Updater'))
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-plugin-updater.php';
}

$key = 'Profilegrid_Instagram_Integration';

$license_status = get_option($key.'_license_status','');
if( ! empty( $license_status ) && $license_status == 'valid' ){
    add_action( 'init','Profilegrid_Instagram_Integration_plugin_updater' );
}

function Profilegrid_Instagram_Integration_plugin_updater()
{
    $key = 'Profilegrid_Instagram_Integration';
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
function run_profilegrid_instagram_integration() {

	$plugin = new Profilegrid_Instagram_Integration();
	$plugin->run();

}
run_profilegrid_instagram_integration();
