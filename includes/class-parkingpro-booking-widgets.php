<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.parkingpro.nl
 * @since      1.0.0
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/includes
 * @author     ParkingPro <info@parkingpro.nl>
 */
class ParkingPro_Booking_Widgets {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      ParkingPro_Booking_Widgets_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $parkingpro_booking_widgets    The string used to uniquely identify this plugin.
	 */
	protected $parkingpro_booking_widgets;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PARKINGPRO_BOOKING_WIDGETS_VERSION' ) ) {
			$this->version = PARKINGPRO_BOOKING_WIDGETS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->parkingpro_booking_widgets = 'parkingpro-booking-widgets';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_widget_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - ParkingPro_Booking_Widgets_Loader. Orchestrates the hooks of the plugin.
	 * - ParkingPro_Booking_Widgets_i18n. Defines internationalization functionality.
	 * - ParkingPro_Booking_Widgets_Admin. Defines all hooks for the admin area.
	 * - ParkingPro_Booking_Widgets_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-parkingpro-booking-widgets-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-parkingpro-booking-widgets-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-parkingpro-booking-widgets-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-parkingpro-booking-widgets-public.php';

		/**
		 * The class responsible for defining the Widget class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-parkingpro-booking-widgets-widget.php';

		$this->loader = new ParkingPro_Booking_Widgets_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the ParkingPro_Booking_Widgets_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new ParkingPro_Booking_Widgets_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new ParkingPro_Booking_Widgets_Admin( $this->get_parkingpro_booking_widgets(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );
		
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new ParkingPro_Booking_Widgets_Public( $this->get_parkingpro_booking_widgets(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	    $this->loader->add_shortcode( 'pp_booking_widget', $plugin_public, 'pp_booking_shortcode' );
	    $this->loader->add_shortcode( 'pp_booking_iframe', $plugin_public, 'pp_booking_iframe_shortcode' );
	    $this->loader->add_shortcode( 'pp_account_registration_iframe', $plugin_public, 'pp_booking_iframe_account_registration_shortcode' );
	    $this->loader->add_shortcode( 'pp_account_login_iframe', $plugin_public, 'pp_booking_iframe_account_login_shortcode' );
	    $this->loader->add_shortcode( 'pp_parking_rates_iframe', $plugin_public, 'pp_booking_iframe_parking_rates_shortcode' );
	    $this->loader->add_shortcode( 'pp_product_selection', $plugin_public, 'pp_product_selection_shortcode' );
	    $this->loader->add_shortcode( 'pp_thank_you', $plugin_public, 'pp_thank_you_shortcode' );

	    $this->loader->add_action( 'wp_ajax_nopriv_pp_get_prices', $plugin_public, 'pp_get_prices' );
    	$this->loader->add_action( 'wp_ajax_pp_get_prices', $plugin_public, 'pp_get_prices' );
	}

	/**
     * Register all of the hooks related to the widget functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_widget_hooks() {

        $plugin_widget = new ParkingPro_Booking_Widgets_Widget( $this->get_parkingpro_booking_widgets(), $this->get_version() );

    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_parkingpro_booking_widgets() {
		return $this->parkingpro_booking_widgets;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    ParkingPro_Booking_Widgets_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the plugin variable names.
	 *
	 * @since     1.0.0
	 * @return    array    List of available plugin variable names
	 */
	public function get_variable_names() {

		$variable_names = [
	    	[
	    		'meta_key' => 'parkingpro_booking_widgets_myparkingpro_url',
	    		'short' => 'myparkingpro_url',
	    		'default_value' => '',
	    		'sanitizer' => 'esc_url_raw'
	    	],
			[
				'meta_key' => 'parkingpro_booking_widgets_display_services',
				'short' => 'display_services',
				'default_value' => 'display',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_booking_page',
				'short' => 'booking_page',
				'default_value' => '',
				'sanitizer' => 'esc_url_raw'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_products_page',
				'short' => 'products_page',
				'default_value' => '',
				'sanitizer' => 'esc_url_raw'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_thankyou_page',
				'short' => 'thankyou_page',
				'default_value' => '',
				'sanitizer' => 'esc_url_raw'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_form_title',
				'short' => 'form_title',
				'default_value' => __("Welcome", "parkingpro-booking-widgets"),
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_size',
				'short' => 'size',
				'default_value' => 'large',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_colors_header_background',
				'short' => 'colors_header_background',
				'default_value' => '',
				'sanitizer' => 'sanitize_hex_color'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_colors_header_text',
				'short' => 'colors_header_text',
				'default_value' => '',
				'sanitizer' => 'sanitize_hex_color'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_colors_section_text',
				'short' => 'colors_section_text',
				'default_value' => '',
				'sanitizer' => 'sanitize_hex_color'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_colors_label_text',
				'short' => 'colors_label_text',
				'default_value' => '',
				'sanitizer' => 'sanitize_hex_color'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_colors_button_background',
				'short' => 'colors_button_background',
				'default_value' => '',
				'sanitizer' => 'sanitize_hex_color'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_colors_button_text',
				'short' => 'colors_button_text',
				'default_value' => '',
				'sanitizer' => 'sanitize_hex_color'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_colors_button_hover_background',
				'short' => 'colors_button_hover_background',
				'default_value' => '',
				'sanitizer' => 'sanitize_hex_color'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_colors_button_hover_text',
				'short' => 'colors_button_hover_text',
				'default_value' => '',
				'sanitizer' => 'sanitize_hex_color'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_colors_border',
				'short' => 'colors_border',
				'default_value' => '',
				'sanitizer' => 'sanitize_hex_color'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_section_dates_title',
				'short' => 'section_dates_title',
				'default_value' => __("Parking and return date", "parkingpro-booking-widgets"),
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_section_services_title',
				'short' => 'section_services_title',
				'default_value' => __("Services", "parkingpro-booking-widgets"),
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_button_text',
				'short' => 'button_text',
				'default_value' => __("Book now", "parkingpro-booking-widgets"),
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_label_parkingdate',
				'short' => 'label_parkingdate',
				'default_value' => __("Parking date and time", "parkingpro-booking-widgets"),
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_label_returndate',
				'short' => 'label_returndate',
				'default_value' => __("Return date and time", "parkingpro-booking-widgets"),
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_filter_services',
				'short' => 'filter_services',
				'default_value' => '',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_culture',
				'short' => 'culture',
				'default_value' => '',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_display_car_dropdown',
				'short' => 'display_car_dropdown',
				'default_value' => 'hide',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_section_car_title',
				'short' => 'section_car_title',
				'default_value' => __("Cars", "parkingpro-booking-widgets"),
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_display_airport_dropdown',
				'short' => 'display_airport_dropdown',
				'default_value' => 'hide',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_section_airport_title',
				'short' => 'section_airport_title',
				'default_value' => __("Airport", "parkingpro-booking-widgets"),
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_widget_destination',
				'short' => 'widget_destination',
				'default_value' => 'booking',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_iframe_destination',
				'short' => 'iframe_destination',
				'default_value' => 'iframe',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_google_analytics_id',
				'short' => 'google_analytics_id',
				'default_value' => '',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_force_single_service',
				'short' => 'force_single_service',
				'default_value' => 'false',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_remember_widget_fields',
				'short' => 'remember_widget_fields',
				'default_value' => 'false',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_affiliate_tracking',
				'short' => 'affiliate_tracking',
				'default_value' => 'hide',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_header_element_selector',
				'short' => 'header_element_selector',
				'default_value' => '',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_partnerid',
				'short' => 'partnerid',
				'default_value' => '',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_append_google_analytics_cookie',
				'short' => 'append_google_analytics_cookie',
				'default_value' => 'no',
				'sanitizer' => 'sanitize_text_field'
			],
			[
				'meta_key' => 'parkingpro_booking_widgets_hide_times',
				'short' => 'hide_times',
				'default_value' => 'no',
				'sanitizer' => 'sanitize_text_field'
			],
	    ];

		return $variable_names;
	}

}
