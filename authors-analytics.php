<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/nachovz
 * @since             1.0.0
 * @package           Authors_Analytics
 *
 * @wordpress-plugin
 * Plugin Name:       Authors Analytics
 * Plugin URI:        https://github.com/nachovz/authors-analytics
 * Description:       This plugins shows the analytics statistics for posts of each user. It also includes an Admin page which shows all user's analytics (per page).
 * Version:           1.0.0
 * Author:            Ignacio Cordoba
 * Author URI:        https://github.com/nachovz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       authors-analytics
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
define( 'AUTHORS_ANALYTICS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-authors-analytics-activator.php
 */
function activate_authors_analytics() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-authors-analytics-activator.php';
	Authors_Analytics_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-authors-analytics-deactivator.php
 */
function deactivate_authors_analytics() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-authors-analytics-deactivator.php';
	Authors_Analytics_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_authors_analytics' );
register_deactivation_hook( __FILE__, 'deactivate_authors_analytics' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-authors-analytics.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_authors_analytics() {

	$plugin = new Authors_Analytics();
	$plugin->run();

}
run_authors_analytics();
