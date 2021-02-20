<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wich.tech
 * @since             1.0.
 * @package           Wc_Restrict_Stock
 *
 * @wordpress-plugin
 * Plugin Name:       Restrict Stock for WooCommerce
 * Description:       A small extension written to make it possible to restrict quantities of stock purchased in a single order with WooCommerce.
 * Version:           1.0.0
 * Author:            Eric Wich
 * Author URI:        https://wich.tech
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-restrict-stock
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WC_RESTRICT_STOCK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-restrict-stock-activator.php
 */
function activate_wc_restrict_stock() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-restrict-stock-activator.php';
	Wc_Restrict_Stock_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-restrict-stock-deactivator.php
 */
function deactivate_wc_restrict_stock() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-restrict-stock-deactivator.php';
	Wc_Restrict_Stock_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_restrict_stock' );
register_deactivation_hook( __FILE__, 'deactivate_wc_restrict_stock' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-restrict-stock.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_restrict_stock() {

	$plugin = new Wc_Restrict_Stock();
	$plugin->run();

}
run_wc_restrict_stock();
