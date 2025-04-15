<?php
//Headings
$_['heading_title']                 = 'Amazon Pay and Login with Amazon';

//Text
$_['text_success']                  = 'Amazon Pay and Login with Amazon module has been updated.';
$_['text_ipn_url']					= 'Cron Job\'s URL';
$_['text_ipn_token']				= 'Secret Token';
$_['text_us']					    = 'American English';
$_['text_de']					    = 'German';
$_['text_uk']                       = 'English';
$_['text_fr']                       = 'French';
$_['text_it']                       = 'Italian';
$_['text_es']                       = 'Spanish';
$_['text_us_region']			    = 'United States';
$_['text_eu_region']                = 'Euro region';
$_['text_uk_region']                = 'United Kingdom';
$_['text_live']                     = 'Live';
$_['text_sandbox']                  = 'Sandbox';
$_['text_auth']						= 'Authorization';
$_['text_payment']                  = 'Payment';
$_['text_account']                  = 'Account';
$_['text_guest']					= 'Guest';
$_['text_no_capture']               = '--- No Automatic Capture ---';
$_['text_all_geo_zones']            = 'All Geo Zones';
$_['text_button_settings']          = 'Checkout Button Settings';
$_['text_colour']                   = 'Colour';
$_['text_orange']                   = 'Orange';
$_['text_tan']                      = 'Tan';
$_['text_white']                    = 'White';
$_['text_light']                    = 'Light';
$_['text_dark']                     = 'Dark';
$_['text_size']                     = 'Size';
$_['text_medium']                   = 'Medium';
$_['text_large']                    = 'Large';
$_['text_x_large']                  = 'Extra large';
$_['text_background']               = 'Background';
$_['text_edit']						= 'Edit Amazon Pay and Login with Amazon';
$_['text_amazon_login_pay']         = '<a href="https://pay.amazon.com/help/201828820" target="_blank" title="Sign-up to Amazon Pay"><img src="view/image/payment/amazon_lpa.png" alt="Amazon Pay and Login with Amazon" title="Amazon Pay and Login with Amazon" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_amazon_join']              = '<a href="https://pay.amazon.com/help/201828820" target="_blank" title="Sign-up to Amazon Pay"><u>Sign-up to Amazon Pay</u></a>';
$_['text_payment_info']				= 'Payment information';
$_['text_capture_ok']				= 'Capture was successful';
$_['text_capture_ok_order']			= 'Capture was successful, authorization has been fully captured';
$_['text_refund_ok']				= 'Refund was successfully requested';
$_['text_refund_ok_order']			= 'Refund was successfully requested, amount fully refunded';
$_['text_cancel_ok']				= 'Cancel was successful, order status updated to canceled';
$_['text_capture_status']			= 'Payment captured';
$_['text_cancel_status']			= 'Payment canceled';
$_['text_refund_status']			= 'Payment refunded';
$_['text_order_ref']				= 'Order ref';
$_['text_order_total']				= 'Total authorized';
$_['text_total_captured']			= 'Total captured';
$_['text_transactions']				= 'Transactions';
$_['text_column_authorization_id']	= 'Amazon Authorization ID';
$_['text_column_capture_id']		= 'Amazon Capture ID';
$_['text_column_refund_id']			= 'Amazon Refund ID';
$_['text_column_amount']			= 'Amount';
$_['text_column_type']				= 'Type';
$_['text_column_status']			= 'Status';
$_['text_column_date_added']		= 'Date added';
$_['text_confirm_cancel']			= 'Are you sure you want to cancel the payment?';
$_['text_confirm_capture']			= 'Are you sure you want to capture the payment?';
$_['text_confirm_refund']			= 'Are you sure you want to refund the payment?';
$_['text_minimum_total']            = 'Minimum Order Total';
$_['text_geo_zone']                 = 'Geo Zone';
$_['text_buyer_multi_currency']     = 'Multi-Currency function';
$_['text_status']                   = 'Status';
$_['text_declined_codes']           = 'Test Decline Codes';
$_['text_sort_order']               = 'Sort Order';
$_['text_amazon_invalid']           = 'InvalidPaymentMethod';
$_['text_amazon_rejected']          = 'AmazonRejected';
$_['text_amazon_timeout']           = 'TransactionTimedOut';
$_['text_amazon_no_declined']       = '--- No Automatic Declined Authorization ---';
$_['text_amazon_signup']			= 'Sign-up to Amazon Pay';
$_['text_credentials']				= 'Please paste your keys here (in JSON format)';
$_['text_validate_credentials']		= 'Validate and Use Credentials';
$_['text_extension']                = 'Extensions';
$_['text_info_ssl']                 = '<strong>Important:</strong> SSL (https://) is a requirement and must be enabled on your website for the Amazon Pay and Login with Amazon buttons to work.';
$_['text_info_buyer_multi_currencies'] = 'This extension supports the Multi-Currency functionality. If you would like to use it, please make sure you have enabled at least one of the <a href="https://pay.amazon.co.uk/help/5BDCWHCUC27485L"><b>Amazon Pay supported currencies</b></a> in your on-line store settings <b><a href="index.php?route=localisation/currency&user_token=%s">(%s > %s > %s )</b></a>, and then enable the <b>Multi-Currency function</b>';

