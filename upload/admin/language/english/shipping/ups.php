<?php
// Heading
$_['heading_title']                = 'UPS';

// Text
$_['text_shipping']                = 'Shipping';
$_['text_success']                 = 'Success: You have modified UPS shipping!';
$_['text_regular_daily_pickup']    = 'Regular Daily Pickup';
$_['text_daily_pickup']            = 'Daily Pickup';
$_['text_customer_counter']        = 'Customer Counter';
$_['text_one_time_pickup']         = 'One Time Pickup';
$_['text_on_call_air_pickup']      = 'On Call Air Pickup';
$_['text_letter_center']           = 'Letter Center';
$_['text_air_service_center']      = 'Air Service Center';
$_['text_suggested_retail_rates']  = 'Suggested Retail Rates (UPS Store)';
$_['text_package']                 = 'Package';
$_['text_ups_letter']              = 'UPS Letter';
$_['text_ups_tube']                = 'UPS Tube';
$_['text_ups_pak']                 = 'UPS Pak';
$_['text_ups_express_box']         = 'UPS Express Box';
$_['text_ups_25kg_box']            = 'UPS 25kg box';
$_['text_ups_10kg_box']            = 'UPS 10kg box';
$_['text_us']                      = 'US Origin';
$_['text_ca']                      = 'Canada Origin';
$_['text_eu']                      = 'European Union Origin';
$_['text_pr']                      = 'Puerto Rico Origin';
$_['text_mx']                      = 'Mexico Origin';
$_['text_other']                   = 'All Other Origins';
$_['text_test']                    = 'Test';
$_['text_production']              = 'Production';
$_['text_residential']             = 'Residential';
$_['text_commercial']              = 'Commercial';
$_['text_next_day_air']            = 'UPS Next Day Air';
$_['text_2nd_day_air']             = 'UPS Second Day Air';
$_['text_ground']                  = 'UPS Ground';
$_['text_3_day_select']            = 'UPS Three-Day Select';
$_['text_next_day_air_saver']      = 'UPS Next Day Air Saver';
$_['text_next_day_air_early_am']   = 'UPS Next Day Air Early A.M.';
$_['text_2nd_day_air_am']          = 'UPS Second Day Air A.M.';
$_['text_saver']                   = 'UPS Saver';
$_['text_worldwide_express']       = 'UPS Worldwide Express';
$_['text_worldwide_expedited']     = 'UPS Worldwide Expedited';
$_['text_standard']                = 'UPS Standard';
$_['text_worldwide_express_plus']  = 'UPS Worldwide Express Plus';
$_['text_express']                 = 'UPS Express';
$_['text_expedited']               = 'UPS Expedited';
$_['text_express_early_am']        = 'UPS Express Early A.M.';
$_['text_express_plus']            = 'UPS Express Plus';
$_['text_today_standard']          = 'UPS Today Standard';
$_['text_today_dedicated_courier'] = 'UPS Today Dedicated Courier';
$_['text_today_intercity']         = 'UPS Today Intercity';
$_['text_today_express']           = 'UPS Today Express';
$_['text_today_express_saver']     = 'UPS Today Express Saver';

// Entry
$_['entry_key']                    = 'Access Key:<span class="help">Enter the XML rates access key assigned to you by UPS.</span>';
$_['entry_username']               = 'Username:<span class="help">Enter your UPS Services account username.</span>';
$_['entry_password']               = 'Password:<span class="help">Enter your UPS Services account password.</span>';
$_['entry_pickup']                 = 'Pickup Method:<span class="help">How do you give packages to UPS (only used when origin is US)?</span>';
$_['entry_packaging']              = 'Packaging Type:<span class="help">What kind of packaging do you use?</span>';
$_['entry_classification']         = 'Customer Classification Code:<span class="help">01 - If you are billing to a UPS account and have a daily UPS pickup, 03 - If you do not have a UPS account or you are billing to a UPS account but do not have a daily pickup, 04 - If you are shipping from a retail outlet (only used when origin is US)</span>';
$_['entry_origin']                 = 'Shipping Origin Code:<span class="help">What origin point should be used (this setting affects only what UPS product names are shown to the user)</span>';
$_['entry_city']                   = 'Origin City:<span class="help">Enter the name of the origin city.</span>';
$_['entry_state']                  = 'Origin State/Province:<span class="help">Enter the two-letter code for your origin state/province.</span>';
$_['entry_country']                = 'Origin Country:<span class="help">Enter the two-letter code for your origin country.</span>';
$_['entry_postcode']               = 'Origin Zip/Postal Code:<span class="help">Enter your origin zip/postalcode.</span>';
$_['entry_test']                   = 'Test Mode:<span class="help">Use this module in Test (YES) or Production mode (NO)?</span>';
$_['entry_quote_type']             = 'Quote Type:<span class="help">Quote for Residential or Commercial Delivery.</span>';
$_['entry_service']                = 'Services:<span class="help">Select the UPS services to be offered.</span>';
$_['entry_insurance']              = 'Enable Insurance:<span class="help">Enables insurance with product total as the value</span>';
$_['entry_display_weight']         = 'Display Delivery Weight:<br /><span class="help">Do you want to display the shipping weight? (e.g. Delivery Weight : 2.7674 Kg\'s)</span>';
$_['entry_weight_class']           = 'Weight Class:<span class="help">Set to kilograms or pounds.</span>';
$_['entry_length_class']           = 'Length Class:<span class="help">Set to centimeters or inches.</span>';
$_['entry_dimension']			   = 'Dimensions (L x W x H):<br /><span class="help">This is assumed to be your average packing box size. Individual item dimensions are not supported at this time so you must enter average dimensions like 5x5x5.</span>';
$_['entry_tax_class']              = 'Tax Class:';
$_['entry_geo_zone']               = 'Geo Zone:';
$_['entry_status']                 = 'Status:';
$_['entry_sort_order']             = 'Sort Order:';
$_['entry_debug']      			   = 'Debug Mode:<br /><span class="help">Saves send/recv data to the system log</span>';

// Error
$_['error_permission']             = 'Warning: You do not have permission to modify UPS (US) shipping!';
$_['error_key']                    = 'Access Key Required!';
$_['error_username']               = 'Username Required!';
$_['error_password']               = 'Password Required!';
$_['error_city']                   = 'Origin City!';
$_['error_state']                  = 'Origin State/Province Required!';
$_['error_country']                = 'Origin Country Required!';
$_['error_dimension']              = 'Average Dimensions Required!';
?>