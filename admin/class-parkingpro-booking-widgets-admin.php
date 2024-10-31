<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.parkingpro.nl
 * @since      1.0.0
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/admin
 * @author     ParkingPro <info@parkingpro.nl>
 */
class ParkingPro_Booking_Widgets_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $parkingpro_booking_widgets    The ID of this plugin.
	 */
	private $parkingpro_booking_widgets;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $parkingpro_booking_widgets       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $parkingpro_booking_widgets, $version ) {

		$this->parkingpro_booking_widgets = $parkingpro_booking_widgets;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_style( $this->parkingpro_booking_widgets, plugin_dir_url( __FILE__ ) . 'css/parkingpro-booking-widgets-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_script( $this->parkingpro_booking_widgets, plugin_dir_url( __FILE__ ) . 'js/parkingpro-booking-widgets-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds a settings page link to a menu
	 *
	 * @link 		https://codex.wordpress.org/Administration_Menus
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function add_menu() {

		add_menu_page( 
			$page_title = 'ParkingPro Booking Widgets', 
			$menu_title = 'ParkingPro', 
			$capability = 'manage_options', 
			$menu_slug = 'parkingpro-booking-widgets', 
			$function = array( $this, 'page_settings' ),
			$icon_url = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj48c3ZnIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIHZpZXdCb3g9IjAgMCA2NTIgNTkyIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zOnNlcmlmPSJodHRwOi8vd3d3LnNlcmlmLmNvbS8iIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MS40MTQyMTsiPjxnPjxwYXRoIGQ9Ik00MzUuNTA4LDIzNi45MDhjMTkuMjk2LC0xOS4yOTYgMjguOTQ2LC00Mi41MzMgMjguOTQ2LC02OS43MTJjMCwtMjYuOTA0IC05LjY1LC01MC4wNzEgLTI4Ljk0NiwtNjkuNTA0Yy0xOS4yOTYsLTE5LjQzNCAtNDIuNTMzLC0yOS4xNSAtNjkuNzA4LC0yOS4xNWwtOTguMjU0LDBsMCwxOTcuMzEybDk4LjI1NCwwYzI3LjE3NSwwIDUwLjQxMiwtOS42NSA2OS43MDgsLTI4Ljk0NloiIHN0eWxlPSJmaWxsOiNmZmY7ZmlsbC1ydWxlOm5vbnplcm87Ii8+PHBhdGggZD0iTTU1MC4xMTIsMjkuNTQyYzMwLjY3MSwzOS43OTEgNDYuMDEzLDg1LjY3MSA0Ni4wMTMsMTM3LjY1NGMwLDYzLjMyOSAtMjIuNTU0LDExNy40NzUgLTY3LjY2NywxNjIuNDU0Yy00NS4xMTYsNDQuOTc5IC05OS4zMzMsNjcuNDY3IC0xNjIuNjYyLDY3LjQ2N2wtOTguMjQ2LDBsMCwxODkuMzVjMTguOTA0LDMuNDEyIDM4LjM3NSw1LjIgNTguMjYyLDUuMmMxNzkuOTQ2LDAgMzI1LjgyMSwtMTQ1Ljg3MSAzMjUuODIxLC0zMjUuODEzYzAsLTkzLjAyOSAtMzguOTkxLC0xNzYuOTQ2IC0xMDEuNTIxLC0yMzYuMzEyWiIgc3R5bGU9ImZpbGw6I2ZmZjtmaWxsLXJ1bGU6bm9uemVybzsiLz48L2c+PGNsaXBQYXRoIGlkPSJfY2xpcDEiPjxwYXRoIGQ9Ik0wLjcxNywyNjUuOTIxYzAsMTA4LjYzMyA1My4yOTEsMjA0LjgwOCAxMzUuMTU0LDI2My44NDZsMCwtNTI3LjdjLTgxLjg2Myw1OS4wMzcgLTEzNS4xNTQsMTU1LjIxNiAtMTM1LjE1NCwyNjMuODU0WiIvPjwvY2xpcFBhdGg+PGcgY2xpcC1wYXRoPSJ1cmwoI19jbGlwMSkiPjxwYXRoIGQ9Ik0tNTIuNjc1LC0yMjcuOTA0bDAsOTUwLjU0NmwzODkuNzUsMGwwLC0zMTYuMjI5bDAsLTEuODU1bDAsLTMxNC4zNzlsMCwtMS44NTRsMCwtMzE2LjIyOWwtMzg5Ljc1LDBaIiBzdHlsZT0iZmlsbDojZmZmO2ZpbGwtcnVsZTpub256ZXJvOyIvPjwvZz48L3N2Zz4='
		);

	}

	/**
	 * Retrieve all services for a MyParkingPro URL
	 *
	 * @since     1.0.0
	 * @param     string   $myparkingpro_url       The MyParkingPro URL.
	 * @return    array    List of services.
	 */
	public function get_myparkingpro_services($myparkingpro_url) {
		
		try {
			
			$services_list = [];

			$data = wp_remote_get( $myparkingpro_url . '/api/widget/locations' );

			if( !is_array($data) )
				throw new Exception("Problem while requesting locations from API", 1);

	        $array = json_decode($data['body'], true);

	        if( $array && $array['data'] && count( $array['data'] ) > 0 ) {
	            
	            foreach( $array['data'] as $service ) {

	                array_push($services_list, $service);

	            }

	        }

	        return $services_list;
		} 
		catch (Exception $e) {
		
			error_log($e);
			return [];

		}

	}

	/**
	 * Creates the settings page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_settings() {

		if( !current_user_can('manage_options') ) {

	        wp_die('Unauthorized user');

	    }

	    $ParkingPro_Booking_Widgets = new ParkingPro_Booking_Widgets();

	    $variable_names = $ParkingPro_Booking_Widgets->get_variable_names();

	    foreach( $variable_names as $var ) 
	    {
	    	
	    	$meta_key = $var['meta_key'];
	    	
	    	// If the var is posted, save it
	    	if( isset($_POST[ $meta_key ]) ) {

	    		if( $meta_key === 'parkingpro_booking_widgets_filter_services' ) 
	    			$_POST[ $meta_key ] = implode(',', $_POST[ $meta_key ]);

	    		if( $meta_key === 'parkingpro_booking_widgets_myparkingpro_url' )
	    			$_POST[ $meta_key ] = rtrim($_POST[ $meta_key ], "/");

	    		// Sanitize the input data
	    		$sanitized_value = call_user_func($var['sanitizer'], $_POST[ $meta_key ]);

		        update_option($meta_key, $sanitized_value);
		        
		    }
		    elseif( $_POST && $meta_key === 'parkingpro_booking_widgets_filter_services' ) {

		    	update_option('parkingpro_booking_widgets_filter_services', []);

		    }

		    // Read the value from database and set in variable
		    ${"$meta_key"} = get_option($meta_key, $var['default_value']);

	    }

		include( plugin_dir_path( __FILE__ ) . 'partials/parkingpro-booking-widgets-admin-display.php' );

	}

}
