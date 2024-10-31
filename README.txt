=== ParkingPro Booking Widgets ===
Contributors: parkingpro
Donate link: https://www.parkingpro.nl
Tags: parkingpro, parking, booking, widget
Requires at least: 4.9
Tested up to: 6.3.2
Requires PHP: 5.6
Stable tag: 1.2.46
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

ParkingPro Booking Widgets plugin: add widgets to your website.

== Description ==

ParkingPro Booking Widgets plugin: add widgets to your website.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `parkingpro-booking-widgets.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add widgets and shortcodes in your templates

== Frequently Asked Questions ==

= What is the shortcode for the widget? =

Using the [pp_booking_widget] shortcode, you can add the ParkingPro Booking Widget to any post or page. All plugin settings are customizable by adding parameters to the shortcode. Example: `[pp_booking_widget form_title="Example Parking" size="small" display_services="hide"]`

= What parameters are available for the [pp_booking_widget] shortcode? =

* myparkingpro_url          (example: https://demo.myparking.pro)
* display_services          (example: display, hide)
* filter_services           (example: GUID,GUID,GUID)
* force_single_service      (example: true)
* airport                   (example: GUID)
* booking_page              (example: https://www.example.com/book-now)
* products_page             (example: https://www.example.com/products)
* thankyou_page             (example: https://www.example.com/thank-you)
* widget_destination        (example: booking, product_selection)
* iframe_destination        (example: iframe, thankyou_page)
* size                      (example: large, medium, small, row)
* display_airport_dropdown  (example: display, hide)
* display_car_dropdown      (example: display, hide)
* remember_widget_fields    (example: true)

* form_title                (example: Book your valet service)
* section_dates_title       (example: Parking and return)
* section_services_title    (example: Our services)
* label_parkingdate         (example: Parking date and time)
* label_returndate          (example: Return date and time)
* section_airport_title     (example: Airports)
* section_car_title         (example: Cars)

* colors_header_background        (example: #000000)
* colors_header_text              (example: #000000)
* colors_border                   (example: #000000)
* colors_section_text             (example: #000000)
* colors_label_text               (example: #000000)
* colors_button_background        (example: #000000)
* colors_button_text              (example: #000000)
* colors_button_hoveR_background  (example: #000000)
* colors_button_hoveR_text        (example: #000000)
* button_text                     (example: Book now)
* hide_times                      (example: yes)

= How to use the MyParkingPro iFrame =
Use the shortcode `[pp_booking_iframe]` in the booking page to include the MyParkingPro iFrame. The form fields from the widget (dates, times and location) will be prefilled in the MyParkingPro iFrame, so the user don't need to enter the same information twice. 
This is only possible by using the shortcode. If you include the MyParkingPro iFrame with HTML (like `<iframe src="https://demo.myparking.pro"></iframe>`), this won't work.
By adding the `hideheader="yes"` parameter, you can hide the header in the MyParkingPro iFrame. Using the `culture="nl-NL"` parameter, you can force the language context in the MyParkingPro iFrame.

`[pp_booking_iframe hideheader="yes" hidetitle="yes" culture="nl-NL"]`

= What parameters are available for the [pp_booking_iframe] shortcode? =

* hideheader                (example: yes)
* hidetitle                 (example: yes)
* culture                   (example: nl-NL, de-DE, en-GB)
* height                    (example: 2200)
* width                     (example: 100%)
* showlocations             (example: GUID,GUID,GUID)
* header_element_selector   (example: #header)

= What is the shortcode for the product selection table? =

Using the [pp_product_selection] shortcode, you can add the ParkingPro product selection table to any post or page. Use parameters `extra.hide_more_info`,
`extra.more_info_as_link` and `extra.more_info_link` in ParkingPro to change the behaviour of the More info button for each location.

= How to add an account registration iFrame? =

Using the [pp_account_registration_iframe] shortcode, you can add the ParkingPro account registration iFrame to any post or page. This shortcode supports the following parameters:

* hideheader                (example: yes)
* hidetitle                 (example: yes)
* culture                   (example: nl-NL, de-DE, en-GB)
* height                    (example: 1500)
* width                     (example: 100%)

`[pp_account_registration_iframe hideheader="yes" hidetitle="yes" culture="nl-NL"]`

= How to add an account login iFrame? =

Using the [pp_account_login_iframe] shortcode, you can add the ParkingPro account login iFrame to any post or page. This shortcode supports the following parameters:

* hideheader                (example: yes)
* hidetitle                 (example: yes)
* culture                   (example: nl-NL, de-DE, en-GB)
* height                    (example: 1500)
* width                     (example: 100%)

`[pp_account_login_iframe hideheader="yes" hidetitle="yes" culture="nl-NL"]`

= How to add a parking rates iFrame? =

Using the [pp_parking_rates_iframe] shortcode, you can add the ParkingPro parking rates iFrame to any post or page. This shortcode supports the following parameters:

* hideheader                (example: yes)
* hidetitle                 (example: yes)
* culture                   (example: nl-NL, de-DE, en-GB)
* maxnumberofdays           (example: 30)
* height                    (example: 1500)
* width                     (example: 100%)

`[pp_parking_rates_iframe hideheader="yes" hidetitle="yes" culture="nl-NL" maxnumberofdays="30"]`

= How to add a custom Thank You page? =

Using the [pp_thank_you] shortcode, you can add the Thank You content to any post or page.

`[pp_thank_you]`

== Screenshots ==

1. The plugin has it's own settings page to control all options for the Widgets, shortcodes and iFrame embed.
2. A new Widget type named 'ParkingPro Booking Widget' is available to add to your Widget Areas.
3. It's also possible to add the ParkingPro Booking Widget to pages and posts via a shortcode.
4. Use the custom iFrame shortcode to add the MyParkingPro iFrame to a page on your site.

== Changelog ==

= 1.2.46 (25-09-2024) =
* Footer default background fix
* Improved responsiveness for size=large

= 1.2.45 (26-04-2024) =
* Auto scroll to correct spot in reservation iFrame
* Added showlocations and includelocations parameters to rates shortcode
* Added includelocations parameter to reservation shortcode
* Added query_string parameter to all shortcodes

= 1.2.44 (27-11-2023) =
* Added Thank You URL as returnUrl parameter to booking iFrame URL

= 1.2.43 (23-10-2023) =
* Added setting for button hover background and text colors
* Fix for setting the size value to 'row' via shortcode parameter

= 1.2.42 (29-09-2023) =
* Layout improvements

= 1.2.41 (22-09-2023) =
* Remove trailing slash from MyParkingPro URL
* Fix PHP warnings on empty Thank You page

= 1.2.40 (15-09-2023) =
* Fixed fetching prices when no times are provide
* UI fixes

= 1.2.39 (08-08-2023) =
* Fixed location images to prevent widget height changing while filtering
* Fixed month limit when setting/removing dates

= 1.2.38 (03-08-2023) =
* Only use/populate (filtered) visible locations from the widget in the reservation from

= 1.2.37 (13-07-2023) =
* Added support for mppdata parameters
* Improvements to Google tracking scripts
* Support for multiple ParkingPro instances
* Filter services after selecting an airport

= 1.2.36 (29-06-2023) =
* Updated Google tracking scripts

= 1.2.35 (25-02-2023) =
* Translated time input placeholders and added missing translations

= 1.2.34 (28-02-2023) =
* Added setting and shortcode parameters to hide time inputs in widget

= 1.2.33 (25-09-2022) =
* Added support for multiple header element selectors
* Added optgroup element in airport dropdown
* Updated translations
* Fix for loading the iFrame twice

= 1.2.32 (22-06-2022) =
* Fix for populating the selected location services

= 1.2.31 (20-06-2022) =
* Fix for maxnumberofdays parameter in the [pp_parking_rates_iframe] shortcode

= 1.2.30 (21-04-2022) =
* Added timezone support for date formatting on the Thank You page

= 1.2.29 (20-04-2022) =
* Added option to add Google Analytics cookies to the booking iFrame

= 1.2.28 (29-03-2022) =
* Added partnerId parameter to the pp_booking_iframe shortcode

= 1.2.26 (24-03-2022) =
* Added jQuery to the Header Element Selector to set header element

= 1.2.25 (15-03-2022) =
* Added option Header Element Selector to set header element and correct scrolling behaviour in the booking iFrame

= 1.2.24 (01-03-2022) =
* Improvements related to affiliate tracking

= 1.2.22 (30-11-2021) =
* Added support for dynamic iFrame heights

= 1.2.21 (03-11-2021) =
* Added support for affiliate tracking

= 1.2.20 (25-10-2021) =
* Dynamically update all iFrame heights to prevent scrolling inside the iFrame

= 1.2.19 (19-10-2021) =
* Added locale support on Thank You page for currencies and dates
* Dynamically update reservation iFrame height to prevent scrolling inside the iFrame

= 1.2.18 (27-09-2021) =
* Changed string for airport dropdown and updated language files

= 1.2.17 (13-09-2021) =
* Added support for airport filtering in booking iFrame
* Removed default fallback times in the booking iFrame

= 1.2.16 (28-07-2020) =
* Open calendar by default on today
* If no time data is provided by the user, use 12:00 by default in the booking iFrame

= 1.2.15 (28-07-2020) =
* Included arrival and return times in all price calculations
* Load backend config and use for timepickers (minArrivalDepartureTime and maxArrivalDepartureTime)
* Added support for extra backend config (defaultArrivalTime, defaultDepartureTime, minArrivalDate)

= 1.2.14 (16-01-2020) =
* Added support for showlocations parameter in [pp_booking_iframe] shortcode

= 1.2.13 (08-01-2020) =
* Added tags to services on the product selection page

= 1.2.12 (08-01-2020) =
* All iFrame shortcodes now supports custom height and width attributes
* Added tag filters and sorting to product selection page

= 1.2.11 (21-11-2019) =
* Fix for metadata fields

= 1.2.10 (19-11-2019) =
* Added support for external 'More info' links in Product Selection 
* Updated translations

= 1.2.9 (18-09-2019) =
* Added support for custom Thank You page
* Removed all h1, h2, h3 and h3 tags to prevent styling issues
* Fixed date formatting bug
* Updated README and translations

= 1.2.8 (27-08-2019) =
* Added support to use the widget on the product selection page
* Fixed errors with using stored dates in the past

= 1.2.7 (23-08-2019) =
* Fixed styling and responsive issues
* Fixed PHP warning for disabled car dropdown

= 1.2.6 (12-07-2019) =
* Added setting to remember widget data entered by the user
* Added German translations
* Updated README with new parameters

= 1.2.5 (11-07-2019) =
* Added the number of cars to the booking iFrame
* Added referer URL to the booking iFrame
* Added iFrame shortcode for account registration
* Added iFrame shortcode for account login
* Added iFrame shortcode for parking rates
* Updated README with new parameters

= 1.2.4 (08-07-2019) =
* Added WPML language for the iFrame culture
* Added setting to hide services in the iFrame
* Updated translations
* Updated README with new parameters

= 1.2.3 (08-07-2019) =
* Added spinner animation to all submit buttons
* Added Google Analytics integration to measure events in MyParkingPro iFrame
* Added transfer the visible (by airport filtered) services to the MyParkingPro iFrame
* Fixed price display when no pricelists are set in ParkingPro
* Fixed PHP warning and missing parameters
* Fixed alignments of date fields in the widget
* Changed form ID to a class
* Updated ormatting of dates to dd-mm-yyyy
* Updated README with new parameters


= 1.2.2 (01-07-2019) =
* Fixed escaping in titles
* Fixed responsive behaviour of the row lay-out
* Fixed PHP warnings for missing parameters
* Added missing translation

= 1.2.1 (18-06-2019) =
* Added shortcode to display a product selection table
* Added support for airport selection
* Added support for number of cars selection

= 1.1.0 (25-04-2019) =
* Sanitize and escape all input and output data
* Added error message to tell the user when no services are available
* Fixed API call error in admin page when MyParkingPro URL is malformed

= 1.0.0 (23-04-2019) =
* Initial version of the plugin

== Upgrade Notice ==

= 1.0.0 =
First version of the plugin
