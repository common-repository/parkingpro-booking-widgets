<?php

/**
 * Provide a public-facing view for the thank you page
 *
 * This file is used to markup the public-facing aspects of the [pp_thank_you] shortcode.
 *
 * @link       https://www.parkingpro.nl
 * @since      1.2.9
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/public/partials
 */

ob_start();

$wp_timezone = wp_timezone_string();
$timezone = NULL;

if( $wp_timezone && $wp_timezone[0] === "+" )
    $timezone = 'GMT' . $wp_timezone;
else
    $timezone = $wp_timezone;

$DateFormatter = new IntlDateFormatter(get_locale(), IntlDateFormatter::LONG, IntlDateFormatter::SHORT, $timezone);
$NumberFormatter = new NumberFormatter(get_locale(), NumberFormatter::CURRENCY);
?>

<style type="text/css" media="screen">

    <?php if( $parkingpro_booking_widgets_colors_header_background ) { ?> 
    .pp__thankyou__table__rowfilled td {
        background: <?php echo esc_attr($parkingpro_booking_widgets_colors_header_background); ?>;
    }
    <?php } ?>

    <?php if( $parkingpro_booking_widgets_colors_border ) { ?>
    .pp__thankyou__table__rowborder {
        border-color: <?php echo esc_attr($parkingpro_booking_widgets_colors_border); ?>;
    }

    .pp__thankyou__table {
        box-shadow: 0 0 0 1px <?php echo esc_attr($parkingpro_booking_widgets_colors_border); ?>;
    }
    <?php } ?>

    <?php if( $parkingpro_booking_widgets_colors_header_text ) { ?>
    .ppwidget__header__title {
        color: <?php echo esc_attr($parkingpro_booking_widgets_colors_header_text); ?>;
    }
    <?php } ?>

    <?php if( $parkingpro_booking_widgets_colors_section_text ) { ?> 
    .ppwidget__section__title { 
        color: <?php echo esc_attr($parkingpro_booking_widgets_colors_section_text); ?>;
     }
    <?php } ?>

</style>

<div class="pp__thankyou ppwidget__container pp__thankyou__container" id="pp__thankyou">

    <div class="pp__thankyou__main">
        <div class="ppwidget__main__column">
            <div class="ppwidget__section__title"><?php echo esc_attr(__("Your booking has been completed", "parkingpro-booking-widgets")); ?></div>
            <p><?php echo esc_attr(__("You will receive the booking confirmation by e-mail. Print the confirmation and take it with you on a trip.", "parkingpro-booking-widgets")); ?></p>
        </div>
        <?php if( isset($_GET['arrivalDate']) && isset($_GET['departureDate']) && isset($_GET['totalWithTax']) ) { ?>
        <div class="ppwidget__main__column">
            <table class="pp__thankyou__table">
                <tr class="pp__thankyou__table__rowfilled">
                    <td><strong class="ppwidget__header__title"><?php echo esc_attr(__("Your booking", "parkingpro-booking-widgets")); ?></strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td><?php echo esc_attr(__("Service", "parkingpro-booking-widgets")); ?></td>
                    <td><?php echo esc_attr($_GET['location']); ?></td>
                </tr>
                <tr>
                    <td><?php echo esc_attr(__("Parking date", "parkingpro-booking-widgets")); ?></td>
                    <td>
                        <?php 
                        $arrivalDate = new DateTime(esc_attr($_GET['arrivalDate']));
                        echo $DateFormatter->format($arrivalDate);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo esc_attr(__("Return date", "parkingpro-booking-widgets")); ?></td>
                    <td>
                        <?php 
                        $departureDate = new DateTime(esc_attr($_GET['departureDate']));
                        echo $DateFormatter->format($departureDate);
                        ?>
                    </td>
                </tr>
                <?php if( isset($_GET['carCount']) ) { ?>
                <tr>
                    <td><?php echo esc_attr(__("Cars", "parkingpro-booking-widgets")); ?></td>
                    <td><?php echo esc_attr($_GET['carCount']); ?></td>
                </tr>
                <?php } ?>
                <tr class="pp__thankyou__table__rowborder">
                    <td></td>
                    <td></td>
                </tr>
                <tr class="pp__thankyou__table__rowtotal">
                    <td><strong><?php echo esc_attr(__("Total amount", "parkingpro-booking-widgets")); ?></strong></td>
                    <td><strong><?php echo $NumberFormatter->formatCurrency(esc_attr($_GET['totalWithTax']), "EUR"); ?></strong></td>
                </tr>
            </table>
        </div>
        <?php } ?>
    </div>

</div>

<?php
return ob_get_clean();
?>