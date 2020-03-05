<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/nickmortensen
 * @since             1.0.0
 * @package           Jones_Multi
 *
 * @wordpress-plugin
 * Plugin Name:       Jones Multisite Plugin
 * Plugin URI:        https://github.com/nickmortensen/jones-multisite
 * Description:       Plugin to centralize the disparate location based jonessign.com websites.
 * Version:           1.0.0
 * Author:            Nick Mortensen
 * Author URI:        https://github.com/nickmortensen
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jones-multi
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
define( 'JONES_MULTI_VERSION', '1.0.0' );

/**
 * Get the CMB2 bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/includes/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/includes/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/includes/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/includes/CMB2/init.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jones-multi-activator.php
 */
function activate_jones_multi() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jones-multi-activator.php';
	Jones_Multi_Activator::activate();
	flush_rewrite_rules();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jones-multi-deactivator.php
 */
function deactivate_jones_multi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jones-multi-deactivator.php';
	Jones_Multi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jones_multi' );
register_deactivation_hook( __FILE__, 'deactivate_jones_multi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-jones-multi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_jones_multi() {

	$plugin = new Jones_Multi();
	$plugin->run();

}

run_jones_multi();
