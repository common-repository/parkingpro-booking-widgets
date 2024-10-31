<?php

/**
 * Provide a public-facing view for the product selection shortocde
 *
 * This file is used to markup the public-facing aspects of the [product_selection] shortcode.
 *
 * @link       https://www.parkingpro.nl
 * @since      1.1.0
 *
 * @package    ParkingPro_Booking_Widgets
 * @subpackage ParkingPro_Booking_Widgets/public/partials
 */

ob_start();

$NumberFormatter = new NumberFormatter( get_locale(), NumberFormatter::CURRENCY );

?>

<style type="text/css" media="screen">

    <?php if( $parkingpro_booking_widgets_colors_button_background ) { ?>
    .pp__products__block__availability__button,
    .pp__products__block__availability__button:hover,
    .pp__products__filters__filter--active {
        background: <?php echo esc_attr($parkingpro_booking_widgets_colors_button_background); ?>;
    }

    .pp__products__block__content__tag { 
        color: <?php echo esc_attr($parkingpro_booking_widgets_colors_button_background); ?>;
    }

    .pp__products__block__content__tag {
        border-color: <?php echo esc_attr($parkingpro_booking_widgets_colors_button_background); ?>;
    }
    <?php } ?>

    <?php if( $parkingpro_booking_widgets_colors_button_text ) { ?> 
    .pp__products__block__availability__button,
    .pp__products__filters__filter--active { 
        color: <?php echo esc_attr($parkingpro_booking_widgets_colors_button_text); ?>;
    }
    <?php } ?>
</style>

