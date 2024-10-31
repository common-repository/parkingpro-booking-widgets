<?php

/**
 *
 * @link              https://www.parkingpro.nl
 * @since             1.0.0
 * @package           ParkingPro_Booking_Widgets
 *
 * @wordpress-plugin
 * Plugin Name:       ParkingPro Booking Widgets
 * Plugin URI:        https://www.parkingpro.nl
 * Description:       ParkingPro Booking Widgets plugin: easily add a booking widget or shortcode to your website.
 * Version:           1.2.46
 * Author:            ParkingPro
 * Author URI:        https://www.parkingpro.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       parkingpro-booking-widgets
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
define( 'PARKINGPRO_BOOKING_WIDGETS_VERSION', '1.2.46' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-parkingpro-booking-widgets-activator.php
 */
function activate_parkingpro_booking_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parkingpro-booking-widgets-activator.php';
	ParkingPro_Booking_Widgets_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-parkingpro-booking-widgets-deactivator.php
 */
function deactivate_parkingpro_booking_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-parkingpro-booking-widgets-deactivator.php';
	ParkingPro_Booking_Widgets_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_parkingpro_booking_widgets' );
register_deactivation_hook( __FILE__, 'deactivate_parkingpro_booking_widgets' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-parkingpro-booking-widgets.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_parkingpro_booking_widgets() {

	$plugin = new ParkingPro_Booking_Widgets();
	$plugin->run();

}
run_parkingpro_booking_widgets();
