<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

//Headings
$_['heading_title']                 = 'Login and Pay with Amazon';

//Text
$_['text_success']                  = 'Login and Pay with Amazon module has been updated';
$_['text_ipn_url']					= 'Cron Job\'s URL';
$_['text_ipn_token']				= 'Secret Token';
$_['text_us']						= 'US';
$_['text_germany']                  = 'Germany';
$_['text_uk']                       = 'United Kingdom';
$_['text_live']                     = 'Live';
$_['text_sandbox']                  = 'Sandbox';
$_['text_auth']						= 'Authorization';
$_['text_payment']                  = 'Платеж';
$_['text_no_capture']               = '--- No Automatic Capture ---';
$_['text_all_geo_zones']            = 'All Geo Zones';
$_['text_button_settings']          = 'Checkout Button Settings';
$_['text_colour']                   = 'Colour';
$_['text_orange']                   = 'Orange';
$_['text_tan']                      = 'Tan';
$_['text_white']                    = 'White';
$_['text_light']                    = 'Light';
$_['text_dark']                     = 'Dark';
$_['text_size']                     = 'Размер';
$_['text_medium']                   = 'Medium';
$_['text_large']                    = 'Large';
$_['text_x_large']                  = 'Extra large';
$_['text_background']               = 'Background';
$_['text_edit']						= 'Редактирование';
$_['text_amazon_login_pay']         = '<a href="http://go.amazonservices.com/opencart.html" target="_blank" title="Sign-up to Login and Pay with Amazon"><img src="view/image/payment/amazon.png" alt="Login and Pay with Amazon" title="Login and Pay with Amazon" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_amazon_join']              = '<a href="http://go.amazonservices.com/opencart.html" target="_blank" title="Sign-up to Login and Pay with Amazon"><u>Sign-up to Login and Pay with Amazon</u></a>';
$_['entry_login_pay_test']          = 'Test mode';
$_['entry_login_pay_mode']          = 'Payment mode';
$_['text_payment_info']				= 'Платёжные реквизиты';
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
$_['text_transactions']				= 'Операции';
$_['text_column_authorization_id']	= 'Amazon Authorization ID';
$_['text_column_capture_id']		= 'Amazon Capture ID';
$_['text_column_refund_id']			= 'Amazon Refund ID';
$_['text_column_amount']			= 'Итого';
$_['text_column_type']				= 'Тип';
$_['text_column_status']			= 'Статус';
$_['text_column_date_added']		= 'Дата добавления';
$_['text_confirm_cancel']			= 'Are you sure you want to cancel the payment?';
$_['text_confirm_capture']			= 'Are you sure you want to capture the payment?';
$_['text_confirm_refund']			= 'Are you sure you want to refund the payment?';
$_['text_minimum_total']            = 'Minimum Order Total';
$_['text_geo_zone']                 = 'Географическая зона';
$_['text_status']                   = 'Статус';
$_['text_declined_codes']           = 'Test Decline Codes';
$_['text_sort_order']               = 'Порядок сортировки';
$_['text_amazon_invalid']           = 'InvalidPaymentMethod';
$_['text_amazon_rejected']          = 'AmazonRejected';
$_['text_amazon_timeout']           = 'TransactionTimedOut';
$_['text_amazon_no_declined']       = '--- No Automatic Declined Authorization ---';

// Columns
$_['column_status']                 = 'Статус';

//entry
$_['entry_merchant_id']             = 'Merchant ID';
$_['entry_access_key']              = 'Access Key';
$_['entry_access_secret']           = 'Secret Key';
$_['entry_client_id']               = 'Client ID';
$_['entry_client_secret']           = 'Client Secret';
$_['entry_marketplace']             = 'Marketplace';
$_['entry_capture_status']          = 'Automatic capture status';
$_['entry_pending_status']          = 'Pending Order Status';
$_['entry_ipn_url']					= 'IPN\'s URL';
$_['entry_ipn_token']				= 'Secret Token';
$_['entry_debug']					= 'Debug logging';


// Help
$_['help_pay_mode']					= 'Payment is only available for US marketplace';
$_['help_capture_status']			= 'Choose order staus that will trigger automatic capture of an authorized payment';
$_['help_ipn_url']					= 'Set this as you merhcant URL in Amazon Seller Central';
$_['help_ipn_token']				= 'Make this long and hard to guess';
$_['help_debug']					= 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise';
$_['help_declined_codes']			= 'This is for testing purposes only';

// Order Info
$_['tab_order_adjustment']          = 'Order Adjustment';

// Errors
$_['error_permission']              = 'У вас недостаточно прав для внесения изменений!';
$_['error_merchant_id']			    = 'Merchant ID is required';
$_['error_access_key']			    = 'Access Key is required';
$_['error_access_secret']		    = 'Secret Key is required';
$_['error_client_id']			    = 'Client ID is required';
$_['error_client_secret']			= 'Client Key is required';
$_['error_pay_mode']				= 'Payment is only available for US marketplace';
$_['error_curreny']                 = 'Your shop must have %s currency installed and enabled';
$_['error_upload']                  = 'Upload failed';
$_['error_data_missing'] 			= 'Required data is missing';

// Buttons
$_['button_capture']				= 'Capture';
$_['button_refund']					= 'Refund';
$_['button_cancel']					= 'Отмена';
