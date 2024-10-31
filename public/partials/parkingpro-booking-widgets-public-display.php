<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.parkingpro.nl
 * @since      1.0.0
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/public/partials
 */

ob_start();

$arrivalDate = "";
$arrivalTime = "";
$departureDate = "";
$departureTime = "";

// Load dates and times from SESSION
if( $parkingpro_booking_widgets_remember_widget_fields === 'true' )
{
    if( isset($_SESSION['parkingpro_arrivaldate']) ) 
    {
        $session_arrivalDate = date_create_from_format('Y-m-d', $_SESSION['parkingpro_arrivaldate']);
        if( $session_arrivalDate->format('U') > time() ) 
        {
            $arrivalDate = $session_arrivalDate->format('d-m-Y');

            if( isset($_SESSION['parkingpro_arrivaltime']) )
                $arrivalTime = $_SESSION['parkingpro_arrivaltime'];
        }
    }

    if( isset($_SESSION['parkingpro_departuredate']) ) 
    {
        $session_departureDate = date_create_from_format('Y-m-d', $_SESSION['parkingpro_departuredate']);
        if( $session_departureDate->format('U') > time() ) 
        {
            $departureDate = $session_departureDate->format('d-m-Y');

            if( isset($_SESSION['parkingpro_departuretime']) )
                $departureTime = $_SESSION['parkingpro_departuretime'];
        }
    }
}

// Load dates and times from URL parameters
if( isset($_GET['arrivalDate']) && strtotime($_GET['arrivalDate']) > time() )
    $arrivalDate = date_format(date_create_from_format('Y-m-d', esc_attr($_GET['arrivalDate'])), 'd-m-Y');

if( isset($_GET['arrivalTime']) )
    $arrivalTime = esc_attr($_GET['arrivalTime']);

if( isset($_GET['departureDate']) && strtotime($_GET['departureDate']) > time() )
    $departureDate = date_format(date_create_from_format('Y-m-d', esc_attr($_GET['departureDate'])), 'd-m-Y');

if( isset($_GET['departureTime']) )
    $departureTime = esc_attr($_GET['departureTime']);

if( $parkingpro_booking_widgets_config !== NULL )
    echo '<script>var parkingpro_booking_widgets_config = ' . json_encode($parkingpro_booking_widgets_config) . ';</script>';

?>

