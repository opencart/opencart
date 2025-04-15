<?php
// Heading
$_['heading_title']					 = 'First Data EMEA Web Service API';

// Text
$_['text_firstdata_remote']			 = '<img src="view/image/payment/firstdata.png" alt="First Data" title="First Data" style="border: 1px solid #EEEEEE;" />';
$_['text_extension']					 = 'Extensions';
$_['text_success']					 = 'Success: You have modified First Data account details!';
$_['text_edit']                      = 'Edit First Data EMEA Web Service API';
$_['text_card_type']				 = 'Card type';
$_['text_enabled']					 = 'Enabled';
$_['text_merchant_id']				 = 'Store ID';
$_['text_subaccount']				 = 'Subaccount';
$_['text_user_id']					 = 'User ID';
$_['text_capture_ok']				 = 'Capture was successful';
$_['text_capture_ok_order']			 = 'Capture was successful, order status updated to success - settled';
$_['text_refund_ok']				 = 'Refund was successful';
$_['text_refund_ok_order']			 = 'Refund was successful, order status updated to refunded';
$_['text_void_ok']					 = 'Void was successful, order status updated to voided';
$_['text_settle_auto']				 = 'Sale';
$_['text_settle_delayed']			 = 'Pre auth';
$_['text_mastercard']				 = 'Mastercard';
$_['text_visa']						 = 'Visa';
$_['text_diners']					 = 'Diners';
$_['text_amex']						 = 'American Express';
$_['text_maestro']					 = 'Maestro';
$_['text_payment_info']				 = 'Payment information';
$_['text_capture_status']			 = 'Payment captured';
$_['text_void_status']				 = 'Payment voided';
$_['text_refund_status']			 = 'Payment refunded';
$_['text_order_ref']				 = 'Order ref';
$_['text_order_total']				 = 'Total authorised';
$_['text_total_captured']			 = 'Total captured';
$_['text_transactions']				 = 'Transactions';
$_['text_column_amount']			 = 'Amount';
$_['text_column_type']				 = 'Type';
$_['text_column_date_added']		 = 'Created';
$_['text_confirm_void']				 = 'Are you sure you want to void the payment?';
$_['text_confirm_capture']			 = 'Are you sure you want to capture the payment?';
$_['text_confirm_refund']			 = 'Are you sure you want to refund the payment?';
$_['text_void']                      = 'Void';
$_['text_payment']                   = 'Payment';
$_['text_refund']                    = "Refund";

// Entry
$_['entry_certificate_path']		 = 'Certificate path';
$_['entry_certificate_key_path']	 = 'Private key path';
$_['entry_certificate_key_pw']		 = 'Private key password';
$_['entry_certificate_ca_path']		 = 'CA path';
$_['entry_merchant_id']				 = 'Store ID';
$_['entry_user_id']					 = 'User ID';
$_['entry_password']				 = 'Password';
$_['entry_total']					 = 'Total';
$_['entry_sort_order']				 = 'Sort order';
$_['entry_geo_zone']				 = 'Geo zone';
$_['entry_status']					 = 'Status';
$_['entry_debug']					 = 'Debug logging';
$_['entry_auto_settle']				 = 'Settlement type';
$_['entry_status_success_settled']	 = 'Success - settled';
$_['entry_status_success_unsettled'] = 'Success - not settled';
$_['entry_status_decline']			 = 'Decline';
$_['entry_status_void']				 = 'Voided';
$_['entry_status_refund']			 = 'Refunded';
$_['entry_enable_card_store']		 = 'Enable card storage tokens';
$_['entry_cards_accepted']			 = 'Card types accepted';

// Help
$_['help_total']					 = 'The checkout total the order must reach before this payment method becomes active';
$_['help_certificate']				 = 'Certificates and private keys should be stored outside of your public web folders';
$_['help_card_select']				 = 'Ask the user to choose thier card type before they are redirected';
$_['help_notification']				 = 'You need to supply this URL to First Data to get payment notifications';
$_['help_debug']					 = 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise .';
$_['help_settle']					 = 'If you use pre-auth you must complete a post-auth action within 3-5 days otherwise your transaction will be dropped';

// Tab
$_['tab_account']					 = 'API info';
$_['tab_order_status']				 = 'Order status';
$_['tab_payment']					 = 'Payment settings';

// Button
$_['button_capture']				 = 'Capture';
$_['button_refund']					 = 'Refund';
$_['button_void']					 = 'Void';

// Error
$_['error_merchant_id']				 = 'Store ID is required';
$_['error_user_id']					 = 'User ID is required';
$_['error_password']				 = 'Password is required';
$_['error_certificate']				 = 'Certificate path is required';
$_['error_key']						 = 'Certificate key is required';
$_['error_key_pw']					 = 'Certificate key password is required';
$_['error_ca']						 = 'Certificate Authority (CA) is required';