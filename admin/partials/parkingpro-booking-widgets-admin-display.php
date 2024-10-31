<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link              https://www.parkingpro.nl
 * @since             1.0.0
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/admin/partials
 */
?>

<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

<form method="POST">

    <table class="form-table">
        <tbody>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_myparkingpro_url"><?php echo esc_attr(__("MyParkingPro address (URL)", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_myparkingpro_url" id="parkingpro_booking_widgets_myparkingpro_url" value="<?php echo esc_url_raw($parkingpro_booking_widgets_myparkingpro_url); ?>" placeholder="https://example.myparking.pro" class="regular-text" required />
                    <p class="description"><?php echo esc_attr(__("Enter the URL of your MyParkingPro address", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_booking_page"><?php echo esc_attr(__("Booking page address (URL)", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <select name="parkingpro_booking_widgets_booking_page" id="parkingpro_booking_widgets_booking_page">
                        <option value=""><?php echo esc_attr(__("Link to MyParkingPro page", "parkingpro-booking-widgets")); ?></option>
                        <?php 
                        $pages = get_pages();
                        foreach($pages as $page)
                        {
                            $page_url = get_page_link($page->ID);
                            echo '<option value="' . esc_url_raw($page_url) . '" ' . selected( $page_url, $parkingpro_booking_widgets_booking_page ) . '>' . $page->post_title . '</option>';
                        }
                        ?>
                    </select>
                    <p class="description"><?php echo esc_attr(__("Select the booking page. Use the shortcode [pp_booking_iframe] to display the MyParkingPro iFrame on your booking page", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_products_page"><?php echo esc_attr(__("Product selection page address (URL)", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <select name="parkingpro_booking_widgets_products_page" id="parkingpro_booking_widgets_products_page">
                        <option value=""><?php echo esc_attr(__("Link to product selection page", "parkingpro-booking-widgets")); ?></option>
                        <?php 
                        $pages = get_pages();
                        foreach($pages as $page)
                        {
                            $page_url = get_page_link($page->ID);
                            echo '<option value="' . esc_url_raw($page_url) . '" ' . selected( $page_url, $parkingpro_booking_widgets_products_page ) . '>' . $page->post_title . '</option>';
                        }
                        ?>
                    </select>
                    <p class="description"><?php echo esc_attr(__("Select the product selection page. Use the shortcode [pp_product_selection] to display the product selection table on your page", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_thankyou_page"><?php echo esc_attr(__("Thank you page address (URL)", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <select name="parkingpro_booking_widgets_thankyou_page" id="parkingpro_booking_widgets_thankyou_page">
                        <option value=""><?php echo esc_attr(__("Link to thank you page", "parkingpro-booking-widgets")); ?></option>
                        <?php 
                        $pages = get_pages();
                        foreach($pages as $page)
                        {
                            $page_url = get_page_link($page->ID);
                            echo '<option value="' . esc_url_raw($page_url) . '" ' . selected( $page_url, $parkingpro_booking_widgets_thankyou_page ) . '>' . $page->post_title . '</option>';
                        }
                        ?>
                    </select>
                    <p class="description"><?php echo esc_attr(__("Select the thank you page. Use the shortcode [pp_thank_you] to display the custom thank you page", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Widget destination", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_widget_destination">
                        <select name="parkingpro_booking_widgets_widget_destination" id="parkingpro_booking_widgets_widget_destination">
                            <option value="booking" <?php selected( 'booking', $parkingpro_booking_widgets_widget_destination ); ?>><?php echo esc_attr(__("Booking page", "parkingpro-booking-widgets")); ?></option>
                            <option value="product_selection" <?php selected( 'product_selection', $parkingpro_booking_widgets_widget_destination ); ?>><?php echo esc_attr(__("Product selection page", "parkingpro-booking-widgets")); ?></option>
                        </select>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("iFrame destination", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_iframe_destination">
                        <select name="parkingpro_booking_widgets_iframe_destination" id="parkingpro_booking_widgets_iframe_destination">
                            <option value="iframe" <?php selected( 'iframe', $parkingpro_booking_widgets_iframe_destination ); ?>><?php echo esc_attr(__("iFrame", "parkingpro-booking-widgets")); ?></option>
                            <option value="thankyou_page" <?php selected( 'thankyou_page', $parkingpro_booking_widgets_iframe_destination ); ?>><?php echo esc_attr(__("Custom thank you page", "parkingpro-booking-widgets")); ?></option>
                        </select>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_size"><?php echo esc_attr(__("Default widget size", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <select name="parkingpro_booking_widgets_size" id="parkingpro_booking_widgets_size">
                        <option value="large" <?php selected( 'large', $parkingpro_booking_widgets_size ); ?>><?php echo esc_attr(__("Large", "parkingpro-booking-widgets")); ?></option>
                        <option value="medium" <?php selected( 'medium', $parkingpro_booking_widgets_size ); ?>><?php echo esc_attr(__("Medium", "parkingpro-booking-widgets")); ?></option>
                        <option value="small" <?php selected( 'small', $parkingpro_booking_widgets_size ); ?>><?php echo esc_attr(__("Small", "parkingpro-booking-widgets")); ?></option>
                        <option value="row" <?php selected( 'row', $parkingpro_booking_widgets_size ); ?>><?php echo esc_attr(__("Row", "parkingpro-booking-widgets")); ?></option>
                    </select>

                    <p class="description"><?php echo esc_attr(__("Enter the default size (width) of the widget", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_hide_times"><?php echo esc_attr(__("Hide time inputs in widget", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <select name="parkingpro_booking_widgets_hide_times" id="parkingpro_booking_widgets_hide_times">
                        <option value="no" <?php selected( 'no', $parkingpro_booking_widgets_hide_times ); ?>><?php echo esc_attr(__("No, show times", "parkingpro-booking-widgets")); ?></option>
                        <option value="yes" <?php selected( 'yes', $parkingpro_booking_widgets_hide_times ); ?>><?php echo esc_attr(__("Yes, hide times", "parkingpro-booking-widgets")); ?></option>
                    </select>

                    <p class="description"><?php echo esc_attr(__("Show or hide time inputs in the widget", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_culture"><?php echo esc_attr(__("iFrame language", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_culture" id="parkingpro_booking_widgets_culture" value="<?php echo esc_attr($parkingpro_booking_widgets_culture); ?>" placeholder="nl-NL" class="regular-text" />
                    <p class="description"><?php echo esc_attr(__("Enter the language context for the MyParkingPro iFrame", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Display airport dropdown", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_display_airport_dropdown">
                        <select name="parkingpro_booking_widgets_display_airport_dropdown" id="parkingpro_booking_widgets_display_airport_dropdown">
                            <option value="hide" <?php selected( 'hide', $parkingpro_booking_widgets_display_airport_dropdown ); ?>><?php echo esc_attr(__("Hide airport dropdown", "parkingpro-booking-widgets")); ?></option>
                            <option value="display" <?php selected( 'display', $parkingpro_booking_widgets_display_airport_dropdown ); ?>><?php echo esc_attr(__("Display airport dropdown", "parkingpro-booking-widgets")); ?></option>
                        </select>
                    </label>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php echo esc_attr(__("Display car dropdown", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_display_car_dropdown">
                        <select name="parkingpro_booking_widgets_display_car_dropdown" id="parkingpro_booking_widgets_display_car_dropdown">
                            <option value="hide" <?php selected( 'hide', $parkingpro_booking_widgets_display_car_dropdown ); ?>><?php echo esc_attr(__("Hide car dropdown", "parkingpro-booking-widgets")); ?></option>
                            <option value="display" <?php selected( 'display', $parkingpro_booking_widgets_display_car_dropdown ); ?>><?php echo esc_attr(__("Display car dropdown", "parkingpro-booking-widgets")); ?></option>
                        </select>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_google_analytics_id"><?php echo esc_attr(__("Google Analytics ID", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_google_analytics_id" id="parkingpro_booking_widgets_google_analytics_id" value="<?php echo esc_attr($parkingpro_booking_widgets_google_analytics_id); ?>" placeholder="UA-12345-1" class="regular-text" />
                    <p class="description"><?php echo esc_attr(__("Enter the Google Analytics ID and add tracking to the MyParkingPro iFrame", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_append_google_analytics_cookie"><?php echo esc_attr(__("Google Analytics cookie", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <select name="parkingpro_booking_widgets_append_google_analytics_cookie" id="parkingpro_booking_widgets_append_google_analytics_cookie">
                        <option value="no" <?php selected( 'no', $parkingpro_booking_widgets_append_google_analytics_cookie ); ?>><?php echo esc_attr(__("Ignore Google Analytics cookie", "parkingpro-booking-widgets")); ?></option>
                        <option value="yes" <?php selected( 'yes', $parkingpro_booking_widgets_append_google_analytics_cookie ); ?>><?php echo esc_attr(__("Append Google Analytics cookie to iFrames", "parkingpro-booking-widgets")); ?></option>
                    </select>
                    <p class="description"><?php echo esc_attr(__("Append Google Analytics cookie (_ga) to the iFrame", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Remember widget data", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_remember_widget_fields">
                        <select name="parkingpro_booking_widgets_remember_widget_fields" id="parkingpro_booking_widgets_remember_widget_fields">
                            <option value="false" <?php selected( 'false', $parkingpro_booking_widgets_remember_widget_fields ); ?>><?php echo esc_attr(__("No, don't remember widget data", "parkingpro-booking-widgets")); ?></option>
                            <option value="true" <?php selected( 'true', $parkingpro_booking_widgets_remember_widget_fields ); ?>><?php echo esc_attr(__("Yes, remember widget fields", "parkingpro-booking-widgets")); ?></option>
                        </select>
                    </label>
                    <p class="description"><?php echo esc_attr(__("When a user enters data in the widget (dates, times), it's possible to rembember and reuse the data in the widget after reloading the page.", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_header_element_selector"><?php echo esc_attr(__("Header element selector(s)", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_header_element_selector" id="parkingpro_booking_widgets_header_element_selector" value="<?php echo esc_attr($parkingpro_booking_widgets_header_element_selector); ?>" placeholder="#header" class="regular-text" />
                    <p class="description"><?php echo esc_attr(__("Enter one or more header element jQuery selectors to correct the scroll positions in the MyParkingPro iFrame", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <h2>Header</h2>
                </th>
                <td></td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_form_title"><?php echo esc_attr(__("Default widget title", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_form_title" id="parkingpro_booking_widgets_form_title" value="<?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_form_title)); ?>" placeholder="" class="regular-text" required />
                    <p class="description"><?php echo esc_attr(__("Enter the default title for the widget", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Header background color", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_colors_header_background">
                        <input class="color-picker" type="text" name="parkingpro_booking_widgets_colors_header_background" id="parkingpro_booking_widgets_colors_header_background" value="<?php echo esc_attr($parkingpro_booking_widgets_colors_header_background); ?>" />
                    </label>
                    <p class="description"><?php echo esc_attr(__("The background color of the top part of the widget", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Header text color", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_colors_header_text">
                        <input class="color-picker" type="text" name="parkingpro_booking_widgets_colors_header_text" id="parkingpro_booking_widgets_colors_header_text" value="<?php echo esc_attr($parkingpro_booking_widgets_colors_header_text); ?>" />
                    </label>
                    <p class="description"><?php echo esc_attr(__("The text color of the widget title in the top part", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Border color", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_colors_border">
                        <input class="color-picker" type="text" name="parkingpro_booking_widgets_colors_border" id="parkingpro_booking_widgets_colors_border" value="<?php echo esc_attr($parkingpro_booking_widgets_colors_border); ?>" />
                    </label>
                    <p class="description"><?php echo esc_attr(__("Color of all the borders", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <h2><?php echo esc_attr(__("Titles and labels", "parkingpro-booking-widgets")); ?></h2>
                </th>
                <td></td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_section_airport_title"><?php echo esc_attr(__("Section airport title", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_section_airport_title" id="parkingpro_booking_widgets_section_airport_title" value="<?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_section_airport_title)); ?>" placeholder="" class="regular-text" />
                    <p class="description"><?php echo esc_attr(__("Title for the airport section", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_section_dates_title"><?php echo esc_attr(__("Section dates title", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_section_dates_title" id="parkingpro_booking_widgets_section_dates_title" value="<?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_section_dates_title)); ?>" placeholder="" class="regular-text" required />
                    <p class="description"><?php echo esc_attr(__("Title for the dates section", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_label_parkingdate"><?php echo esc_attr(__("Label parking date", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_label_parkingdate" id="parkingpro_booking_widgets_label_parkingdate" value="<?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_label_parkingdate)); ?>" placeholder="" class="regular-text" required />
                    <p class="description"><?php echo esc_attr(__("Label for the parking date", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_label_returndate"><?php echo esc_attr(__("Label return date", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_label_returndate" id="parkingpro_booking_widgets_label_returndate" value="<?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_label_returndate)); ?>" placeholder="" class="regular-text" required />
                    <p class="description"><?php echo esc_attr(__("Label for the return date", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Display services", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_display_services">
                        <select name="parkingpro_booking_widgets_display_services" id="parkingpro_booking_widgets_display_services">
                            <option value="display" <?php selected( 'display', $parkingpro_booking_widgets_display_services ); ?>><?php echo esc_attr(__("Yes, display services", "parkingpro-booking-widgets")); ?></option>
                            <option value="hide" <?php selected( 'hide', $parkingpro_booking_widgets_display_services ); ?>><?php echo esc_attr(__("No, hide services", "parkingpro-booking-widgets")); ?></option>
                        </select>
                    </label>
               
                    <?php

                    if( $parkingpro_booking_widgets_myparkingpro_url )
                    {
                        $parkingpro_booking_widgets_services = $this->get_myparkingpro_services($parkingpro_booking_widgets_myparkingpro_url);

                        if( count( $parkingpro_booking_widgets_services ) > 0 )
                        {
                            echo '<p class="description">' . __("Services to display", "parkingpro-booking-widgets") . ':</p>';

                            echo '<ul>';

                            foreach( $parkingpro_booking_widgets_services as $service ) 
                            {
                                echo '<li><label><input type="checkbox" name="parkingpro_booking_widgets_filter_services[]" value="' . $service["id"] . '"';

                                if( is_string($parkingpro_booking_widgets_filter_services) && strlen($parkingpro_booking_widgets_filter_services) > 0 && in_array($service['id'], explode(',', $parkingpro_booking_widgets_filter_services)) )
                                    echo " checked='checked' ";

                                echo ' /> ' . $service['name'] . '</label></li>';
                            }
                            
                            echo '</ul>';
                        }
                        else
                        {
                            echo '<p class="text-red">' . __("Error: there are no services available to display", "parkingpro-booking-widgets") . '</p>';
                        }
                    }
                    ?>
 
                </td>
            </tr> 

            <tr>
                <th scope="row"><?php echo esc_attr(__("Services in iFrame", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_force_single_service">
                        <select name="parkingpro_booking_widgets_force_single_service" id="parkingpro_booking_widgets_force_single_service">
                            <option value="false" <?php selected( 'false', $parkingpro_booking_widgets_force_single_service ); ?>><?php echo esc_attr(__("User can change services in the iFrame", "parkingpro-booking-widgets")); ?></option>
                            <option value="true" <?php selected( 'true', $parkingpro_booking_widgets_force_single_service ); ?>><?php echo esc_attr(__("Use the service from widget and hide services in the iFrame", "parkingpro-booking-widgets")); ?></option>
                        </select>
                    </label>
                    <p class="description"><?php echo esc_attr(__("Show all services in the MyParkingPro iFrame (the user can change the services in the iFrame) or load the iFrame with a pre-selected service and hide the service selection in the iFrame.", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_section_services_title"><?php echo esc_attr(__("Section services title", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_section_services_title" id="parkingpro_booking_widgets_section_services_title" value="<?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_section_services_title)); ?>" placeholder="" class="regular-text" required />
                    <p class="description"><?php echo esc_attr(__("Title for the services section", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_section_car_title"><?php echo esc_attr(__("Section car title", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_section_car_title" id="parkingpro_booking_widgets_section_car_title" value="<?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_section_car_title)); ?>" placeholder="" class="regular-text" />
                    <p class="description"><?php echo esc_attr(__("Title for the car section", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Section title color", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_colors_section_text">
                        <input class="color-picker" type="text" name="parkingpro_booking_widgets_colors_section_text" id="parkingpro_booking_widgets_colors_section_text" value="<?php echo esc_attr($parkingpro_booking_widgets_colors_section_text); ?>" />
                    </label>
                    <p class="description"><?php echo esc_attr(__("Text color for the section title", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Label text color", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_colors_label_text">
                        <input class="color-picker" type="text" name="parkingpro_booking_widgets_colors_label_text" id="parkingpro_booking_widgets_colors_label_text" value="<?php echo esc_attr($parkingpro_booking_widgets_colors_label_text); ?>" />
                    </label>
                    <p class="description"><?php echo esc_attr(__("Text color for the labels", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <h2><?php echo esc_attr(__("Button", "parkingpro-booking-widgets")); ?></h2>
                </th>
                <td></td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="parkingpro_booking_widgets_button_text"><?php echo esc_attr(__("Button text", "parkingpro-booking-widgets")); ?></label>
                </th>
                <td>
                    <input type="text" name="parkingpro_booking_widgets_button_text" id="parkingpro_booking_widgets_button_text" value="<?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_button_text)); ?>" placeholder="" class="regular-text" required />
                    <p class="description"><?php echo esc_attr(__("The button text", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Button background color", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_colors_button_background">
                        <input class="color-picker" type="text" name="parkingpro_booking_widgets_colors_button_background" id="parkingpro_booking_widgets_colors_button_background" value="<?php echo esc_attr($parkingpro_booking_widgets_colors_button_background); ?>" />
                    </label>
                    <p class="description"><?php echo esc_attr(__("Background color for the button", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Button text color", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_colors_button_text">
                        <input class="color-picker" type="text" name="parkingpro_booking_widgets_colors_button_text" id="parkingpro_booking_widgets_colors_button_text" value="<?php echo esc_attr($parkingpro_booking_widgets_colors_button_text); ?>" />
                    </label>
                    <p class="description"><?php echo esc_attr(__("Color for the button text", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Button hover background color", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_colors_button_hover_background">
                        <input class="color-picker" type="text" name="parkingpro_booking_widgets_colors_button_hover_background" id="parkingpro_booking_widgets_colors_button_hover_background" value="<?php echo esc_attr($parkingpro_booking_widgets_colors_button_hover_background); ?>" />
                    </label>
                    <p class="description"><?php echo esc_attr(__("Background color for the button hover", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Button hover text color", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_colors_button_hover_text">
                        <input class="color-picker" type="text" name="parkingpro_booking_widgets_colors_button_hover_text" id="parkingpro_booking_widgets_colors_button_hover_text" value="<?php echo esc_attr($parkingpro_booking_widgets_colors_button_hover_text); ?>" />
                    </label>
                    <p class="description"><?php echo esc_attr(__("Color for the button hover text", "parkingpro-booking-widgets")); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <h2><?php echo esc_attr(__("Affiliate tracking", "parkingpro-booking-widgets")); ?></h2>
                </th>
                <td></td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_attr(__("Affiliate tracker", "parkingpro-booking-widgets")); ?></th>
                <td>
                    <label for="parkingpro_booking_widgets_affiliate_tracking">
                        <select name="parkingpro_booking_widgets_affiliate_tracking" id="parkingpro_booking_widgets_affiliate_tracking">
                            <option value="hide" <?php selected( 'hide', $parkingpro_booking_widgets_affiliate_tracking ); ?>><?php echo esc_attr(__("No, don't use affiliate tracking", "parkingpro-booking-widgets")); ?></option>
                            <option value="tradedoubler" <?php selected( 'tradedoubler', $parkingpro_booking_widgets_affiliate_tracking ); ?>><?php echo esc_attr("Tradedoubler"); ?></option>
                        </select>
                    </label> 
                </td>
            </tr> 

        </tbody>
    </table>

    <input type="submit" value="<?php echo esc_attr(__('Save Changes')); ?>" class="button button-primary button-large">
</form>