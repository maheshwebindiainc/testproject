<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.webindiaiinc.com
 * @since             1.0.0
 * @package           Money
 *
 * @wordpress-plugin
 * Plugin Name:       Money
 * Plugin URI:        www.webindiaiinc.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Money
 * Author URI:        www.webindiaiinc.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       money
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-money-activator.php
 */
function activate_money() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-money-activator.php';
	Money_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-money-deactivator.php
 */
function deactivate_money() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-money-deactivator.php';
	Money_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_money' );
register_deactivation_hook( __FILE__, 'deactivate_money' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-money.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_money() {

	$plugin = new Money();
	$plugin->run();

}
run_money();
