<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.parkingpro.nl
 * @since      1.0.0
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/includes
 * @author     ParkingPro <info@parkingpro.nl>
 */
class ParkingPro_Booking_Widgets_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'parkingpro-booking-widgets',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
