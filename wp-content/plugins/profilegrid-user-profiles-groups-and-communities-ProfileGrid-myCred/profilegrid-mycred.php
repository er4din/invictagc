<?php

/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           Profilegrid_Mycred
 *
 * @wordpress-plugin
 * Plugin Name:       ProfileGrid myCred Integration
 * Plugin URI:        http://profilegrid.co
 * Description:       Integrate popular points system for WordPress with ProfileGrid to reward your users. Display ranks and badges on user profile pages, give incentive for activities on site or penalize based on pre-set rules.
 * Version:           2.1
 * Author:            profilegrid
 * Author URI:        http://profilegrid.co
 * License:           Commercial/ Proprietary
 * Text Domain:       profilegrid-mycred-integration
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 4.9.2
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
 * This action is documented in includes/class-profilegrid-mycred-activator.php
 */
function activate_profilegrid_mycred() {
	$pm_mycred_activator = new Profilegrid_Mycred_Activator;
	$pm_mycred_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profilegrid-mycred-deactivator.php
 */
function deactivate_profilegrid_mycred() {
        $pm_mycred_deactivator = new Profilegrid_Mycred_Deactivator();
	$pm_mycred_deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_profilegrid_mycred' );
register_deactivation_hook( __FILE__, 'deactivate_profilegrid_mycred' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-mycred.php';


if(!class_exists('Profilegrid_Plugin_Updater'))
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-plugin-updater.php';
}

$key = 'Profilegrid_Mycred';

$license_status = get_option($key.'_license_status','');
if( ! empty( $license_status ) && $license_status == 'valid' ){
    add_action( 'init','Profilegrid_Mycred_plugin_updater' );
}

function Profilegrid_Mycred_plugin_updater()
{
    $key = 'Profilegrid_Mycred';
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
function run_profilegrid_mycred() {

	$plugin = new Profilegrid_Mycred();
	$plugin->run();

}
run_profilegrid_mycred();