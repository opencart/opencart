<?php
// Heading
$_['heading_title']			= 'Cardinity';

// Text
$_['text_editing_shop']     = 'Select the shop you want to assign the API keys to';
$_['text_extension']		= 'Extensions';
$_['text_success']			= 'Success: You have modified Cardinity payment module!';
$_['text_edit']             = 'Edit Cardinity';
$_['text_cardinity']		= '<a href="http://cardinity.com/?crdp=opencart" target="_blank"><img src="view/image/payment/cardinity.png" alt="Cardinity" title="Cardinity" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_production']		= 'Production';
$_['text_sandbox']			= 'Sandbox';
$_['text_payment_info']		= 'Refund information';
$_['text_no_refund']		= 'No refund history';
$_['text_confirm_refund']	= 'Are you sure you want to refund';
$_['text_na']				= 'N/A';
$_['text_success_action']	= 'Success';
$_['text_error_generic']	= 'Error: There was an error with your request. Please check the logs.';

// Column
$_['column_refund']			= 'Refund';
$_['column_date']			= 'Date';
$_['column_refund_history'] = 'Refund History';
$_['column_action']			= 'Action';
$_['column_status']			= 'Status';
$_['column_amount']			= 'Amount';
$_['column_description']	= 'Description';

// Entry
$_['entry_editing_shop']	= 'Shop';
$_['entry_total']			= 'Total';
$_['entry_order_status']	= 'Order Status';
$_['entry_geo_zone']		= 'Geo Zone';
$_['entry_status']			= 'Status';
$_['entry_sort_order']		= 'Sort Order';
$_['entry_key']				= 'Consumer Key';
$_['entry_secret']			= 'Consumer Secret';
$_['entry_debug']			= 'Debug';
$_['project_key']			= 'Project ID';
$_['project_secret']		= 'Project Secret';
$_['entry_external']		= 'External checkout';

$_['entry_log']		        = 'Transaction Log';


// Help
$_['help_debug']			= 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise.';
$_['help_total']			= 'The checkout total the order must reach before this payment method becomes active.';
$_['help_external']			= 'Enabling external checkout will perform the payment in our secured hosted server, instead of internally.';


$_['help_consumer_cred']	= 'Consumer Key and Consumer Secret can be found in your cardinity dashboard > Integration > API settings. Only required if using internal method.';
$_['help_project_cred']		= 'Project ID and Project Secret can be found in your cardinity dashboard > Integration > API settings. Only required if using external hosted method.';




// Button
$_['button_refund']			= 'Refund';

// Error
$_['error_key']				= 'Key Required!';
$_['error_secret']			= 'Secret Required!';

$_['error_project_key']		= 'Project Key Required!';
$_['error_project_secret']	= 'Project Secret Required!';

$_['error_composer']		= 'Unable to load Cardinity SDK. Please download a compiled vendor folder or run composer.';
$_['error_php_version']		= 'Minimum version of PHP 5.4.0 is required!';
$_['error_permission']		= 'Warning: You do not have permission to modify payment Cardinity!';
$_['error_connection']		= 'There was a problem establishing a connection to the Cardinity API. Please check your Key and Secret settings.';
$_['error_warning']			= 'Warning: Please check the form carefully for errors!';

$_['refund_approved']	= 'Refund Approved';
$_['refund_declined']	= 'Refund Declined';
$_['refund_processing']	= 'Refund Processing, check back later';