// Columns
$_['column_status']                 = 'Status';

//entry
$_['entry_merchant_id']             = 'Merchant ID';
$_['entry_access_key']              = 'Access Key';
$_['entry_access_secret']           = 'Secret Key';
$_['entry_client_id']               = 'Client ID';
$_['entry_client_secret']           = 'Client Secret';
$_['entry_language']			   = 'Default Language';
$_['entry_login_pay_test']          = 'Test mode';
$_['entry_login_pay_mode']          = 'Payment mode';
$_['entry_checkout']				= 'Checkout mode';
$_['entry_payment_region']		   = 'Payment Region';
$_['entry_capture_status']          = 'Status for automatic capture';
$_['entry_pending_status']          = 'Pending Order Status';
$_['entry_capture_oc_status']       = 'Capture Order Status';
$_['entry_ipn_url']					= 'IPN\'s URL';
$_['entry_ipn_token']				= 'Secret Token';
$_['entry_debug']					= 'Debug logging';

// Help
$_['help_pay_mode']					= 'Choose Payment if you would like the payment to get captured automatically, or Authorization to capture it manually.';
$_['help_checkout']					= 'Should payment button also login customer';
$_['help_capture_status']			= 'Choose the order status that will trigger automatic capture of an authorized payment.';
$_['help_capture_oc_status']        = 'Choose the order status that the order will get once it is captured in Amazon Seller Central or from the capture function in OpenCart Admin > %s > %s > %s.';
$_['help_ipn_url']					= 'Set this as you merhcant URL in Amazon Seller Central';
$_['help_ipn_token']				= 'Make this long and hard to guess. The resulting IPN URL must not be longer than 150 characters.';
$_['help_minimum_total']			= 'This must be above zero';
$_['help_debug']					= 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise';
$_['help_declined_codes']			= 'This is for testing purposes only';
$_['help_buyer_multi_currency']     = 'Enable this option if you would like the buyer to shop in any of the Amazon Pay supported currencies available in your on-line store: %s';
$_['help_buyer_multi_currency_no_available_currency']     = 'There are no <a href="https://pay.amazon.co.uk/help/5BDCWHCUC27485L"><b>Amazon Pay supported currencies</b></a> available in your on-line store, please add/enable such currencies in order to use this functionality.';

// Order Info
$_['tab_order_adjustment']          = 'Order Adjustment';

// Errors
$_['error_permission']              = 'You do not have permissions to modify this module!';
$_['error_merchant_id']			    = 'Merchant ID is required';
$_['error_access_key']			    = 'Access Key is required';
$_['error_access_secret']		    = 'Secret Key is required';
$_['error_client_id']			    = 'Client ID is required';
$_['error_client_secret']			= 'Client Key is required';
$_['error_pay_mode']				= 'Payment is only available for US marketplace';
$_['error_minimum_total']			= 'Minimum order total must be above zero';
$_['error_curreny']                 = 'Your shop must have %s currency installed and enabled';
$_['error_upload']                  = 'Upload failed';
$_['error_data_missing'] 			= 'Required data is missing';
$_['error_credentials'] 			= 'Please enter the keys in a valid JSON format';
$_['error_no_supported_currencies'] = 'There are no supported currencies available in your store, please add/enable Buyer Multi-Currency supported currencies in order to use this feature.';

// Buttons
$_['button_capture']				= 'Capture';
$_['button_refund']					= 'Refund';
$_['button_cancel']					= 'Cancel';
