<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.parkingpro.nl
 * @since      1.0.0
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/public
 * @author     ParkingPro <info@parkingpro.nl>
 */
class ParkingPro_Booking_Widgets_Public {

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
	 * @param      string    $parkingpro_booking_widgets       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $parkingpro_booking_widgets, $version ) {

		$this->parkingpro_booking_widgets = $parkingpro_booking_widgets;
		$this->version = $version;

		add_action( 'widgets_init', function() {

		    $widget = new ParkingPro_Booking_Widgets_Widget( $this->parkingpro_booking_widgets, $this->version );
		    \register_widget( $widget );
		});

		if (!session_id())
    		session_start();

		// Store referrer in session
		if( isset($_SERVER['HTTP_REFERER']) )
		{
			$referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);

			if(substr($referer_host, 0 - strlen($_SERVER['HTTP_HOST'])) !== $_SERVER['HTTP_HOST']) 
				$_SESSION['parkingpro_http_referer'] = $_SERVER['HTTP_REFERER'];
		}

		$affiliate_tracking = get_option('parkingpro_booking_widgets_affiliate_tracking');

		if( $affiliate_tracking && $affiliate_tracking === 'tradedoubler' && isset($_GET['tduid']) && $_GET['tduid'] !== '' )
		{
			$tduid 		 = $_GET['tduid'];
			$expire_days = 60;
			$cookie_name = 'TRADEDOUBLER';
			$path 		 = "/";

			setcookie($cookie_name, $tduid, time() + $expire_days * 24 * 60 * 60, $path);
		}

		session_write_close();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$wp_scripts = wp_scripts();
		
		wp_enqueue_style( 'jquery-ui-theme-smoothness', sprintf( '//ajax.googleapis.com/ajax/libs/jqueryui/%s/themes/smoothness/jquery-ui.css', $wp_scripts->registered['jquery-ui-core']->ver) );
		wp_enqueue_style( 'jquery-timepicker', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'lightslider', plugin_dir_url( __FILE__ ) . 'css/lightslider.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->parkingpro_booking_widgets, plugin_dir_url( __FILE__ ) . 'css/parkingpro-booking-widgets-public.css', array('jquery-timepicker'), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-timepicker', plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'lightslider', plugin_dir_url( __FILE__ ) . 'js/lightslider.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->parkingpro_booking_widgets, plugin_dir_url( __FILE__ ) . 'js/parkingpro-booking-widgets-public.js', array( 'jquery', 'jquery-timepicker' ), $this->version, false );
		wp_localize_script( $this->parkingpro_booking_widgets, 'parkingpro_booking_widgets', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

	/**
	 * Function to retrieve location price for given dates, used via AJAX in front-end
	 *
	 * @since    1.0.0
	 */
	public function pp_get_prices() {

		try 
		{
			if( !$_POST['location_id'] || !$_POST['arrivalDate'] || !$_POST['departureDate'] )
				return false;

			$data = $this->pp_get_location_price($_POST['location_id'], $_POST['arrivalDate'], $_POST['departureDate']);

	        echo json_encode($data);
			exit();
		} 
		catch (Exception $e) 
		{
			error_log($e);
			return [];
		}

	}

	/**
	 * Function to retrieve location price for given dates, used via AJAX in front-end
	 *
	 * @since    1.1.0
	 */
	public function pp_get_location_price($location_id, $arrivalDate, $departureDate) {

		try 
		{
			if( !$location_id || !$arrivalDate || !$departureDate )
				return false;

			$myparkingpro_url = get_option('parkingpro_booking_widgets_myparkingpro_url');

			if( !$myparkingpro_url )
				return false; 

			$url = $myparkingpro_url . '/api/widget/price?locationId=' . $location_id . '&arrivalDate=' . $arrivalDate . '&departureDate=' . $departureDate;
			$response = wp_remote_get( esc_url_raw($url) );

			if( !is_array( $response ) )
				throw new Exception("Error while loading prices", 1);

	        $array = json_decode($response['body'], true);

	        if( $array && $array['id'] ) {
	            
	            $data = [
	            	'currency' => $array['currency'],
	            	'isUnavailable' => $array['isUnavailable'],
	            	'locationStatus' => $array['locationStatus'],
	            	'totalWithTax' => $array['totalWithTax'],
	            	'totalDays' => $array['totalDays']
	            ];

	        }
	        else {

	        	$data = [];

	        }

	    	return $data;
		} 
		catch (Exception $e) 
		{
			error_log($e);
			return [];
		}

	}

	/**
	 * Add shortcode for booking widget.
	 *
	 * @since    1.0.0
	 */
	public function pp_booking_shortcode( $atts ) {

		$plugin = new ParkingPro_Booking_Widgets();

		$variable_names = $plugin->get_variable_names();

		foreach ($variable_names as $var) 
		{

			$meta_key = $var['meta_key'];
			$short = $var['short'];

			// Set variable via shortcode/widget attribute or load from default settings
			if (isset($atts[ $short ]) )
			    ${"$meta_key"} = $atts[ $short ];
			else
				${"$meta_key"} = get_option($meta_key, $var['default_value']);

			if( isset($parkingpro_booking_widgets_filter_services) && is_string($parkingpro_booking_widgets_filter_services) ) 
				$parkingpro_booking_widgets_filter_services = explode(',', $parkingpro_booking_widgets_filter_services);

		}

		$parkingpro_booking_widgets_myparkingpro_service_ids = [];

		// Retrieve list of services
	    if( $parkingpro_booking_widgets_myparkingpro_url && $parkingpro_booking_widgets_display_services === 'display' ) {
	    	$parkingpro_booking_widgets_myparkingpro_services = $this->get_myparkingpro_services($parkingpro_booking_widgets_myparkingpro_url);

	    	foreach($parkingpro_booking_widgets_myparkingpro_services as $location) 
	    	{
	    		array_push($parkingpro_booking_widgets_myparkingpro_service_ids, $location['id']);
	    	}
	    }
	    else {
	    	$parkingpro_booking_widgets_myparkingpro_services = [];
	    }

	    // Retrieve list of airports
	    if( $parkingpro_booking_widgets_myparkingpro_url && $parkingpro_booking_widgets_display_airport_dropdown === 'display' )
	    	$parkingpro_booking_widgets_airports = $this->get_myparkingpro_airports($parkingpro_booking_widgets_myparkingpro_url);
	    else
	    	$parkingpro_booking_widgets_airports = [];

	    if( isset( $atts['airport']) )
	    	$parkingpro_booking_widgets_fixed_airport = $atts['airport'];
	    else
	    	$parkingpro_booking_widgets_fixed_airport = NULL;

	    // Determine the dynamic form destination URL
	    if( isset($parkingpro_booking_widgets_widget_destination) && $parkingpro_booking_widgets_widget_destination === 'product_selection' && isset( $parkingpro_booking_widgets_products_page ) && $parkingpro_booking_widgets_products_page !== '' )
		    $form_destination_url = $parkingpro_booking_widgets_products_page;
		elseif( isset( $parkingpro_booking_widgets_booking_page ) && $parkingpro_booking_widgets_booking_page !== '' )
		    $form_destination_url = $parkingpro_booking_widgets_booking_page;
		else
		    $form_destination_url = $parkingpro_booking_widgets_myparkingpro_url . '/reservations/add';

		$parkingpro_booking_widgets_car_options = ['1', '2', '3', '4', '5'];

		$parkingpro_booking_widgets_config = $this->get_myparkingpro_config($parkingpro_booking_widgets_myparkingpro_url);

		return include( plugin_dir_path( __FILE__ ) . 'partials/parkingpro-booking-widgets-public-display.php' );

	}

	/**
	 * Add shortcode for booking iFrame.
	 *
	 * @since    1.0.0
	 */
	public function pp_booking_iframe_shortcode( $atts ) {

		$plugin = new ParkingPro_Booking_Widgets();

		$variable_names = $plugin->get_variable_names();

		foreach ($variable_names as $var) 
		{

			$meta_key = $var['meta_key'];
			$short = $var['short'];

			// Set variable via shortcode/widget attribute or load from default settings
			if (isset($atts[ $short ]) )
			    ${"$meta_key"} = $atts[ $short ];
			else
				${"$meta_key"} = get_option($meta_key, $var['default_value']);

			if( isset($parkingpro_booking_widgets_filter_services) && is_string($parkingpro_booking_widgets_filter_services) ) 
				$parkingpro_booking_widgets_filter_services = explode(',', $parkingpro_booking_widgets_filter_services);

		}

		if( !$parkingpro_booking_widgets_myparkingpro_url )
			return '';

		$iframe_url = $parkingpro_booking_widgets_myparkingpro_url . '/reservations/add?';

		if( isset($atts['hideheader']) && $atts['hideheader'] !== '' )
			$iframe_url .= '&hideHeader';

		if( isset($atts['hidetitle']) && $atts['hidetitle'] !== '' )
			$iframe_url .= '&hideTitle';

		if( isset($_GET['arrivalDate']) && $_GET['arrivalDate'] !== '' )
			$iframe_url .= '&arrivalDate=' . $_GET['arrivalDate'];
		
		if( isset($_GET['arrivalTime']) && $_GET['arrivalTime'] !== '' )
			$iframe_url .= '&arrivalTime=' . $_GET['arrivalTime'];

		if( isset($_GET['departureDate']) && $_GET['departureDate'] !== '' )
			$iframe_url .= '&departureDate=' . $_GET['departureDate'];

		if( isset($_GET['departureTime']) && $_GET['departureTime'] !== '' )
			$iframe_url .= '&departureTime=' . $_GET['departureTime'];

		if( isset($_GET['car']) && $_GET['car'] !== '' )
			$iframe_url .= '&startCarCount=' . $_GET['car'];

		if( isset($_GET['locationId']) && $_GET['locationId'] !== '' )
			$iframe_url .= '&locationId=' . $_GET['locationId'];

		if( isset($_GET['airport']) && $_GET['airport'] !== '' )
			$iframe_url .= '&airportId=' . $_GET['airport'];

		if( isset($_GET['visible_services']) && $_GET['visible_services'] !== '' ) {
			$iframe_url .= '&showLocations=' . $_GET['visible_services'];
		}
		elseif( $parkingpro_booking_widgets_force_single_service === 'true' && isset($_GET['locationId']) && $_GET['locationId'] !== '' ) {
			$iframe_url .= '&showLocations=' . $_GET['locationId'];
		}
		elseif( isset($_GET['showLocations']) && $_GET['showLocations'] !== '' ) {
			$iframe_url .= '&showLocations=' . $_GET['showLocations'];
		}
		elseif( isset($atts['showlocations']) && $atts['showlocations'] !== '' ) {
			$iframe_url .= '&showLocations=' . $atts['showlocations'];	
		}

		if( isset($atts['includelocations']) && $atts['includelocations'] !== '' ) {
			$iframe_url .= '&includeLocations=' . $atts['includelocations'];	
		}

		if( $parkingpro_booking_widgets_affiliate_tracking && $parkingpro_booking_widgets_affiliate_tracking === 'tradedoubler') 
		{
			if( isset($_COOKIE['TRADEDOUBLER']) && $_COOKIE['TRADEDOUBLER'] )
				$iframe_url .= '&tduid=' . $_COOKIE['TRADEDOUBLER'];
			elseif( isset($_GET['tduid']) && $_GET['tduid'] )
				$iframe_url .= '&tduid=' . $_GET['tduid'];
		}

		if( isset($atts['culture']) && $atts['culture'] !== '' ) {
			$iframe_url .= '&culture=' . $atts['culture'];
		}
		elseif( function_exists('icl_object_id') ) {
			if( ICL_LANGUAGE_CODE === 'nl' )
				$iframe_url .= '&culture=nl-NL';
			elseif( ICL_LANGUAGE_CODE === 'de' )
				$iframe_url .= '&culture=de-DE';
			else
				$iframe_url .= '&culture=en-GB';
		}
		else {
			$culture = get_option('parkingpro_booking_widgets_culture');

			if( isset($culture) && $culture !== "" )
				$iframe_url .= '&culture=' . $culture;
		}
		
		if( isset($_SESSION['parkingpro_http_referer']) )
			$iframe_url .= '&referrer=' . urlencode($_SESSION['parkingpro_http_referer']);

		if( isset($atts['partnerid']) && $atts['partnerid'] !== '' )
			$iframe_url .= '&partnerId=' . $atts['partnerid'];
		elseif( isset($partnerid) && $partnerid !== '' )
			$iframe_url .= '&partnerId=' . $partnerid;

		if( isset($_GET['mppdata']) && $_GET['mppdata'] !== "" )
			$iframe_url .= '&mppdata=' . esc_attr($_GET['mppdata']);

		if( isset($atts['query_string']) && $atts['query_string'] !== '' ) {
			$iframe_url = $this->pp_add_query_string_to_url($iframe_url, $atts['query_string']);
		}

		if( $parkingpro_booking_widgets_remember_widget_fields === 'true' )
			$this->pp_store_widget_data($_GET);

		if( isset($atts['height']) && $atts['height'] !== '' )
			$iframe_height = $atts['height'];
		else
			$iframe_height = 2200;

		if( isset($atts['width']) && $atts['width'] !== '' )
			$iframe_width = $atts['width'];
		else
			$iframe_width = '100%';

		$html = '<script> 

			document.addEventListener("scroll", function () {
				var iframe = document.getElementById("parkingpro_booking_widgets_iframe");
				var rect = iframe.getBoundingClientRect();
				var top_value = rect.top;

		';

		if( $parkingpro_booking_widgets_header_element_selector ) 
		{
			$html .= '
				var scrollAppendElements = jQuery("'. $parkingpro_booking_widgets_header_element_selector . '");

				var headerHeight = scrollAppendElements
					.map(function (x) { 
						return jQuery(this).outerHeight();
					})
					.get()
					.reduce(function (prev, curr) {
						return prev + curr;
					}, 0);
					
				if( headerHeight > 0 )
					top_value = rect.top - headerHeight;
			';
		}
				
		$html .= '
				iframe.contentWindow.postMessage({
					event: "parkingPro.widget.scroll",
					top: top_value
				}, "*");
			});

			window.addEventListener("message", function (e) { 
				if(e.data.event === "parkingPro.domain.reservationAdded") { ';
			
	    if( $parkingpro_booking_widgets_iframe_destination === 'thankyou_page' && $parkingpro_booking_widgets_thankyou_page !== null && $parkingpro_booking_widgets_thankyou_page !== '' ) {
	    	$html .= ' 
	    		var destination_url = "' . esc_url_raw($parkingpro_booking_widgets_thankyou_page) . '";
		    	if( e.data.reservation && e.data.reservation.location ) {
		    		destination_url += "?" + jQuery.param(e.data.reservation);
		    	}
		    	window.location.href = destination_url;
	    	';

	    	$iframe_url .= '&returnUrl=' . esc_url_raw($parkingpro_booking_widgets_thankyou_page);
	    }
	    else {
	    	$html .= ' 
	    		jQuery("html, body").animate({
	        		scrollTop: jQuery("#parkingpro_booking_widgets_iframe").offset().top
	        	}, 600); 
	        ';
	    }

	    $html .= '}

	    	if( e.data.event === "parkingPro.ui.pageHeightChanged" ) 
            {
                jQuery("#parkingpro_booking_widgets_iframe").height(e.data.newHeight + 50);
            }

			if ( e.data.event === "parkingPro.ui.scroll" )
			{
				var scrollAppendElements = jQuery("'. $parkingpro_booking_widgets_header_element_selector . '");

				var headerHeight = scrollAppendElements
					.map(function (x) { 
						return jQuery(this).outerHeight();
					})
					.get()
					.reduce(function (prev, curr) {
						return prev + curr;
					}, 0);

				jQuery("html, body").animate({
	        		scrollTop: jQuery("#parkingpro_booking_widgets_iframe").offset().top + e.data.scrollTop - headerHeight
	        	}, 600); 
			}
            
	        }, false);</script>';
        
        $html .= '<iframe id="parkingpro_booking_widgets_iframe" style="width: ' . esc_attr($iframe_width) . '; src="about:blank" padding: 0; border: 0;" width="' . esc_attr($iframe_width) . '" height="' . esc_attr($iframe_height) . '" frameborder="0" scrolling="yes"></iframe>';

        $google_analytics_id = get_option('parkingpro_booking_widgets_google_analytics_id');

        if( $google_analytics_id && strlen($google_analytics_id) > 0 )
	        $html .= $this->pp_booking_iframe_tracking($google_analytics_id, $iframe_url);
	    else
	    	$html .= $this->pp_booking_iframe_set_src($iframe_url);

        return $html;

	}

	/**
	 * Add shortcode for account registration iFrame.
	 *
	 * @since    1.2.5
	 */
	public function pp_booking_iframe_account_registration_shortcode( $atts ) {

		$myparkingpro_url = get_option('parkingpro_booking_widgets_myparkingpro_url');

		if( !$myparkingpro_url )
			return '';

		$iframe_url = $myparkingpro_url . '/account/register?';

		if( isset($atts['hideheader']) && $atts['hideheader'] !== '' )
			$iframe_url .= '&hideHeader';

		if( isset($atts['hidetitle']) && $atts['hidetitle'] !== '' )
			$iframe_url .= '&hideTitle';

		if( isset($atts['culture']) && $atts['culture'] !== '' ) {
			$iframe_url .= '&culture=' . $atts['culture'];
		}
		elseif( function_exists('icl_object_id') ) {
			if( ICL_LANGUAGE_CODE === 'nl' )
				$iframe_url .= '&culture=nl-NL';
			elseif( ICL_LANGUAGE_CODE === 'de' )
				$iframe_url .= '&culture=de-DE';
			else
				$iframe_url .= '&culture=en-GB';
		}
		else {
			$culture = get_option('parkingpro_booking_widgets_culture');

			if( isset($culture) && $culture !== "" )
				$iframe_url .= '&culture=' . $culture;
		}

		if( isset($_GET['mppdata']) && $_GET['mppdata'] !== "" )
			$iframe_url .= '&mppdata=' . esc_attr($_GET['mppdata']);

		if( isset($atts['query_string']) && $atts['query_string'] !== '' ) {
			$iframe_url = $this->pp_add_query_string_to_url($iframe_url, $atts['query_string']);
		}

		if( isset($atts['height']) && $atts['height'] !== '' )
			$iframe_height = $atts['height'];
		else
			$iframe_height = 1500;

		if( isset($atts['width']) && $atts['width'] !== '' )
			$iframe_width = $atts['width'];
		else
			$iframe_width = '100%';

		$html = '<script> 
			window.addEventListener("message", function (e) { 
		    	if( e.data.event === "parkingPro.ui.pageHeightChanged" ) 
	            {
	                jQuery("#parkingpro_booking_widgets_account_registration_iframe").height(e.data.newHeight + 50);
	            }
	        }, false);
	    </script>';

		$html .= '<iframe id="parkingpro_booking_widgets_account_registration_iframe" style="width: ' . esc_attr($iframe_width) . '; padding: 0; border: 0;" src="' . esc_url_raw($iframe_url) . '" width="' . esc_attr($iframe_width) . '" height="' . esc_attr($iframe_height) . '" frameborder="0" scrolling="yes"></iframe>';
		
        return $html;

	}

	/**
	 * Add shortcode for account login iFrame.
	 *
	 * @since    1.2.5
	 */
	public function pp_booking_iframe_account_login_shortcode( $atts ) {

		$myparkingpro_url = get_option('parkingpro_booking_widgets_myparkingpro_url');

		if( !$myparkingpro_url )
			return '';

		$iframe_url = $myparkingpro_url . '/account/login?';

		if( isset($atts['hideheader']) && $atts['hideheader'] !== '' )
			$iframe_url .= '&hideHeader';

		if( isset($atts['hidetitle']) && $atts['hidetitle'] !== '' )
			$iframe_url .= '&hideTitle';

		if( isset($atts['culture']) && $atts['culture'] !== '' ) {
			$iframe_url .= '&culture=' . $atts['culture'];
		}
		elseif( function_exists('icl_object_id') ) {
			if( ICL_LANGUAGE_CODE === 'nl' )
				$iframe_url .= '&culture=nl-NL';
			elseif( ICL_LANGUAGE_CODE === 'de' )
				$iframe_url .= '&culture=de-DE';
			else
				$iframe_url .= '&culture=en-GB';
		}
		else {
			$culture = get_option('parkingpro_booking_widgets_culture');

			if( isset($culture) && $culture !== "" )
				$iframe_url .= '&culture=' . $culture;
		}

		if( isset($_GET['mppdata']) && $_GET['mppdata'] !== "" )
			$iframe_url .= '&mppdata=' . esc_attr($_GET['mppdata']);

		if( isset($atts['query_string']) && $atts['query_string'] !== '' ) {
			$iframe_url = $this->pp_add_query_string_to_url($iframe_url, $atts['query_string']);
		}

		if( isset($atts['height']) && $atts['height'] !== '' )
			$iframe_height = $atts['height'];
		else
			$iframe_height = 1500;

		if( isset($atts['width']) && $atts['width'] !== '' )
			$iframe_width = $atts['width'];
		else
			$iframe_width = '100%';

		$html = '<script> 
			window.addEventListener("message", function (e) { 
		    	if( e.data.event === "parkingPro.ui.pageHeightChanged" ) 
	            {
	                jQuery("#parkingpro_booking_widgets_account_login_iframe").height(e.data.newHeight + 50);
	            }
	        }, false);
	    </script>';

		$html .= '<iframe id="parkingpro_booking_widgets_account_login_iframe" style="width: ' . esc_attr($iframe_width) . '; padding: 0; border: 0;" src="' . esc_url_raw($iframe_url) . '" width="' . esc_attr($iframe_width) . '" height="' . esc_attr($iframe_height) . '" frameborder="0" scrolling="yes"></iframe>';
		
        return $html;

	}

	/**
	 * Add shortcode for parking rates iFrame.
	 *
	 * @since    1.2.5
	 */
	public function pp_booking_iframe_parking_rates_shortcode( $atts ) {

		$myparkingpro_url = get_option('parkingpro_booking_widgets_myparkingpro_url');

		if( !$myparkingpro_url )
			return '';

		$iframe_url = $myparkingpro_url . '/parkingrates?';

		if( isset($atts['hideheader']) && $atts['hideheader'] !== '' )
			$iframe_url .= '&hideHeader';

		if( isset($atts['hidetitle']) && $atts['hidetitle'] !== '' )
			$iframe_url .= '&hideTitle';

		if( isset($atts['maxnumberofdays']) && $atts['maxnumberofdays'] !== '' )
			$iframe_url .= '&maxNumberOfDays=' . $atts['maxnumberofdays'];

		if( isset($atts['culture']) && $atts['culture'] !== '' ) {
			$iframe_url .= '&culture=' . $atts['culture'];
		}
		elseif( function_exists('icl_object_id') ) {
			if( ICL_LANGUAGE_CODE === 'nl' )
				$iframe_url .= '&culture=nl-NL';
			elseif( ICL_LANGUAGE_CODE === 'de' )
				$iframe_url .= '&culture=de-DE';
			else
				$iframe_url .= '&culture=en-GB';
		}
		else {
			$culture = get_option('parkingpro_booking_widgets_culture');

			if( isset($culture) && $culture !== "" )
				$iframe_url .= '&culture=' . $culture;
		}

		if( isset($_GET['mppdata']) && $_GET['mppdata'] !== "" )
			$iframe_url .= '&mppdata=' . esc_attr($_GET['mppdata']);

		if( isset($_GET['visible_services']) && $_GET['visible_services'] !== '' ) {
			$iframe_url .= '&showLocations=' . $_GET['visible_services'];
		}
		elseif( $parkingpro_booking_widgets_force_single_service === 'true' && isset($_GET['locationId']) && $_GET['locationId'] !== '' ) {
			$iframe_url .= '&showLocations=' . $_GET['locationId'];
		}
		elseif( isset($_GET['showLocations']) && $_GET['showLocations'] !== '' ) {
			$iframe_url .= '&showLocations=' . $_GET['showLocations'];
		}
		elseif( isset($atts['showlocations']) && $atts['showlocations'] !== '' ) {
			$iframe_url .= '&showLocations=' . $atts['showlocations'];	
		}

		if( isset($atts['includelocations']) && $atts['includelocations'] !== '' ) {
			$iframe_url .= '&includeLocations=' . $atts['includelocations'];	
		}

		if( isset($atts['query_string']) && $atts['query_string'] !== '' ) {
			$iframe_url = $this->pp_add_query_string_to_url($iframe_url, $atts['query_string']);
		}

		if( isset($atts['height']) && $atts['height'] !== '' )
			$iframe_height = $atts['height'];
		else
			$iframe_height = 2800;

		if( isset($atts['width']) && $atts['width'] !== '' )
			$iframe_width = $atts['width'];
		else
			$iframe_width = '100%';

		$html = '<script> 
			window.addEventListener("message", function (e) { 
		    	if( e.data.event === "parkingPro.ui.pageHeightChanged" ) 
	            {
	                jQuery("#parkingpro_booking_widgets_parking_rates_iframe").height(e.data.newHeight + 50);
	            }
	        }, false);
	    </script>';

		$html .= '<iframe id="parkingpro_booking_widgets_parking_rates_iframe" style="width: ' . esc_attr($iframe_width) . '; padding: 0; border: 0;" src="' . esc_url_raw($iframe_url) . '" width="' . esc_attr($iframe_width) . '" height="' . esc_attr($iframe_height) . '" frameborder="0" scrolling="yes"></iframe>';
		
        return $html;

	}

	/**
	 * Add shortcode for product selection.
	 *
	 * @since    1.1.0
	 */
	public function pp_product_selection_shortcode( $atts ) {

		$plugin = new ParkingPro_Booking_Widgets();

		$variable_names = $plugin->get_variable_names();

		foreach ($variable_names as $var) 
		{

			$meta_key = $var['meta_key'];
			$short = $var['short'];

			// Set variable via shortcode/widget attribute or load from default settings
			if (isset($atts[ $short ]) )
			    ${"$meta_key"} = $atts[ $short ];
			else
				${"$meta_key"} = get_option($meta_key, $var['default_value']);

			if( $meta_key === 'parkingpro_booking_widgets_filter_services' && is_string($parkingpro_booking_widgets_filter_services) )
				$parkingpro_booking_widgets_filter_services = explode(',', $parkingpro_booking_widgets_filter_services);

		}

		// Retrieve list of services
	    if( $parkingpro_booking_widgets_myparkingpro_url && isset($_GET['airport']) && $_GET['airport'] !== '' )
	    	$parkingpro_booking_widgets_myparkingpro_services = $this->get_myparkingpro_services($parkingpro_booking_widgets_myparkingpro_url, esc_attr($_GET['airport']) );
	    elseif( $parkingpro_booking_widgets_myparkingpro_url )
	    	$parkingpro_booking_widgets_myparkingpro_services = $this->get_myparkingpro_services($parkingpro_booking_widgets_myparkingpro_url);
	    else
	    	$parkingpro_booking_widgets_myparkingpro_services = [];

	    // Get list of tags
	    $parkingpro_booking_widgets_tags = $this->pp_get_services_tags($parkingpro_booking_widgets_myparkingpro_services);

	    // Retrieve list of airports
	    if( $parkingpro_booking_widgets_myparkingpro_url )
	    	$parkingpro_booking_widgets_airports = $this->get_myparkingpro_airports($parkingpro_booking_widgets_myparkingpro_url);
	    else
	    	$parkingpro_booking_widgets_airports = [];

	    // If enabled, store widget data
	    if( $parkingpro_booking_widgets_remember_widget_fields === 'true' )
	    	$this->pp_store_widget_data($_GET);

	    // Determine the form URL
	    if( isset( $parkingpro_booking_widgets_booking_page ) && $parkingpro_booking_widgets_booking_page !== '' )
		    $form_destination_url = $parkingpro_booking_widgets_booking_page;
		else
		    $form_destination_url = $parkingpro_booking_widgets_myparkingpro_url . '/reservations/add';

		// Calculate total number of days
		$arrivalDate = (isset($_GET['arrivalDate']) ? new DateTime(esc_attr($_GET['arrivalDate'])) : null );
		$departureDate = (isset($_GET['departureDate']) ? new DateTime(esc_attr($_GET['departureDate'])) : null );
		$interval = ($arrivalDate !== null && $departureDate !== null ? $arrivalDate->diff($departureDate) : null);
		$totalDays = ($interval !== null ? $interval->days + 1 : null);

		// Get airport name
		$airport_name = NULL;
		$parkingpro_booking_airport_services = [];

		if( isset($_GET['airport']) && $_GET['airport'] !== '' ) {
		    foreach ($parkingpro_booking_widgets_airports as $parkingpro_booking_widgets_airport) 
		    {
		        if( $parkingpro_booking_widgets_airport['id'] === $_GET['airport'] ) {
		            $airport_name = $parkingpro_booking_widgets_airport['name'];
		            break;
		        }
		    }

		    // Create array of services for the selected airport
		    foreach ($parkingpro_booking_widgets_myparkingpro_services as $service) 
		    {
		        if( in_array($service['id'], $parkingpro_booking_widgets_filter_services) ) {
		            if( $service['airport']['id'] === $_GET['airport'] )
		                array_push($parkingpro_booking_airport_services, $service['id']);
		        }
		    }
		}

		return include( plugin_dir_path( __FILE__ ) . 'partials/parkingpro-booking-widgets-public-product-selection-display.php' );

	}

	/**
	 * Retrieve services for a MyParkingPro URL
	 *
	 * @since     1.0.0
	 * @param     string   $myparkingpro_url       The MyParkingPro URL.
	 * @param     string   $airport_guid           GUID of the airport.
	 * @return    array    List of services.
	 */
	public function get_myparkingpro_services($myparkingpro_url, $airport_guid = NULL) {
		
		try 
		{
			$services_list = [];

			$url = $myparkingpro_url . '/api/widget/locations';
			$response = wp_remote_get( esc_url_raw($url) );

			if( !is_array( $response ) )
				throw new Exception("Error while loading locations", 1);

	        $array = json_decode($response['body'], true);

	        if( $array && $array['data'] && count( $array['data'] ) > 0 ) {
	            
	            foreach( $array['data'] as $service ) {

	            	if( $airport_guid !== NULL ) {
	            		if( $airport_guid === $service['airport']['id'] )
	                		array_push($services_list, $service);
	                }
	                else {
	                	array_push($services_list, $service);
	                }
	            }

	        }

	        return $services_list;
		} 
		catch (Exception $e) 
		{
			error_log($e);
			return [];
		}

	}

	/**
	 * Retrieve all airports for a MyParkingPro URL
	 *
	 * @since     1.1.0
	 * @param     string   $myparkingpro_url       The MyParkingPro URL.
	 * @return    array    List of services.
	 */
	public function get_myparkingpro_airports($myparkingpro_url) {
		
		try 
		{
			$airports_list = [__("Other", "parkingpro-booking-widgets") => []];

			$url = $myparkingpro_url . '/api/widget/locations';
			$response = wp_remote_get( esc_url_raw($url) );

			if( !is_array( $response ) )
				throw new Exception("Error while loading locations", 1);

	        $array = json_decode($response['body'], true);

	        if( $array && $array['data'] && count( $array['data'] ) > 0 ) {
	            
	            foreach( $array['data'] as $service ) 
	            {
	            	if( $service['airport'] && $service['airport']['id'] )
	            	{
	            		if( $service['airport']['country'] ) 
	            		{
	            			$country_translated = $service['airport']['country'];

                            if( $country_translated === "NL" )
                                $country_translated = __("Netherlands", "parkingpro-booking-widgets");
                            elseif( $country_translated === "DE" )
                                $country_translated = __("Germany", "parkingpro-booking-widgets");
                            elseif( $country_translated === "BE" )
                                $country_translated = __("Belgium", "parkingpro-booking-widgets");
                            elseif( $country_translated === "SE" )
                                $country_translated = __("Sweden", "parkingpro-booking-widgets");
                            elseif( $country_translated === "GB" )
                                $country_translated = __("United Kingdom", "parkingpro-booking-widgets");
                            elseif( $country_translated === "ES" )
                                $country_translated = __("Spain", "parkingpro-booking-widgets");
                            elseif( $country_translated === "FR" )
                                $country_translated = __("France", "parkingpro-booking-widgets");
                            elseif( $country_translated === "other" )
                                $country_translated = __("Other", "parkingpro-booking-widgets");

	            			if( !array_key_exists($country_translated, $airports_list) )
	            				$airports_list[$country_translated] = [];

	            			$airports_list[$country_translated][$service['airport']['id']] = $service['airport'];
	            		}
	            		else 
	            		{
	            			$airports_list[__("Other", "parkingpro-booking-widgets")][$service['airport']['id']] = $service['airport'];
	            		}
	            	}
	            }

	        }
	        
	        foreach ($airports_list as $country_index => $country_airports) {
	        	usort($country_airports, function($a, $b) {
				    return $a['name'] > $b['name'];
				});

				$airports_list[$country_index] = $country_airports;
	        }

	        // Move the 'others' to the end of the array
	        if( count($airports_list[__("Other", "parkingpro-booking-widgets")]) === 0 )
	        {
	        	unset($airports_list[__("Other", "parkingpro-booking-widgets")]);
	        }
	        else
	        {
		        $other_airports = $airports_list[__("Other", "parkingpro-booking-widgets")];
				unset($airports_list[__("Other", "parkingpro-booking-widgets")]);
				$airports_list[__("Other", "parkingpro-booking-widgets")] = $other_airports;
			}

	        return $airports_list;
		} 
		catch (Exception $e) 
		{
			error_log($e);
			return [];
		}

	}

	/**
	 * Generate script HTML for Cross-domain iFrame tracking
	 *
	 * @since    1.2.3
	 * @param    string   $google_analytics_id       Google Analytics ID
	 * @param    string   $iframe_url     			 iFrame URL
	 * @return   string   HTML tracking script
	 */
	public function pp_booking_iframe_tracking($google_analytics_id, $iframe_url) {

		$html = '';

		if( !$google_analytics_id )
			return $html;

		$html .= "<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . esc_attr($google_analytics_id) . "\"></script>";

		$html .= "
			<script>
				(function () {
					var iframe = document.querySelector('#parkingpro_booking_widgets_iframe'),
						iframeUrl = new URL('". $iframe_url ."'),
						trackingId = '" . esc_attr($google_analytics_id) . "',
						iframeLoaded = false;

					window.dataLayer = window.dataLayer || [];
					window.gtag = function () {
						dataLayer.push(arguments);
					};

					gtag('js', new Date());
					gtag('config', trackingId, {
						linker: {
							domains: [iframeUrl.host],
							accept_incoming: true
						}
					});
					gtag('set', 'cookie_flags', 'SameSite=None;Secure');
					gtag('event', 'pageview');

					gtag('get', trackingId, 'client_id', function (clientId) {
						iframeUrl.searchParams.append('_ga_client_id', clientId);
						iframe.src = iframeUrl.toString();

						iframeLoaded = true;
					});

					// Fallback in case gtag didn't load because of adblocker or other reasons.
					// We still want to load the iframe. Set to 2 seconds.
					setTimeout(function () {
						if (iframeLoaded === false) {
							iframe.src = iframeUrl.toString();
							iframeLoaded = true;
						}
					}, 2000);

					window.addEventListener('message', function (e) {
						if(new URL(e.origin).host === iframeUrl.host && e.data) {
							switch (e.data.event) {
								case 'parkingPro.googleAnalytics.gtag':
									gtag.apply(null, e.data.gtagArguments);
									break;

								case 'parkingPro.googleTagManager.dataLayer':
									dataLayer.push(e.data.dataLayerArguments);
									break;

								default:
									break;
							}
						}
					});
				})();
			</script>
		";

        return $html;
	}

	/**
	 * Store URL parameters in session
	 *
	 * @since    1.2.6
	 * @param    array    $data       GET parameters from URL
	 */
	public function pp_store_widget_data($data) {

		if( isset($data['arrivalDate']) && $data['arrivalDate'] !== '' )
			$_SESSION['parkingpro_arrivaldate'] = $data['arrivalDate'];

		if( isset($data['arrivalTime']) && $data['arrivalTime'] !== '' )
			$_SESSION['parkingpro_arrivaltime'] = $data['arrivalTime'];

		if( isset($data['departureDate']) && $data['departureDate'] !== '' )
			$_SESSION['parkingpro_departuredate'] = $data['departureDate'];

		if( isset($data['departureTime']) && $data['departureTime'] !== '' )
			$_SESSION['parkingpro_departuretime'] = $data['departureTime'];

		if( isset($data['airport']) && $data['airport'] !== '' )
			$_SESSION['parkingpro_airport'] = $data['airport'];

		if( isset($data['car']) && $data['car'] !== '' )
			$_SESSION['parkingpro_car'] = $data['car'];

		if( isset($data['locationId']) && $data['locationId'] !== '' )
			$_SESSION['parkingpro_locationid'] = $data['locationId'];

	}

	/**
	 * Add shortcode for thank you page.
	 *
	 * @since    1.2.9
	 */
	public function pp_thank_you_shortcode( $atts ) {

		$plugin = new ParkingPro_Booking_Widgets();

		$variable_names = $plugin->get_variable_names();

		foreach ($variable_names as $var) 
		{

			$meta_key = $var['meta_key'];
			$short = $var['short'];

			// Set variable via shortcode/widget attribute or load from default settings
			if (isset($atts[ $short ]) )
			    ${"$meta_key"} = $atts[ $short ];
			else
				${"$meta_key"} = get_option($meta_key, $var['default_value']);
		}

		return include( plugin_dir_path( __FILE__ ) . 'partials/parkingpro-booking-widgets-public-thank-you-display.php' );

	}

	/**
	 * Add shortcode for thank you page.
	 *
	 * @since    1.2.12
	 */
	public function pp_get_services_tags( $services ) {

		$tag_list = [];

		foreach( $services as $service ) 
		{
			if( is_array($service['appliedTags']) && count($service['appliedTags']) > 0 )
			{
				foreach( $service['appliedTags'] as $appliedTag )
				{
					$tag = [
						'tagId' => $appliedTag['tagId'],
						'tagName' => $appliedTag['tagName']
					];

					if( !in_array($tag, $tag_list) )
						array_push($tag_list, $tag);
				}
			}
		}

		usort($tag_list, function ($item1, $item2) {
		    if ($item1['tagName'] == $item2['tagName']) return 0;
		    return $item1['tagName'] < $item2['tagName'] ? -1 : 1;
		});

		return $tag_list;

	}

	/**
	 * Retrieve the config for a MyParkingPro URL
	 *
	 * @since     1.2.15
	 * @param     string   $myparkingpro_url       The MyParkingPro URL.
	 * @return    array    Config object.
	 */
	public function get_myparkingpro_config($myparkingpro_url) {
		
		try 
		{
			$url = $myparkingpro_url . '/api/widget/config';
			$response = wp_remote_get( esc_url_raw($url) );

			if( !is_array( $response ) )
				throw new Exception("Error while loading config", 1);

	        return json_decode($response['body'], true);
		} 
		catch (Exception $e) 
		{
			error_log($e);
			return [];
		}

	}

	/**
	 * Generate script to set the iFrame source
	 *
	 * @since    1.2.29
	 * @return   string   HTML with script
	 */
	public function pp_booking_iframe_set_src($iframe_url) {

		$html = '';

		if( !$iframe_url )
			return $html;

		$html .= "<script>

			try { 
				var iframe = document.querySelector('#parkingpro_booking_widgets_iframe');
				iframe.src = '" . $iframe_url . "';
			} 
			catch(e) {
				console.log(e);
			}

		</script>";

        return $html;

	}

	/**
	 * Add query string to an existing URL
	 *
	 * @since    1.2.45
	 * @param    string   $url       		Input URL.
	 * @param    string   $query_string     Query string parameters.
	 * @return   string   URL
	 */
	public function pp_add_query_string_to_url($url, $query_string)
	{
		// Parse the existing URL to get its components
		$url_components = parse_url($url);

		// Check if there is an existing query string and parse it into an array
		parse_str($url_components['query'] ?? '', $existing_params);

		// Parse the additional query string into an array
		parse_str($query_string, $additional_params);

		// Merge existing and additional parameters
		$final_params = array_merge($existing_params, $additional_params);

		// Build the final query string from the merged parameters
		$url_components['query'] = http_build_query($final_params);

		// Rebuild the full URL
		$final_url = $url_components['scheme'] . '://'
			. $url_components['host']
			. (isset($url_components['port']) ? ':' . $url_components['port'] : '')
			. $url_components['path']
			. '?' . $url_components['query'];

		return $final_url;
	}

}
