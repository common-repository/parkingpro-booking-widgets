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
 * The widget class.
 *
 * This is used to define the widget.
 *
 * @since      1.0.0
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/includes
 * @author     ParkingPro <info@parkingpro.nl>
 */
class ParkingPro_Booking_Widgets_Widget extends WP_Widget {
   
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
     * Register widget with WordPress
     *
     * @since    1.0.0
     * @param      string    $parkingpro_booking_widgets       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $parkingpro_booking_widgets, $version ) {

        $this->parkingpro_booking_widgets = $parkingpro_booking_widgets;
        $this->version = $version;

        $widget_ops = array( 
            'classname' => 'parkingpro_booking_widgets_widget',
            'description' => 'ParkingPro Booking Widget',
        );
        parent::__construct( 'parkingpro_booking_widgets_widget', 'ParkingPro Booking Widget', $widget_ops );

    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {

        echo $args['before_widget'];

        $ParkingPro_Booking_Widgets = new ParkingPro_Booking_Widgets();

        $variable_names = $ParkingPro_Booking_Widgets->get_variable_names();

        $atts = [];

        foreach ($variable_names as $var) 
        {
            
            $short = $var['short'];

            $atts[ $short ] = $instance[ $short ];

        }
        
        $ParkingPro_Booking_Widgets_Public = new ParkingPro_Booking_Widgets_Public( $this->parkingpro_booking_widgets, $this->version );
        $widget = $ParkingPro_Booking_Widgets_Public->pp_booking_shortcode($atts);
        echo $widget;
        
        echo $args['after_widget'];

    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {

        $ParkingPro_Booking_Widgets = new ParkingPro_Booking_Widgets();

        $variable_names = $ParkingPro_Booking_Widgets->get_variable_names();

        foreach ($variable_names as $var) 
        {

            $short = $var['short'];
            
            ${"$short"} = ! empty( $instance[ $short ] ) ? $instance[ $short ] : $var['default_value'];

        }

        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'form_title' ) ); ?>"><?php echo esc_attr(__("Default widget title", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_title' ) ); ?>" type="text" value="<?php echo esc_attr( $form_title ); ?>" required>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'booking_page' ) ); ?>"><?php echo esc_attr(__("Booking page address (URL)", "parkingpro-booking-widgets")); ?></label> 
            <select name="<?php echo esc_attr( $this->get_field_name( 'booking_page' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'booking_page' ) ); ?>">
                <option value=""><?php echo esc_attr(__("Link to MyParkingPro page", "parkingpro-booking-widgets")); ?></option>
                <?php 
                $pages = get_pages();
                foreach($pages as $page)
                {
                    $page_url = get_page_link($page->ID);
                    echo '<option value="' . esc_url_raw($page_url) . '" ' . selected( $page_url, $booking_page ) . '>' . esc_attr($page->post_title) . '</option>';
                }
                ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'products_page' ) ); ?>"><?php echo esc_attr(__("Product selection page address (URL)", "parkingpro-booking-widgets")); ?></label> 
            <select name="<?php echo esc_attr( $this->get_field_name( 'products_page' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'products_page' ) ); ?>">
                <option value=""><?php echo esc_attr(__("Link to product selection page", "parkingpro-booking-widgets")); ?></option>
                <?php 
                $pages = get_pages();
                foreach($pages as $page)
                {
                    $page_url = get_page_link($page->ID);
                    echo '<option value="' . esc_url_raw($page_url) . '" ' . selected( $page_url, $products_page ) . '>' . esc_attr($page->post_title) . '</option>';
                }
                ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thankyou_page' ) ); ?>"><?php echo esc_attr(__("Thank you page address (URL)", "parkingpro-booking-widgets")); ?></label> 
            <select name="<?php echo esc_attr( $this->get_field_name( 'thankyou_page' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'thankyou_page' ) ); ?>">
                <option value=""><?php echo esc_attr(__("Link to thank you page", "parkingpro-booking-widgets")); ?></option>
                <?php 
                $pages = get_pages();
                foreach($pages as $page)
                {
                    $page_url = get_page_link($page->ID);
                    echo '<option value="' . esc_url_raw($page_url) . '" ' . selected( $page_url, $thankyou_page ) . '>' . esc_attr($page->post_title) . '</option>';
                }
                ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'widget_destination' ); ?>"><?php echo esc_attr(__("Widget destination", "parkingpro-booking-widgets")); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'widget_destination' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'widget_destination' ) ); ?>">
                <option value="booking" <?php selected( 'booking', $widget_destination ); ?>><?php echo esc_attr(__("Booking page", "parkingpro-booking-widgets")); ?></option>
                <option value="product_selection" <?php selected( 'product_selection', $widget_destination ); ?>><?php echo esc_attr(__("Product selection page", "parkingpro-booking-widgets")); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'iframe_destination' ); ?>"><?php echo esc_attr(__("iFrame destination", "parkingpro-booking-widgets")); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'iframe_destination' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'iframe_destination' ) ); ?>">
                <option value="iframe" <?php selected( 'iframe', $iframe_destination ); ?>><?php echo esc_attr(__("iFrame", "parkingpro-booking-widgets")); ?></option>
                <option value="thankyou_page" <?php selected( 'thankyou_page', $iframe_destination ); ?>><?php echo esc_attr(__("Custom thank you page", "parkingpro-booking-widgets")); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'display_services' ); ?>"><?php echo esc_attr(__("Display services", "parkingpro-booking-widgets")); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'display_services' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_services' ) ); ?>">
                <option value="display" <?php selected( 'display', $display_services ); ?>><?php echo esc_attr(__("Yes, display services", "parkingpro-booking-widgets")); ?></option>
                <option value="hide" <?php selected( 'hide', $display_services ); ?>><?php echo esc_attr(__("No, hide services", "parkingpro-booking-widgets")); ?></option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'filter_services' ); ?>"><?php echo esc_attr(__("Services to display", "parkingpro-booking-widgets")); ?></label>

            <?php 
            $myparkingpro_url = get_option('parkingpro_booking_widgets_myparkingpro_url');
            $ParkingPro_Booking_Widgets_Admin = new ParkingPro_Booking_Widgets_Admin( $this->parkingpro_booking_widgets, $this->version );
            $parkingpro_booking_widgets_services = $ParkingPro_Booking_Widgets_Admin->get_myparkingpro_services($myparkingpro_url);
           
            if( count( $parkingpro_booking_widgets_services ) > 0 )
            {                
                echo '<ul>';

                foreach( $parkingpro_booking_widgets_services as $service ) 
                {
                    echo '<li><label><input id="' . esc_attr( $this->get_field_id( 'filter_services' ) ) . '" type="checkbox" name="' . esc_attr( $this->get_field_name( 'filter_services' ) ) . '[]" value="' . esc_attr($service["id"]) . '"';

                    if( in_array($service['id'], explode(',', $filter_services)) )
                        echo " checked='checked' ";

                    echo ' /> ' . esc_attr($service['name']) . '</label></li>';
                }
                
                echo '</ul>';
            }
            else
            {
                echo '<p class="text-red">' . __("Error: there are no services available to display", "parkingpro-booking-widgets") . '</p>';
            }

            ?>

        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'force_single_service' ); ?>"><?php echo esc_attr(__("Services in iFrame", "parkingpro-booking-widgets")); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'force_single_service' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'force_single_service' ) ); ?>">
                <option value="false" <?php selected( 'false', $force_single_service ); ?>><?php echo esc_attr(__("User can change services in the iFrame", "parkingpro-booking-widgets")); ?></option>
                <option value="true" <?php selected( 'true', $force_single_service ); ?>><?php echo esc_attr(__("Use the service from widget and hide services in the iFrame", "parkingpro-booking-widgets")); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php echo esc_attr(__("Default widget size", "parkingpro-booking-widgets")); ?></label> 
            <select id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>">
                <option value="large" <?php selected( 'large', $size ); ?>><?php echo esc_attr(__("Large", "parkingpro-booking-widgets")); ?></option>
                <option value="medium" <?php selected( 'medium', $size ); ?>><?php echo esc_attr(__("Medium", "parkingpro-booking-widgets")); ?></option>
                <option value="small" <?php selected( 'small', $size ); ?>><?php echo esc_attr(__("Small", "parkingpro-booking-widgets")); ?></option>
                <option value="row" <?php selected( 'row', $size ); ?>><?php echo esc_attr(__("Row", "parkingpro-booking-widgets")); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'display_airport_dropdown' ); ?>"><?php echo esc_attr(__("Display airport dropdown", "parkingpro-booking-widgets")); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'display_airport_dropdown' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_airport_dropdown' ) ); ?>">
                <option value="hide" <?php selected( 'hide', $display_airport_dropdown ); ?>><?php echo esc_attr(__("Hide airport dropdown", "parkingpro-booking-widgets")); ?></option>
                <option value="display" <?php selected( 'display', $display_airport_dropdown ); ?>><?php echo esc_attr(__("Display airport dropdown", "parkingpro-booking-widgets")); ?></option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'display_car_dropdown' ); ?>"><?php echo esc_attr(__("Display car dropdown", "parkingpro-booking-widgets")); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'display_car_dropdown' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_car_dropdown' ) ); ?>">
                <option value="hide" <?php selected( 'hide', $display_car_dropdown ); ?>><?php echo esc_attr(__("Hide car dropdown", "parkingpro-booking-widgets")); ?></option>
                <option value="display" <?php selected( 'display', $display_car_dropdown ); ?>><?php echo esc_attr(__("Display car dropdown", "parkingpro-booking-widgets")); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'remember_widget_fields' ); ?>"><?php echo esc_attr(__("Remember widget data", "parkingpro-booking-widgets")); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'remember_widget_fields' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'remember_widget_fields' ) ); ?>">
                <option value="false" <?php selected( 'false', $remember_widget_fields ); ?>><?php echo esc_attr(__("No, don't remember widget data", "parkingpro-booking-widgets")); ?></option>
                <option value="true" <?php selected( 'true', $remember_widget_fields ); ?>><?php echo esc_attr(__("Yes, remember widget fields", "parkingpro-booking-widgets")); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'culture' ) ); ?>"><?php echo esc_attr(__("iFrame language", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'culture' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'culture' ) ); ?>" type="text" placeholder="nl-NL" value="<?php echo esc_attr( $culture ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'header_element_selector' ) ); ?>"><?php echo esc_attr(__("Header element selector", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'header_element_selector' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'header_element_selector' ) ); ?>" type="text" placeholder="#header" value="<?php echo esc_attr( $header_element_selector ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'partnerid' ) ); ?>"><?php echo esc_attr(__("Partner ID", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'partnerid' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'partnerid' ) ); ?>" type="text" placeholder="" value="<?php echo esc_attr( $partnerid ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'section_airport_title' ) ); ?>"><?php echo esc_attr(__("Section airport title", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_airport_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_airport_title' ) ); ?>" type="text" value="<?php echo esc_attr( $section_airport_title ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'section_dates_title' ) ); ?>"><?php echo esc_attr(__("Section dates title", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_dates_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_dates_title' ) ); ?>" type="text" value="<?php echo esc_attr( $section_dates_title ); ?>" required>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'label_parkingdate' ) ); ?>"><?php echo esc_attr(__("Label parking date", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label_parkingdate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_parkingdate' ) ); ?>" type="text" value="<?php echo esc_attr( $label_parkingdate ); ?>" required>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'label_returndate' ) ); ?>"><?php echo esc_attr(__("Label return date", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label_returndate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label_returndate' ) ); ?>" type="text" value="<?php echo esc_attr( $label_returndate ); ?>" required>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'section_services_title' ) ); ?>"><?php echo esc_attr(__("Section services title", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_services_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_services_title' ) ); ?>" type="text" value="<?php echo esc_attr( $section_services_title ); ?>" required>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'section_car_title' ) ); ?>"><?php echo esc_attr(__("Section car title", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'section_car_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'section_car_title' ) ); ?>" type="text" value="<?php echo esc_attr( $section_car_title ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php echo esc_attr(__("Button text", "parkingpro-booking-widgets")); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" required>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'colors_header_background' ) ); ?>"><?php echo esc_attr(__("Header background color", "parkingpro-booking-widgets")); ?></label> 
            <input class="color-picker" id="<?php echo esc_attr( $this->get_field_id( 'colors_header_background' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'colors_header_background' ) ); ?>" type="text" value="<?php echo esc_attr( $colors_header_background ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'colors_header_text' ) ); ?>"><?php echo esc_attr(__("Header text color", "parkingpro-booking-widgets")); ?></label> 
            <input class="color-picker" id="<?php echo esc_attr( $this->get_field_id( 'colors_header_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'colors_header_text' ) ); ?>" type="text" value="<?php echo esc_attr( $colors_header_text ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'colors_section_text' ) ); ?>"><?php echo esc_attr(__("Section title color", "parkingpro-booking-widgets")); ?></label> 
            <input class="color-picker" id="<?php echo esc_attr( $this->get_field_id( 'colors_section_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'colors_section_text' ) ); ?>" type="text" value="<?php echo esc_attr( $colors_section_text ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'colors_label_text' ) ); ?>"><?php echo esc_attr(__("Label text color", "parkingpro-booking-widgets")); ?></label> 
            <input class="color-picker" id="<?php echo esc_attr( $this->get_field_id( 'colors_label_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'colors_label_text' ) ); ?>" type="text" value="<?php echo esc_attr( $colors_label_text ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'colors_button_background' ) ); ?>"><?php echo esc_attr(__("Button background color", "parkingpro-booking-widgets")); ?></label> 
            <input class="color-picker" id="<?php echo esc_attr( $this->get_field_id( 'colors_button_background' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'colors_button_background' ) ); ?>" type="text" value="<?php echo esc_attr( $colors_button_background ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'colors_button_text' ) ); ?>"><?php echo esc_attr(__("Button text color", "parkingpro-booking-widgets")); ?></label> 
            <input class="color-picker" id="<?php echo esc_attr( $this->get_field_id( 'colors_button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'colors_button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $colors_button_text ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'colors_button_bover_background' ) ); ?>"><?php echo esc_attr(__("Button background color", "parkingpro-booking-widgets")); ?></label> 
            <input class="color-picker" id="<?php echo esc_attr( $this->get_field_id( 'colors_button_bover_background' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'colors_button_bover_background' ) ); ?>" type="text" value="<?php echo esc_attr( $colors_button_bover_background ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'colors_button_hover_text' ) ); ?>"><?php echo esc_attr(__("Button text color", "parkingpro-booking-widgets")); ?></label> 
            <input class="color-picker" id="<?php echo esc_attr( $this->get_field_id( 'colors_button_hover_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'colors_button_hover_text' ) ); ?>" type="text" value="<?php echo esc_attr( $colors_button_hover_text ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'colors_border' ) ); ?>"><?php echo esc_attr(__("Border color", "parkingpro-booking-widgets")); ?></label> 
            <input class="color-picker" id="<?php echo esc_attr( $this->get_field_id( 'colors_border' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'colors_border' ) ); ?>" type="text" value="<?php echo esc_attr( $colors_border ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'affiliate_tracking' ); ?>"><?php echo esc_attr(__("Display services", "parkingpro-booking-widgets")); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'affiliate_tracking' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'affiliate_tracking' ) ); ?>">
                <option value="hide" <?php selected( 'hide', $affiliate_tracking ); ?>><?php echo esc_attr(__("No, don't use affiliate tracking", "parkingpro-booking-widgets")); ?></option>
                <option value="tradedoubler" <?php selected( 'tradedoubler', $affiliate_tracking ); ?>><?php echo esc_attr("Tradedoubler"); ?></option>
            </select>
        </p>

        <script type='text/javascript'>
            jQuery(document).ready(function($) {
                $('.color-picker').wpColorPicker({
                    change: function(e, ui) {
                        $( e.target ).val( ui.color.toString() );
                        $( e.target ).trigger('change'); // enable widget "Save" button
                    }
                });
            });
        </script>

        <?php

    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $ParkingPro_Booking_Widgets = new ParkingPro_Booking_Widgets();

        $variable_names = $ParkingPro_Booking_Widgets->get_variable_names();

        foreach ($variable_names as $var) 
        {

            $short = $var['short'];

            if( $short === "filter_services" )
                $new_instance[ $short ] = implode(',', $new_instance[ $short ]);

            $sanitized_value = call_user_func($var['sanitizer'], $new_instance[ $short ]);
            $instance[ $short ] = $sanitized_value;

        }

        return $instance;

    }

}