<style type="text/css" media="screen">
    <?php if( $parkingpro_booking_widgets_colors_header_background ) { ?> 
    .ppwidget__header {
        background: <?php echo esc_attr($parkingpro_booking_widgets_colors_header_background); ?>;
    }
    <?php } ?>

    <?php if( $parkingpro_booking_widgets_colors_button_background ) { ?>
    .ppwidget__button {
        background: <?php echo esc_attr($parkingpro_booking_widgets_colors_button_background); ?> !important;
    }
    <?php } ?>

    <?php if( $parkingpro_booking_widgets_colors_button_hover_background ) { ?>
    .ppwidget__button:hover {
        background: <?php echo esc_attr($parkingpro_booking_widgets_colors_button_hover_background); ?> !important;
    }
    <?php } ?>

    <?php if( $parkingpro_booking_widgets_colors_border ) { ?>
    .ppwidget__container,
    .ppwidget input.ppwidget__form__input,
    .ppwidget select.ppwidget__form__select,
    .ppwidget__form__serviceradio {
        border-color: <?php echo esc_attr($parkingpro_booking_widgets_colors_border); ?>;
    }

    .ppwidget__form__serviceradio.checked:after {
        background-color: <?php echo esc_attr($parkingpro_booking_widgets_colors_border); ?>;
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

    <?php if( $parkingpro_booking_widgets_colors_label_text ) { ?> 
    .ppwidget__form__label,
    .ppwidget__services__option__title,
    .ppwidget__services__option__price { 
        color: <?php echo esc_attr($parkingpro_booking_widgets_colors_label_text); ?>;
     }
    <?php } ?>

    <?php if( $parkingpro_booking_widgets_colors_button_text ) { ?> 
    .ppwidget__button { 
        color: <?php echo esc_attr($parkingpro_booking_widgets_colors_button_text); ?> !important;
     }
    <?php } ?>

    <?php if( $parkingpro_booking_widgets_colors_button_hover_text ) { ?> 
    .ppwidget__button:hover { 
        color: <?php echo esc_attr($parkingpro_booking_widgets_colors_button_hover_text); ?> !important;
     }
    <?php } ?>
</style>

<script type="text/javascript">
    var parkingpro_booking_widgets_myparkingpro_service_ids = '<?php echo implode(',', $parkingpro_booking_widgets_myparkingpro_service_ids); ?>';
</script>

<div class="ppwidget ppwidget__container 
    <?php 
    if( $parkingpro_booking_widgets_display_services === 'hide' || count($parkingpro_booking_widgets_myparkingpro_services) === 0 ) 
        echo ' ppwidget--serviceshidden '; 
    if( $parkingpro_booking_widgets_size === 'medium' )
        echo ' ppwidget--medium ppwidget--medium--forced ';
    elseif( $parkingpro_booking_widgets_size === 'small' )
        echo ' ppwidget--small ppwidget--small--forced ';
    elseif( $parkingpro_booking_widgets_size === 'row' )
        echo ' ppwidget--row ppwidget--row--forced';
    ?>
" id="ppwidget">
    <form method="get" action="<?php echo esc_url_raw($form_destination_url); ?>" id="ppwidget__form" autocomplete="off">
        <div class="ppwidget__header">
            <div class="ppwidget__header__title">

                <?php 
                if( $parkingpro_booking_widgets_colors_header_text ) 
                    $svg_color = $parkingpro_booking_widgets_colors_header_text;
                else
                    $svg_color = '#FFFFFF';
                ?>

                <div class="ppwidget__header__title__logo"><svg width="100%" height="100%" viewBox="0 0 652 592" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:1.41421;"><g><path d="M435.508,236.908c19.296,-19.296 28.946,-42.533 28.946,-69.712c0,-26.904 -9.65,-50.071 -28.946,-69.504c-19.296,-19.434 -42.533,-29.15 -69.708,-29.15l-98.254,0l0,197.312l98.254,0c27.175,0 50.412,-9.65 69.708,-28.946Z" style="fill:<?php echo esc_attr($svg_color); ?>;fill-rule:nonzero;"/><path d="M550.112,29.542c30.671,39.791 46.013,85.671 46.013,137.654c0,63.329 -22.554,117.475 -67.667,162.454c-45.116,44.979 -99.333,67.467 -162.662,67.467l-98.246,0l0,189.35c18.904,3.412 38.375,5.2 58.262,5.2c179.946,0 325.821,-145.871 325.821,-325.813c0,-93.029 -38.991,-176.946 -101.521,-236.312Z" style="fill:<?php echo esc_attr($svg_color); ?>;fill-rule:nonzero;"/></g><clipPath id="_clip1"><path d="M0.717,265.921c0,108.633 53.291,204.808 135.154,263.846l0,-527.7c-81.863,59.037 -135.154,155.216 -135.154,263.854Z"/></clipPath><g clip-path="url(#_clip1)"><path d="M-52.675,-227.904l0,950.546l389.75,0l0,-316.229l0,-1.855l0,-314.379l0,-1.854l0,-316.229l-389.75,0Z" style="fill:<?php echo esc_attr($svg_color); ?>;fill-rule:nonzero;"/></g></svg></div>
                <?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_form_title)); ?>
            </div>
        </div>
        <div class="ppwidget__main">
            <div class="ppwidget__form">
                <?php if( $parkingpro_booking_widgets_display_airport_dropdown === 'display' && $parkingpro_booking_widgets_fixed_airport === NULL ) : ?>
                <div class="ppwidget__airport">
                    <div class="ppwidget__section__title"><?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_section_airport_title)); ?></div>
                    <select class="ppwidget__form__select" name="airport" id="airport">
                        <option value=""><?php echo esc_attr(__("Select airport", "parkingpro-booking-widgets")); ?></option>
                        <?php foreach($parkingpro_booking_widgets_airports as $country => $airports) {

                            if( count($parkingpro_booking_widgets_airports) > 1 )
                                echo '<optgroup label="' . $country . '">';

                            foreach($airports as $airport) {
                            echo '<option value="' . $airport['id'] . '"';

                            if( isset($_GET['airport']) ) 
                            {
                                if( esc_attr($_GET['airport']) === $airport['id']  )
                                    echo ' selected="selected" ';
                            }
                            elseif( $parkingpro_booking_widgets_remember_widget_fields === 'true' && isset($_SESSION['parkingpro_airport']) && $_SESSION['parkingpro_airport'] === $airport['id'] )
                            {
                                echo ' selected="selected" ';
                            }

                            echo '>' . $airport['name'] . '</option>';                        
                            }

                            if( count($parkingpro_booking_widgets_airports) > 1 )
                                echo '</optgroup>';
                        } ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="ppwidget__dates">
                    <div class="ppwidget__section__title"><?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_section_dates_title)); ?></div>
                    <div class="ppwidget__dates__parking">
                        <label for="ppwidget__parkingdate" class="ppwidget__form__label"><?php echo esc_attr($parkingpro_booking_widgets_label_parkingdate); ?>*</label>
                        <div class="ppwidget__row">
                            <div class="ppwidget__column ppwidget__column--double">
                                <input type="text" autocomplete="off" class="ppwidget__form__input" name="arrivalDate_display" id="ppwidget__parkingdate" value="<?php echo $arrivalDate; ?>" placeholder="<?php echo esc_attr(__("dd-mm-yyyy", "parkingpro-booking-widgets")); ?>">
                                <input type="hidden" class="ppwidget__form__input" name="arrivalDate" id="ppwidget__parkingdate__alt" value="" placeholder="">
                            </div>
                            <?php if( $parkingpro_booking_widgets_hide_times !== "yes") { ?>
                            <div class="ppwidget__column">
                                <input type="text" autocomplete="off" placeholder="--:--" class="ppwidget__form__input" name="arrivalTime" id="ppwidget__parkingtime" value="<?php echo $arrivalTime; ?>">
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="ppwidget__dates__return">
                        <label for="ppwidget__returndate" class="ppwidget__form__label"><?php echo esc_attr($parkingpro_booking_widgets_label_returndate); ?>*</label>
                        <div class="ppwidget__row">
                            <div class="ppwidget__column ppwidget__column--double">
                                <input type="text" autocomplete="off" class="ppwidget__form__input" name="departureDate_display" id="ppwidget__returndate" value="<?php echo $departureDate; ?>" placeholder="<?php echo esc_attr(__("dd-mm-yyyy", "parkingpro-booking-widgets")); ?>">
                                <input type="hidden" class="ppwidget__form__input" name="departureDate" id="ppwidget__returndate__alt" value="" placeholder="">
                            </div>
                            <?php if( $parkingpro_booking_widgets_hide_times !== "yes") { ?>
                            <div class="ppwidget__column">
                                <input type="text" autocomplete="off" placeholder="--:--" class="ppwidget__form__input" name="departureTime" id="ppwidget__returntime" value="<?php echo $departureTime; ?>">
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php if( $parkingpro_booking_widgets_display_services === 'display' && is_array($parkingpro_booking_widgets_filter_services) && count($parkingpro_booking_widgets_myparkingpro_services) > 0 ) : ?>
                <div class="ppwidget__services">
                    <div class="ppwidget__section__title"><?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_section_services_title)); ?></div>

                    <div class="ppwidget__services__options">
                        <?php foreach( $parkingpro_booking_widgets_myparkingpro_services as $index => $parkingpro_booking_widgets_service ) : ?>

                        <?php if( !in_array($parkingpro_booking_widgets_service['id'], $parkingpro_booking_widgets_filter_services) ) continue; ?>
                        <div class="ppwidget__services__option" data-airport="<?php echo $parkingpro_booking_widgets_service['airport']['id']; ?>" data-location="<?php echo $parkingpro_booking_widgets_service['id']; ?>">
                            <label class="ppwidget__form__serviceradio <?php if($index === 0 || ($parkingpro_booking_widgets_remember_widget_fields === 'true' && isset($_SESSION['parkingpro_locationid']) && $_SESSION['parkingpro_locationid'] === $parkingpro_booking_widgets_service['id'])) echo 'checked'; ?>" for="<?php echo esc_attr($parkingpro_booking_widgets_service['id']); ?>">
                                <input type="radio" name="locationId" id="<?php echo esc_attr($parkingpro_booking_widgets_service['id']); ?>" value="<?php echo esc_attr($parkingpro_booking_widgets_service['id']); ?>" 
                                    <?php 
                                    if($index === 0) 
                                        echo ' checked="checked" ';
                                    elseif( $parkingpro_booking_widgets_remember_widget_fields === 'true' && isset($_SESSION['parkingpro_locationid']) && $_SESSION['parkingpro_locationid'] === $parkingpro_booking_widgets_service['id'] )
                                        echo ' checked="checked" ';
                                    ?>
                                > 
                                <div class="ppwidget__services__option__title">
                                    <?php echo esc_attr($parkingpro_booking_widgets_service['name']); ?>
                                </div>
                                
                                <div class="ppwidget__services__option__image">
                                    <?php if( $parkingpro_booking_widgets_service['logoUrl'] ) : ?>
                                    <img src="<?php echo esc_url_raw($parkingpro_booking_widgets_service['logoUrl']); ?>" alt="<?php echo esc_attr($parkingpro_booking_widgets_service['name']); ?>" />
                                    <?php endif; ?>
                                </div>

                                <div class="ppwidget__services__option__price"><span></span></div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if( $parkingpro_booking_widgets_display_car_dropdown === 'display' ) : ?>
                <div class="ppwidget__car">
                    <div class="ppwidget__section__title"><?php echo wp_unslash(esc_attr($parkingpro_booking_widgets_section_car_title)); ?></div>
                    
                    <select class="ppwidget__form__select" name="car" id="car">
                        
                        <?php foreach ($parkingpro_booking_widgets_car_options as $car_option) {
                            
                            echo '<option value="' . $car_option . '"';

                            if( isset($_GET['car']) ) 
                            {
                                if( esc_attr($_GET['car']) === $car_option )
                                    echo ' selected="selected" ';
                            }
                            elseif( $parkingpro_booking_widgets_remember_widget_fields === 'true' && isset($_SESSION['parkingpro_car']) && $_SESSION['parkingpro_car'] === $car_option )
                                echo ' selected="selected" ';

                            echo '>' . $car_option . ' ';
                            if( $car_option === '1' )
                                echo esc_attr(__("car", "parkingpro-booking-widgets"));
                            else
                                echo esc_attr(__("cars", "parkingpro-booking-widgets"));
                            echo '</option>';

                        } ?>

                    </select>
                </div>
                <?php endif; ?>
            </div>

            <input type="hidden" id="ppwidget__visible_services_list" name="visible_services" value="<?php echo implode(',', $parkingpro_booking_widgets_myparkingpro_service_ids); ?>" style="width: 100%;" />
        </div>
        <div class="ppwidget__footer">
            <?php if( $parkingpro_booking_widgets_filter_services ) { ?>
            <input type="hidden" value="<?php echo esc_attr(implode(',', $parkingpro_booking_widgets_filter_services)); ?>" id="showLocations" name="showLocations">
            <?php } ?>
            <?php if( $parkingpro_booking_widgets_fixed_airport !== NULL ) { ?>
            <input type="hidden" value="<?php echo esc_attr($parkingpro_booking_widgets_fixed_airport); ?>" id="airport" name="airport">
            <?php } ?>
            <button type="submit" class="ppwidget__button"><?php echo esc_attr($parkingpro_booking_widgets_button_text); ?></button>
        </div>
    </form>
</div>

<?php
return ob_get_clean();
?>