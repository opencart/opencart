<?php
// Heading
$_['heading_title']         = 'Klarna Invoice';

// Text
$_['text_payment']          = 'Payment';
$_['text_success']          = 'Success: You have modified Klarna Payment module!';
$_['text_klarna_invoice']   = '<a onclick="window.open(\'https://merchants.klarna.com/signup?locale=en&partner_id=d5c87110cebc383a826364769047042e777da5e8&utm_campaign=Platform&utm_medium=Partners&utm_source=Opencart\');"><img src="https://cdn.klarna.com/public/images/global/logos/v1/basic/global_basic_logo_std_blue-black.png?width=60&eid=opencart" alt="Klarna Invoice" title="Klarna Invoice" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_live']             = 'Live';
$_['text_beta']             = 'Beta';
$_['text_sweden']           = 'Sweden';
$_['text_norway']           = 'Norway';
$_['text_finland']          = 'Finland';
$_['text_denmark']          = 'Denmark';
$_['text_germany']          = 'Germany';
$_['text_netherlands']      = 'The Netherlands';

// Entry
$_['entry_merchant']        = 'Klarna Merchant ID:<br /><span class="help">(estore id) to use for the Klarna service (provided by Klarna).</span>';
$_['entry_secret']          = 'Klarna Secret:<br /><span class="help">Shared secret to use with the Klarna service (provided by Klarna).</span>';
$_['entry_server']          = 'Server:';
$_['entry_total']           = 'Total:<br /><span class="help">The checkout total the order must reach before this payment method becomes active.</span>';
$_['entry_pending_status']  = 'Pending Status:<br /><span class="help">Set the default order status when an order is processed.</span>';
$_['entry_accepted_status'] = 'Accepted Status:<br /><span class="help">Set the order status the customers order must reach before they are allowed to access their downloadable products and gift vouchers.</span>';
$_['entry_geo_zone']        = 'Geo Zone:';
$_['entry_status']          = 'Status:';
$_['entry_sort_order']      = 'Sort Order:';

// Error
$_['error_permission']      = 'Warning: You do not have permission to modify payment Klarna Part Payment!';
$_['error_retrieve_pclass'] = 'Could not retrieve pClass for %s. Error Code: %s; Error Message: %s';
$_['error_log_clear']       = 'Error: Log file was not cleared. Please check write permissions for the file';
$_['error_http_error']      = 'HTTP Error - Code: %d; Message: %s';
$_['error_update']          = 'There were errors updating the module. Please check the log file.';
?>