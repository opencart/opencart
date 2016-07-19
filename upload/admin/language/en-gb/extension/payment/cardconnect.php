<?php
// Heading
$_['heading_title']                 = 'CardConnect';

// Tab
$_['tab_settings']                  = 'Settings';
$_['tab_order_status']              = 'Order Status';

// Text
$_['text_extension']                = 'Extensions';
$_['text_success']                  = 'Success: You have modified CardConnect payment module!';
$_['text_edit']                     = 'Edit CardConnect';
$_['text_cardconnect']              = '<a href="http://www.cardconnect.com" target="_blank"><img src="view/image/payment/cardconnect.png" alt="CardConnect" title="CardConnect"></a>';
$_['text_payment']                  = 'Payment';
$_['text_authorize']                = 'Authorize';
$_['text_live']                     = 'Live';
$_['text_test']                     = 'Test';
$_['text_no_cron_time']             = 'The cron has not yet been executed';
$_['text_payment_info']             = 'Payment Information';
$_['text_payment_method']           = 'Payment Method';
$_['text_card']                     = 'Card';
$_['text_echeck']                   = 'eCheck';
$_['text_reference']                = 'Reference';
$_['text_update']                   = 'Update';
$_['text_order_total']              = 'Order Total';
$_['text_total_captured']           = 'Total Captured';
$_['text_capture_payment']          = 'Capture Payment';
$_['text_refund_payment']           = 'Refund Payment';
$_['text_void']                     = 'Void';
$_['text_transactions']             = 'Transactions';
$_['text_column_type']              = 'Type';
$_['text_column_reference']         = 'Reference';
$_['text_column_amount']            = 'Amount';
$_['text_column_status']            = 'Status';
$_['text_column_date_modified']     = 'Date Modified';
$_['text_column_date_added']        = 'Date Added';
$_['text_column_update']            = 'Update';
$_['text_column_void']              = 'Void';
$_['text_confirm_capture']          = 'Are you sure you want to capture the payment?';
$_['text_confirm_refund']           = 'Are you sure you want to refund the payment?';
$_['text_inquire_success']          = 'Inquire was successful';
$_['text_capture_success']          = 'Capture request was successful';
$_['text_refund_success']           = 'Refund request was successful';
$_['text_void_success']             = 'Void request was successful';

// Entry
$_['entry_merchant_id']             = 'Merchant ID';
$_['entry_api_username']            = 'API Username';
$_['entry_api_password']            = 'API Password';
$_['entry_token']                   = 'Secret Token';
$_['entry_transaction']             = 'Transaction';
$_['entry_environment']             = 'Environment';
$_['entry_site']                    = 'Site';
$_['entry_store_cards']             = 'Store Cards';
$_['entry_echeck']                  = 'eCheck';
$_['entry_total']                   = 'Total';
$_['entry_geo_zone']                = 'Geo Zone';
$_['entry_status']                  = 'Status';
$_['entry_logging']                 = 'Debug Logging';
$_['entry_sort_order']              = 'Sort Order';
$_['entry_cron_url']                = 'Cron Job URL';
$_['entry_cron_time']               = 'Cron Job Last Run';
$_['entry_order_status_pending']    = 'Pending';
$_['entry_order_status_processing'] = 'Processing';

// Help
$_['help_merchant_id']              = 'Your personal CardConnect account merchant ID.';
$_['help_api_username']             = 'Your username to access the CardConnect API.';
$_['help_api_password']             = 'Your password to access the CardConnect API.';
$_['help_token']                    = 'Enter a random token for security or use the one generated.';
$_['help_transaction']              = 'Choose \'Payment\' to capture the payment immediately or \'Authorize\' to have to approve it.';
$_['help_site']                     = 'This determines the first part of the API URL. Only change if instructed.';
$_['help_store_cards']              = 'Whether you want to store cards using tokenization.';
$_['help_echeck']                   = 'Whether you want to offer the ability to pay using an eCheck.';
$_['help_total']                    = 'The checkout total the order must reach before this payment method becomes active. Must be a value with no currency sign.';
$_['help_logging']                  = 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise.';
$_['help_cron_url']                 = 'Set a cron job to call this URL so that the orders are auto-updated. It is designed to be ran a few hours after the last capture of a business day.';
$_['help_cron_time']                = 'This is the last time that the cron job URL was executed.';
$_['help_order_status_pending']     = 'The order status when the order has to be authorized by the merchant.';
$_['help_order_status_processing']  = 'The order status when the order is automatically captured.';

// Button
$_['button_inquire_all']            = 'Inquire All';
$_['button_capture']                = 'Capture';
$_['button_refund']                 = 'Refund';
$_['button_void_all']               = 'Void All';
$_['button_inquire']                = 'Inquire';
$_['button_void']                   = 'Void';

// Error
$_['error_permission']              = 'Warning: You do not have permission to modify payment CardConnect!';
$_['error_merchant_id']             = 'Merchant ID Required!';
$_['error_api_username']            = 'API Username Required!';
$_['error_api_password']            = 'API Password Required!';
$_['error_token']                   = 'Secret Token Required!';
$_['error_site']                    = 'Site Required!';
$_['error_amount_zero']             = 'Amount must be higher than zero!';
$_['error_no_order']                = 'No matching order info!';
$_['error_invalid_response']        = 'Invalid response received!';
$_['error_data_missing']            = 'Missing data!';
$_['error_not_enabled']             = 'Module not enabled!';