<div class="pp__productselection pp__productselection__container" id="pp__productselection">
    
    <?php if(count($parkingpro_booking_widgets_myparkingpro_services) > 0 && isset($_GET['arrivalDate']) && isset($_GET['departureDate']) ) : ?>

    <div class="pp__products__query">
        <div><?php echo esc_attr(__("Your options for the period you have selected", "parkingpro-booking-widgets")); ?></div>
        <div class="pp__products__query__box">
            <?php 
            echo date('d-m-Y', strtotime(esc_attr($_GET['arrivalDate'])));
            if( isset($_GET['arrivalTime']) ) 
                echo ' ' . esc_attr($_GET['arrivalTime']); 
            echo ' - ';
            echo date('d-m-Y', strtotime(esc_attr($_GET['departureDate'])));
            if( isset($_GET['departureTime']) )
                echo ' ' . esc_attr($_GET['departureTime']);
            echo '<br />';
            if( $airport_name !== NULL ) { 
                echo esc_attr($airport_name) . ' | '; 
            }
            echo esc_attr($totalDays) . ' ';
            echo esc_attr(__("days", "parkingpro-booking-widgets"));

            if( isset($_GET['car']) && $_GET['car'] !== '' ) {
                echo ' | ';
                echo esc_attr($_GET['car'])  . ' ';
                echo esc_attr(__("cars", "parkingpro-booking-widgets")); 
            }
            ?>
        </div>
    </div>

    <div class="pp__products__filter__sorting__container">
        <?php if( count($parkingpro_booking_widgets_tags) > 0 ) : ?>
        
        <div class="pp__products__filters">

            <div class="pp__products__filters__header ppwidget__section__title"><?php echo esc_attr(__("Filter search results", "parkingpro-booking-widgets")); ?></div>

            <?php foreach($parkingpro_booking_widgets_tags as $tag) : ?>
                <label for="tag_<?php echo esc_attr($tag['tagId']); ?>" class="pp__products__filters__filter js-tag-filter">
                    <input type="checkbox" id="tag_<?php echo esc_attr($tag['tagId']); ?>" name="tag_<?php echo esc_attr($tag['tagId']); ?>" value="<?php echo esc_attr($tag['tagId']); ?>"> <?php echo esc_attr($tag['tagName']); ?>
                </label>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>

        <div class="pp__products__sorting">
            <div class="pp__products__sorting__header ppwidget__section__title"><?php echo esc_attr(__("Sort by", "parkingpro-booking-widgets")); ?></div>
            <select name="" class="pp__products__sorting__select js-products-sorting">
                <option value="price"><?php echo esc_attr(__("Price", "parkingpro-booking-widgets")); ?></option>
                <option value="name"><?php echo esc_attr(__("Name", "parkingpro-booking-widgets")); ?></option>
            </select>
        </div>  
    </div>  

    <ul class="pp__products__list">
        <?php foreach( $parkingpro_booking_widgets_myparkingpro_services as $service ) : ?>

            <?php if( $parkingpro_booking_widgets_filter_services === NULL ) continue; ?>

            <?php if( !in_array($service['id'], $parkingpro_booking_widgets_filter_services) ) continue; ?>

            <?php 
            // Generate tags data attribute
            $tags = '';
            foreach ($service['appliedTags'] as $appliedTag) {
                $tags .= $appliedTag['tagId'] . ',';
            }
            ?>

            <?php 
            $arrivalDate = $_GET['arrivalDate'];
            $departureDate = $_GET['departureDate'];

            if( isset($_GET['arrivalTime']) )
                $arrivalDate .= ' ' . $_GET['arrivalTime'] . ':00';

            if( isset($_GET['departureTime']) )
                $departureDate .= ' ' . $_GET['departureTime'] . ':00';

            $service_price_data = $this->pp_get_location_price($service['id'], $arrivalDate, $departureDate); 
            ?>
            <li class="" data-tags="<?php echo $tags; ?>" data-price="<?php echo esc_attr($service_price_data['totalWithTax']); ?>" data-name="<?php echo esc_attr($service['name']); ?>">
                <form method="get" action="<?php echo esc_url_raw($form_destination_url); ?>" class="pp__productselection__form" autocomplete="off">

                    <?php if( isset($_GET['arrivalDate']) && $_GET['arrivalDate'] !== '' ) { ?>
                        <input type="hidden" value="<?php echo esc_attr($_GET['arrivalDate']); ?>" name="arrivalDate">
                    <?php } ?>
                    <?php if( isset($_GET['arrivalTime']) && $_GET['arrivalTime'] !== '' ) { ?>
                        <input type="hidden" value="<?php echo esc_attr($_GET['arrivalTime']); ?>" name="arrivalTime">
                    <?php } ?>
                    <?php if( isset($_GET['departureDate']) && $_GET['departureDate'] !== '' ) { ?>
                        <input type="hidden" value="<?php echo esc_attr($_GET['departureDate']); ?>" name="departureDate">
                    <?php } ?>
                    <?php if( isset($_GET['departureTime']) && $_GET['departureTime'] !== '' ) { ?>
                        <input type="hidden" value="<?php echo esc_attr($_GET['departureTime']); ?>" name="departureTime">
                    <?php } ?>
                    <?php if( isset($_GET['car']) && $_GET['car'] !== '' ) { ?>
                        <input type="hidden" value="<?php echo esc_attr($_GET['car']); ?>" name="car">
                    <?php } ?>
                    <?php if( isset($service['id']) && $service['id'] !== '' ) { ?>
                        <input type="hidden" value="<?php echo esc_attr($service['id']); ?>" name="locationId">
                    <?php } ?>
                    <?php if( $parkingpro_booking_airport_services ) { ?>
                        <input type="hidden" value="<?php echo esc_attr(implode(',', $parkingpro_booking_airport_services)); ?>" id="showLocations" name="showLocations">
                    <?php } elseif( isset($_GET['showLocations']) && $_GET['showLocations'] !== '' ) { ?>
                        <input type="hidden" value="<?php echo esc_attr($_GET['showLocations']); ?>" name="showLocations">
                    <?php } ?>

                    <div class="pp__products__block">
                        <div class="pp__products__block__image">
                            <?php if( $service['logoUrl'] ) { ?>
                            <img src="<?php echo esc_attr($service['logoUrl']); ?>" alt="<?php echo esc_attr($service['name']); ?>" />
                            <?php } ?>
                        </div>
                        <div class="pp__products__block__content">
                            <div class="pp__products__block__content__title"><?php echo esc_attr($service['name']); ?></div>
                            
                            <?php if( $service['appliedTags'] && count($service['appliedTags']) ) {
                                echo '<div class="pp__products__block__content__tags">';

                                $service_tags = $service['appliedTags'];

                                usort($service_tags, function ($item1, $item2) {
                                    if ($item1['tagName'] == $item2['tagName']) return 0;
                                    return $item1['tagName'] < $item2['tagName'] ? -1 : 1;
                                });

                                foreach ($service_tags as $serviceTag) {
                                    echo '<span class="pp__products__block__content__tag">' . $serviceTag['tagName'] . '</span>';
                                }
                                echo '</div>';
                            } ?>

                            <p><?php echo esc_attr($service['description']); ?></p>

                            <?php
                            if( isset($service['extra']) ) {
                                if( isset($service['extra']['hide_more_info']['value']) && ($service['extra']['hide_more_info']['value'] === '1' || $service['extra']['hide_more_info']['value'] === 1) ) {
                                    // Hide the button
                                } elseif( isset($service['extra']['more_info_as_link']['value']) && ($service['extra']['more_info_as_link']['value'] === '1' || $service['extra']['more_info_as_link']['value'] === 1) && isset($service['extra']['more_info_link']['value']) ) { ?>
                                    <a href="<?php echo esc_attr($service['extra']['more_info_link']['value']); ?>" target="_blank" title="<?php echo esc_attr(__("More info", "parkingpro-booking-widgets")); ?>" class="pp__products__block__content__button"><?php echo esc_attr(__("More info", "parkingpro-booking-widgets")); ?></a>
                                <?php } elseif( isset($service['extra']['story_title']['value']) ) { ?>
                                    <a href="#" title="<?php echo esc_attr(__("More info", "parkingpro-booking-widgets")); ?>" class="pp__products__block__content__button js-product-more-info"><?php echo esc_attr(__("More info", "parkingpro-booking-widgets")); ?></a>
                                <?php }
                            }                        
                            ?>
                        </div>
                        <div class="pp__products__block__metadata">
                            <?php if( isset($service['extra']) && isset($service['extra']['walking_distance']['value']) ) { ?>
                            <div>
                                <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyB3aWR0aD0iMTNweCIgaGVpZ2h0PSIyNXB4IiB2aWV3Qm94PSIwIDAgMTMgMjUiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+ICAgICAgICA8dGl0bGU+V2FsazwvdGl0bGU+ICAgIDxkZXNjPkNyZWF0ZWQgd2l0aCBTa2V0Y2guPC9kZXNjPiAgICA8ZGVmcz48L2RlZnM+ICAgIDxnIGlkPSJGaW5hbC0xLjAiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPiAgICAgICAgPGcgaWQ9IkxvY2F0aWUtLS1FaW5kaG92ZW4iIHRyYW5zZm9ybT0idHJhbnNsYXRlKC04NzUuMDAwMDAwLCAtMTIxMy4wMDAwMDApIiBmaWxsPSIjMzEzMDJFIj4gICAgICAgICAgICA8ZyBpZD0icDYwMCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMjA5LjAwMDAwMCwgMTE2OC4wMDAwMDApIj4gICAgICAgICAgICAgICAgPGcgaWQ9IndhbGstKy1jYWwiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDY1NS4wMDAwMDAsIDQ1LjAwMDAwMCkiPiAgICAgICAgICAgICAgICAgICAgPGcgaWQ9IldhbGsiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDExLjAwMDAwMCwgMC4wMDAwMDApIj4gICAgICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNNS44NTQwOTM5Niw0LjAyNTE5OTI0IEM2LjM5NTA1MDk2LDQuMDA3NTY3NjIgNi44NDg3ODg5MiwzLjc5NTc3NjMzIDcuMjE1MjU1NDgsMy4zODk4MjUzOSBDNy41ODE3MjIwNCwyLjk4Mzg3NDQ1IDcuNzU2MjEyNDgsMi41MDczNDQwNiA3LjczODc3OTE0LDEuOTYwMjM0MjMgQzcuNzIxMzQ1OCwxLjQxMzAxODUgNy41MjA2MjY4MywwLjk0NTMzMDM5NCA3LjEzNjcyNjkzLDAuNTU3MTE2OTcyIEM2Ljc1MjgyNzAzLDAuMTY4ODUwNjAxIDYuMjkwMzQ2MjIsLTAuMDE2NDY2NzcyMyA1Ljc0OTM4OTIyLDAuMDAxMTY0ODUyMDYgQzUuMjA4NDMyMjIsMC4wMTg3OTY0NzY0IDQuNzU0Njk0MjYsMC4yMzA1ODc3NiA0LjM4ODIyNzcsMC42MzY1Mzg3MDMgQzQuMDIxNzYxMTMsMS4wNDI0ODk2NSAzLjgzODUyNzg1LDEuNTE5MDIwMDMgMy44Mzg1Mjc4NSwyLjA2NjEyOTg3IEMzLjgzODUyNzg1LDIuNjEzMjM5NyA0LjAzOTE5NDQ3LDMuMDgwOTgwNzUgNC40NDA1ODAwNiwzLjQ2OTI0NzEyIEM0Ljg0MTk2NTY2LDMuODU3NTEzNDkgNS4zMTMxMzY5NSw0LjA0MjgzMDg3IDUuODU0MDkzOTYsNC4wMjUxOTkyNCBMNS44NTQwOTM5Niw0LjAyNTE5OTI0IFoiIGlkPSJTaGFwZSI+PC9wYXRoPiAgICAgICAgICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xMi40NzY2NjgzLDEwLjcyMzA5ODYgTDkuNTk3Mjg4MTUsOS4xMzQ2NjM5NiBMNy4yNDE0MzE2Niw1LjMyMjQyMDg1IEM2Ljc4NzY5MzcsNC43NTc2MjY0NSA2LjIyOTMwMzM2LDQuNDc1MjU1NzIgNS41NjYxNTU5NCw0LjQ3NTI1NTcyIEM1LjExMjQxNzk4LDQuNDc1MjU1NzIgNC42NzYxNjU3MSw0LjY2OTQxNTM4IDQuMjU3MzQ2NzgsNS4wNTc2ODE3NSBMMC42OTczODU4NzIsOC43MTEwODEzOSBDMC41OTI2ODExMzksOC44NTIyOTMyMyAwLjUyMjg5NTQzNSw5LjAxMTEzNjY5IDAuNDg3OTc2NDA3LDkuMTg3NjExNzggTDAuMDE2ODA1MTA5NiwxMy4yNjQ1OTQgTDAuMDE2ODA1MTA5NiwxMy4zNzA0ODk2IEMwLjAxNjgwNTEwOTYsMTMuNjE3NTQ0MiAwLjEwNDA3NjUwNCwxMy44MjkzMzU0IDAuMjc4NTY2OTQxLDE0LjAwNTg2MzUgQzAuNDUzMDU3Mzc4LDE0LjE4MjI4NTYgMC42NjI0NjY4NDQsMTQuMjcwNjAyNiAwLjkwNjc5NTMzNywxNC4yNzA2MDI2IEMxLjE1MTEyMzgzLDE0LjI3MDYwMjYgMS4zNTE3OTA0NSwxNC4xODIyODU2IDEuNTA4ODQ3NTUsMTQuMDA1ODYzNSBDMS42NjU5MDQ2NSwxMy44MjkzMzU0IDEuNzYxODY2NTQsMTMuNjM1MjI4NyAxLjc5Njc4NTU2LDEzLjQyMzQzNzUgTDIuMTYzMjUyMTMsOS45Mjg4ODEyNyBMMy40MTk3MDg5Miw4LjY1ODEzMzU3IEwyLjI2Nzk1Njg2LDE4LjQwMDUzMjYgTDAuMjI2MjE0NTc1LDIzLjAwNzA0NiBDMC4xNTY0Mjg4NzEsMjMuMjE4ODM3MyAwLjEyMTUwOTg0MiwyMy40MTI1MjA0IDAuMTIxNTA5ODQyLDIzLjU4OTU3NzkgQzAuMTIxNTA5ODQyLDIzLjk3NzI2MTkgMC4yNTIzOTA3NTgsMjQuMzA0MzczNSAwLjUxNDE1MjU5LDI0LjU2OTExMjYgQzAuNzc1OTE0NDIxLDI0LjgzMzg1MTcgMS4wOTg3NzE0NiwyNC45NDg0MzA4IDEuNDgyNjcxMzcsMjQuOTEzMjczNCBDMi4wMDYxOTUwMywyNC45MTMyNzM0IDIuMzkwMDk0OTMsMjQuNjY1NTgzNSAyLjYzNDQyMzQzLDI0LjE3MjAwMzkgTDQuODMzMjIyODEsMTkuMTk0ODAyOSBDNC44MzMyMjI4MSwxOS4xNTkzMjc4IDQuODUwNjU2MTUsMTkuMDk3NjQzNiA0Ljg4NTU3NTE4LDE5LjAwOTQ4NTUgQzQuOTIwNTQ2NTYsMTguOTIxMDYyNiA0Ljk1NTQ2NTU5LDE4Ljg0MTY0MDkgNC45OTAyNzk5MSwxOC43NzEyMjAzIEM1LjAyNTE5ODk0LDE4LjcwMDUzNSA1LjA0MjYzMjI4LDE4LjYyOTg0OTYgNS4wNDI2MzIyOCwxOC41NTk0MjkgTDUuMzA0Mzk0MTEsMTYuMTc2NzI0MSBMNy41NTU1NDU4NiwyNC4wMTMxNjA1IEM3Ljc5OTg3NDM1LDI0LjY0ODUzNDMgOC4yMzYxMjY2MiwyNC45NDg1MzY3IDguODY0MzU1MDIsMjQuOTEzMjczNCBDOS4yMTMzODgyNSwyNC45MTMyNzM0IDkuNTE4NzU5NiwyNC43ODA5MDM5IDkuNzgwNTIxNDMsMjQuNTE2MTY0OCBDMTAuMDQyMjgzMywyNC4yNTE0MjU3IDEwLjE3MzE2NDIsMjMuOTI0MzE0IDEwLjE3MzE2NDIsMjMuNTM2NjMwMSBDMTAuMTczMTY0MiwyMy41MDA3MzE1IDEwLjE2NDQyMTMsMjMuNDU3MjA4NCAxMC4xNDY5ODgsMjMuNDA0MjYwNSBDMTAuMTI5NTU0NywyMy4zNTEzMTI3IDEwLjEyMDgxMTgsMjMuMzA3MDQ4MyAxMC4xMjA4MTE4LDIzLjI3MTc4NTEgTDYuOTc5NjY5ODMsMTIuNDE3NDI4OSBMNy4zNDYxMzY0LDguODY5OTI0ODUgTDguMjM2MTI2NjIsMTAuMjk5NTE2IEM4LjMwNTkxMjMzLDEwLjQwNTQxMTcgOC4zOTMxODM3MiwxMC40OTM2NzU3IDguNDk3ODg4NDUsMTAuNTY0MjU1MSBMMTEuNTg2Njc4MSwxMi4zMTE1MzMyIEMxMS43OTYwODc1LDEyLjM4MjExMjcgMTEuOTM1NzExMywxMi40MTc0Mjg5IDEyLjAwNTQ5NywxMi40MTc0Mjg5IEMxMi4yNDk4MjU1LDEyLjQxNzQyODkgMTIuNDU5MjM1LDEyLjMyMDM3NTUgMTIuNjMzNzI1NCwxMi4xMjYyMTU4IEMxMi44MDgyMTU4LDExLjkzMjAwMzIgMTIuODk1NDg3MiwxMS43MTE0MjI2IDEyLjg5NTQ4NzIsMTEuNDY0MzY4MSBDMTIuODk1NDg3MiwxMS4xNDY2ODEyIDEyLjc1NTg2MzUsMTAuODk5NTczNyAxMi40NzY2NjgzLDEwLjcyMzA5ODYgTDEyLjQ3NjY2ODMsMTAuNzIzMDk4NiBaIiBpZD0iU2hhcGUiPjwvcGF0aD4gICAgICAgICAgICAgICAgICAgIDwvZz4gICAgICAgICAgICAgICAgPC9nPiAgICAgICAgICAgIDwvZz4gICAgICAgIDwvZz4gICAgPC9nPjwvc3ZnPg==" alt="" />
                                <div><?php echo esc_attr($service['extra']['walking_distance']['value']); ?> <?php echo esc_attr(__("min", "parkingpro-booking-widgets")); ?></div>
                            </div>
                            <?php } ?>
                            <div>
                                <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyB3aWR0aD0iMTlweCIgaGVpZ2h0PSIyMXB4IiB2aWV3Qm94PSIwIDAgMTkgMjEiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+ICAgICAgICA8dGl0bGU+Q2FsZW5kYXI8L3RpdGxlPiAgICA8ZGVzYz5DcmVhdGVkIHdpdGggU2tldGNoLjwvZGVzYz4gICAgPGRlZnM+PC9kZWZzPiAgICA8ZyBpZD0iRmluYWwtMS4wIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4gICAgICAgIDxnIGlkPSJMb2NhdGllLS0tRWluZGhvdmVuIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtOTY2LjAwMDAwMCwgLTEyMTMuMDAwMDAwKSI+ICAgICAgICAgICAgPGcgaWQ9InA2MDAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDIwOS4wMDAwMDAsIDExNjguMDAwMDAwKSI+ICAgICAgICAgICAgICAgIDxnIGlkPSJ3YWxrLSstY2FsIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg2NTUuMDAwMDAwLCA0NS4wMDAwMDApIj4gICAgICAgICAgICAgICAgICAgIDxnIGlkPSJDYWxlbmRhciIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTAyLjAwMDAwMCwgMC4wMDAwMDApIj4gICAgICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTcuNDk0ODQ1NiwyLjY3MTAzNTEyIEwxNi4wMzcxMTEzLDIuNjcxMDM1MTIgTDE2LjAzNzExMTMsMS42NjkzNzg2OCBDMTYuMDM3MTExMywxLjIxMDM5MjczIDE1Ljg1ODUwOTksMC44MTczNjc3MzUgMTUuNTAxNjY2LDAuNDkwNDQ5ODc2IEMxNS4xNDQ2MjI2LDAuMTYzNTMyMDE3IDE0LjcxNTgxMTUsMCAxNC4yMTQ1MTQ3LDAgTDEzLjQ4NTc0NzMsMCBDMTIuOTg0NDkwMywwIDEyLjU1NTUxOTcsMC4xNjM1MzIwMTcgMTIuMTk4NjM1OSwwLjQ5MDQ4NjQxOSBDMTEuODQxNjMyNCwwLjgxNzM2NzczNSAxMS42NjMxOTA1LDEuMjEwNDI5MjcgMTEuNjYzMTkwNSwxLjY2OTQxNTIyIEwxMS42NjMxOTA1LDIuNjcxMDcxNjYgTDcuMjg5NzA4NDgsMi42NzEwNzE2NiBMNy4yODk3MDg0OCwxLjY2OTQxNTIyIEM3LjI4OTcwODQ4LDEuMjEwNDI5MjcgNy4xMTExODY4LDAuODE3NDA0Mjc5IDYuNzU0MjYzMTIsMC40OTA0ODY0MTkgQzYuMzk3NDE5MjIsMC4xNjM1MzIwMTcgNS45Njg0MDg2OCwwIDUuNDY3MTUxNzEsMCBMNC43MzgyNjQ2NCwwIEM0LjIzNzEyNzM1LDAgMy44MDgwNzY5MSwwLjE2MzUzMjAxNyAzLjQ1MTE1MzIzLDAuNDkwNDg2NDE5IEMzLjA5NDMwOTMzLDAuODE3MzY3NzM1IDIuOTE1NzQ3NzYsMS4yMTA0MjkyNyAyLjkxNTc0Nzc2LDEuNjY5NDE1MjIgTDIuOTE1NzQ3NzYsMi42NzEwNzE2NiBMMS40NTc5NzM2MSwyLjY3MTA3MTY2IEMxLjA2MzAzMTc5LDIuNjcxMDcxNjYgMC43MjEzODcxNjgsMi44MDMyNDkzOCAwLjQzMjg0MDI3OSwzLjA2NzU2ODI4IEMwLjE0NDI5MzM5MSwzLjMzMTg1MDY0IDAsMy42NDQ5NTUwNyAwLDQuMDA2NjI1NzYgTC02LjE2NTQxMTMyZS0xNiwxOS40NjY0MTY5IEMtNi4xNjU0MTEzMmUtMTYsMTkuODI3ODMxOCAwLjE0NDI5MzM5MSwyMC4xNDA4NjMyIDAuNDMyODQwMjc5LDIwLjQwNTMyODIgQzAuNzIxMzQ3Mjc0LDIwLjY2OTU3NCAxLjA2Mjk5MTksMjAuODAxNzg4MyAxLjQ1Nzk3MzYxLDIwLjgwMTc4ODMgTDE3LjQ5NDU2NjMsMjAuODAxNzg4MyBDMTcuODg5Mzg4NSwyMC44MDE3ODgzIDE4LjIzMTM5MjEsMjAuNjY5NjEwNiAxOC41MTk1OCwyMC40MDUzMjgyIEMxOC44MDgxNjY4LDIwLjE0MDk3MjggMTguOTUyNSwxOS44Mjc3OTUzIDE4Ljk1MjUsMTkuNDY2NDE2OSBMMTguOTUyNSw0LjAwNjU4OTIyIEMxOC45NTI0NjAyLDMuNjQ0ODA4ODkgMTguODA4Mjg2NCwzLjMzMTg1MDY0IDE4LjUxOTg1OTIsMy4wNjc1MzE3NCBDMTguMjMxNjMxNSwyLjgwMzIxMjg0IDE3Ljg4OTY2NzcsMi42NzEwMzUxMiAxNy40OTQ4NDU2LDIuNjcxMDM1MTIgWiBNMTMuMTIxMjgzOCwxLjY2OTUyNDg1IEMxMy4xMjEyODM4LDEuNTcyMDYzNDIgMTMuMTU1MTUzLDEuNDkxOTk2NjkgMTMuMjIzNDEwMiwxLjQyOTU0MzkgQzEzLjI5MTY2NzMsMS4zNjcwMTgwMyAxMy4zNzkwMzMyLDEuMzM1NzAwMjggMTMuNDg1NTA3OSwxLjMzNTcwMDI4IEwxNC4yMTQyNzUzLDEuMzM1NzAwMjggQzE0LjMyMDc4OTksMS4zMzU3MDAyOCAxNC40MDc4NzY2LDEuMzY2OTA4NCAxNC40NzYzNzMxLDEuNDI5NTQzOSBDMTQuNTQ0NjcwMSwxLjQ5MjEwNjMyIDE0LjU3ODc3ODcsMS41NzIxNzMwNSAxNC41Nzg3Nzg3LDEuNjY5NTI0ODUgTDE0LjU3ODc3ODcsNC42NzQyNzQ5MSBDMTQuNTc4Nzc4Nyw0Ljc3MTY5OTggMTQuNTQ0NjcwMSw0Ljg1MTYyMDM2IDE0LjQ3NjM3MzEsNC45MTQyNTU4NiBDMTQuNDA3ODM2Nyw0Ljk3NjcwODY1IDE0LjMyMDc4OTksNS4wMDgwOTk0OSAxNC4yMTQyNzUzLDUuMDA4MDk5NDkgTDEzLjQ4NTUwNzksNS4wMDgwOTk0OSBDMTMuMzc5MDMzMiw1LjAwODA5OTQ5IDEzLjI5MTY2NzMsNC45NzY4NTQ4MiAxMy4yMjM0MTAyLDQuOTE0MjU1ODYgQzEzLjE1NTE1Myw0Ljg1MTU4MzgyIDEzLjEyMTI4MzgsNC43NzE2OTk4IDEzLjEyMTI4MzgsNC42NzQyNzQ5MSBMMTMuMTIxMjgzOCwxLjY2OTUyNDg1IEwxMy4xMjEyODM4LDEuNjY5NTI0ODUgWiBNNC4zNzM4NDEwNSwxLjY2OTUyNDg1IEM0LjM3Mzg0MTA1LDEuNTcyMDYzNDIgNC40MDc5NDk2NiwxLjQ5MTk5NjY5IDQuNDc2Mjg2NTYsMS40Mjk1NDM5IEM0LjU0NDY2MzM2LDEuMzY3MDE4MDMgNC42MzE5MDk2LDEuMzM1NzAwMjggNC43MzgyNjQ2NCwxLjMzNTcwMDI4IEw1LjQ2NzE1MTcxLDEuMzM1NzAwMjggQzUuNTczNTQ2NjQsMS4zMzU3MDAyOCA1LjY2MDk1MjQ1LDEuMzY2OTA4NCA1LjcyOTEyOTc4LDEuNDI5NTQzOSBDNS43OTczODY5LDEuNDkyMTA2MzIgNS44MzE2NTUwOSwxLjU3MjE3MzA1IDUuODMxNjU1MDksMS42Njk1MjQ4NSBMNS44MzE2NTUwOSw0LjY3NDI3NDkxIEM1LjgzMTY1NTA5LDQuNzcxNjk5OCA1Ljc5NzU0NjQ3LDQuODUxNzI5OTkgNS43MjkxMjk3OCw0LjkxNDI1NTg2IEM1LjY2MDc5Mjg4LDQuOTc2NzA4NjUgNS41NzM1NDY2NCw1LjAwODA5OTQ5IDUuNDY3MTUxNzEsNS4wMDgwOTk0OSBMNC43MzgyNjQ2NCw1LjAwODA5OTQ5IEM0LjYzMTkwOTYsNS4wMDgwOTk0OSA0LjU0NDU0MzY4LDQuOTc2ODU0ODIgNC40NzYyODY1Niw0LjkxNDI1NTg2IEM0LjQwODEwOTIzLDQuODUxNTgzODIgNC4zNzM4NDEwNSw0Ljc3MTY5OTggNC4zNzM4NDEwNSw0LjY3NDI3NDkxIEw0LjM3Mzg0MTA1LDEuNjY5NTI0ODUgTDQuMzczODQxMDUsMS42Njk1MjQ4NSBaIE0xNy40OTQ1NjYzLDE5Ljc0ODc2NiBMMS40NTc5NzM2MSwxOS43NDg3NjYgTDEuNDU3OTczNjEsNi42Nzc1MTQ3MSBMMTcuNDk0NTY2Myw2LjY3NzUxNDcxIEwxNy40OTQ1NjYzLDE5Ljc0ODc2NiBMMTcuNDk0NTY2MywxOS43NDg3NjYgWiIgaWQ9IlNoYXBlIiBmaWxsPSIjMzEzMDJFIj48L3BhdGg+ICAgICAgICAgICAgICAgICAgICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZS0yMTkiIGZpbGw9IiMzMTMwMkUiIHg9IjIuMjgiIHk9IjcuODkzMjY3NjUiIHdpZHRoPSIyLjc4NjY2NjY3IiBoZWlnaHQ9IjIuODcwMjc5MTUiPjwvcmVjdD4gICAgICAgICAgICAgICAgICAgICAgICA8cmVjdCBpZD0iUmVjdGFuZ2xlLTIxOS1Db3B5IiBmaWxsPSIjMzEzMDJFIiB4PSI2LjA4IiB5PSI3Ljg5MzI2NzY1IiB3aWR0aD0iMi43ODY2NjY2NyIgaGVpZ2h0PSIyLjg3MDI3OTE1Ij48L3JlY3Q+ICAgICAgICAgICAgICAgICAgICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZS0yMTktQ29weS0yIiBmaWxsPSIjMzEzMDJFIiB4PSI5Ljg4IiB5PSI3Ljg5MzI2NzY1IiB3aWR0aD0iMi43ODY2NjY2NyIgaGVpZ2h0PSIyLjg3MDI3OTE1Ij48L3JlY3Q+ICAgICAgICAgICAgICAgICAgICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZS0yMTktQ29weS0zIiBmaWxsPSIjMzEzMDJFIiB4PSIxMy42OCIgeT0iNy44OTMyNjc2NSIgd2lkdGg9IjIuNzg2NjY2NjciIGhlaWdodD0iMi44NzAyNzkxNSI+PC9yZWN0PiAgICAgICAgICAgICAgICAgICAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGUtMjE5LUNvcHktNyIgZmlsbD0iIzMxMzAyRSIgeD0iMi4yOCIgeT0iMTEuNzIwMzA2NSIgd2lkdGg9IjIuNzg2NjY2NjciIGhlaWdodD0iMi44NzAyNzkxNSI+PC9yZWN0PiAgICAgICAgICAgICAgICAgICAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGUtMjE5LUNvcHktNiIgZmlsbD0iIzMxMzAyRSIgeD0iNi4wOCIgeT0iMTEuNzIwMzA2NSIgd2lkdGg9IjIuNzg2NjY2NjciIGhlaWdodD0iMi44NzAyNzkxNSI+PC9yZWN0PiAgICAgICAgICAgICAgICAgICAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGUtMjE5LUNvcHktNSIgZmlsbD0iIzMxQTZGRiIgeD0iOS44OCIgeT0iMTEuNzIwMzA2NSIgd2lkdGg9IjIuNzg2NjY2NjciIGhlaWdodD0iMi44NzAyNzkxNSI+PC9yZWN0PiAgICAgICAgICAgICAgICAgICAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGUtMjE5LUNvcHktNCIgZmlsbD0iIzMxMzAyRSIgeD0iMTMuNjgiIHk9IjExLjcyMDMwNjUiIHdpZHRoPSIyLjc4NjY2NjY3IiBoZWlnaHQ9IjIuODcwMjc5MTUiPjwvcmVjdD4gICAgICAgICAgICAgICAgICAgICAgICA8cmVjdCBpZD0iUmVjdGFuZ2xlLTIxOS1Db3B5LTExIiBmaWxsPSIjMzEzMDJFIiB4PSIyLjI4IiB5PSIxNS41NDczNDU0IiB3aWR0aD0iMi43ODY2NjY2NyIgaGVpZ2h0PSIyLjg3MDI3OTE1Ij48L3JlY3Q+ICAgICAgICAgICAgICAgICAgICAgICAgPHJlY3QgaWQ9IlJlY3RhbmdsZS0yMTktQ29weS0xMCIgZmlsbD0iIzMxMzAyRSIgeD0iNi4wOCIgeT0iMTUuNTQ3MzQ1NCIgd2lkdGg9IjIuNzg2NjY2NjciIGhlaWdodD0iMi44NzAyNzkxNSI+PC9yZWN0PiAgICAgICAgICAgICAgICAgICAgPC9nPiAgICAgICAgICAgICAgICA8L2c+ICAgICAgICAgICAgPC9nPiAgICAgICAgPC9nPiAgICA8L2c+PC9zdmc+" alt="" />
                                <div><?php echo esc_attr($service_price_data['totalDays']);?> <?php echo esc_attr(__("days", "parkingpro-booking-widgets")); ?></div>
                            </div>
                        </div>
                        <div class="pp__products__block__availability">
                            <?php if( $service_price_data['isUnavailable'] === false ) : ?>
                            <div>
                                <div><?php echo esc_attr(__("Per car", "parkingpro-booking-widgets")); ?></div>
                                <div class="pp__products__block__availability__price"><?php echo $NumberFormatter->formatCurrency(esc_attr($service_price_data['totalWithTax']), 'EUR'); ?></div>
                            </div>
                            <button type="submit" title="<?php echo esc_attr(__("Book now", "parkingpro-booking-widgets")); ?>" class="pp__products__block__availability__button"><?php echo esc_attr(__("Book now", "parkingpro-booking-widgets")); ?></button>
                            <?php endif; ?>
                            <?php if( $service_price_data['isUnavailable'] === true ) : ?>
                            <div>
                                <div class="pp__products__block__availability__price"><?php echo esc_attr(__("Not available", "parkingpro-booking-widgets")); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="pp__products__block__info pp__products__block__info--hidden">
                        <div class="pp__products__block__info__content">
                            <div class="pp__products__block__info__content__title"><?php echo esc_attr($service['extra']['story_title']['value']); ?></div>
                            <p><?php echo esc_attr($service['extra']['story']['value']); ?></p>

                            <a href="#" title="<?php echo esc_attr(__("Close window", "parkingpro-booking-widgets")); ?>" class="pp__products__block__info__button js-product-more-info"><?php echo esc_attr(__("Close window", "parkingpro-booking-widgets")); ?></a>
                        </div>
                        <div class="pp__products__block__info__image">
                            <?php if( isset($service['productImages']) ) { ?>
                                <ul class="lightSlider">
                                    <?php foreach( $service['productImages'] as $image ) : ?>
                                    <li>
                                        <img src="<?php echo esc_attr($image['url']); ?>" alt="<?php echo esc_attr($service['extra']['story_title']['value']); ?>" />
                                        <div><?php echo $image['title']; ?></div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </li>

        <?php endforeach; ?>
    </ul>

    <?php else: ?> 

    <div class="pp__products__query">
        <div><?php echo esc_attr(__("Your options for the period you have selected", "parkingpro-booking-widgets")); ?></div>
        <div class="pp__products__query__box">
            <?php echo esc_attr(__("No services found", "parkingpro-booking-widgets")); ?>
        </div>
    </div>    

    <?php endif; ?>

</div>

<?php
return ob_get_clean();
?>