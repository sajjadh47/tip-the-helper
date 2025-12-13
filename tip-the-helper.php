<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Tip_The_Helper
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       Tip the Helper
 * Plugin URI:        https://wordpress.org/plugins/tip-the-helper/
 * Description:       Add a Tipping Feature for Drivers, Servers, or Any Service Provider in WooCommerce
 * Version:           1.0.1
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tip-the-helper
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'TIP_THE_HELPER_PLUGIN_VERSION', '1.0.1' );

/**
 * Define Plugin Folders Path
 */
define( 'TIP_THE_HELPER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'TIP_THE_HELPER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'TIP_THE_HELPER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tip-the-helper-activator.php
 *
 * @since    1.0.0
 */
function tipth_on_activate_tip_the_helper() {
	require_once TIP_THE_HELPER_PLUGIN_PATH . 'includes/class-tip-the-helper-activator.php';

	Tip_The_Helper_Activator::on_activate();
}

register_activation_hook( __FILE__, 'tipth_on_activate_tip_the_helper' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tip-the-helper-deactivator.php
 *
 * @since    1.0.0
 */
function tipth_on_deactivate_tip_the_helper() {
	require_once TIP_THE_HELPER_PLUGIN_PATH . 'includes/class-tip-the-helper-deactivator.php';

	Tip_The_Helper_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'tipth_on_deactivate_tip_the_helper' );

/**
 * The core plugin class that is used to define admin-specific and public-facing hooks.
 *
 * @since    1.0.0
 */
require TIP_THE_HELPER_PLUGIN_PATH . 'includes/class-tip-the-helper.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function tipth_run_tip_the_helper() {
	$plugin = new Tip_The_Helper();

	$plugin->run();
}

tipth_run_tip_the_helper();
