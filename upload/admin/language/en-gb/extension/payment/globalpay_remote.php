<?php
// Heading
$_['heading_title']					 = 'Global Payments API';

// Text
$_['text_extension']				 = 'Extensions';
$_['text_success']					 = 'Success: You have modified Global Payments API account details!';
$_['text_edit']                      = 'Edit Global Payments Remote';
$_['text_live']						 = 'Live';
$_['text_demo']						 = 'Demo';
$_['text_card_type']				 = 'Card type';
$_['text_enabled']					 = 'Enabled';
$_['text_use_default']				 = 'Use default';
$_['text_merchant_id']				 = 'Merchant ID';
$_['text_subaccount']				 = 'Sub Account';
$_['text_secret']					 = 'Shared secret';
$_['text_card_visa']				 = 'Visa';
$_['text_card_master']				 = 'Mastercard';
$_['text_card_amex']				 = 'American Express';
$_['text_card_switch']				 = 'Switch/Maestro';
$_['text_card_laser']				 = 'Laser';
$_['text_card_diners']				 = 'Diners';
$_['text_capture_ok']				 = 'Capture was successful';
$_['text_capture_ok_order']			 = 'Capture was successful, order status updated to success - settled';
$_['text_rebate_ok']				 = 'Rebate was successful';
$_['text_rebate_ok_order']			 = 'Rebate was successful, order status updated to rebated';
$_['text_void_ok']					 = 'Void was successful, order status updated to voided';
$_['text_settle_auto']				 = 'Auto';
$_['text_settle_delayed']			 = 'Delayed';
$_['text_settle_multi']				 = 'Multi';
$_['text_ip_message']				 = 'You must supply your server IP address to your Global Payments account manager before going live';
$_['text_payment_info']				 = 'Payment information';
$_['text_capture_status']			 = 'Payment captured';
$_['text_void_status']				 = 'Payment voided';
$_['text_rebate_status']			 = 'Payment rebated';
$_['text_order_ref']				 = 'Order ref';
$_['text_order_total']				 = 'Total authorised';
$_['text_total_captured']			 = 'Total captured';
$_['text_transactions']				 = 'Transactions';
$_['text_confirm_void']				 = 'Are you sure you want to void the payment?';
$_['text_confirm_capture']			 = 'Are you sure you want to capture the payment?';
$_['text_confirm_rebate']			 = 'Are you sure you want to rebate the payment?';
$_['text_hash_nomatch']			 	 = 'WARNING! The security check against the hash values did not match, verify the payment manually';
$_['text_globalpay_remote']			 = '<a target="_blank" href="https://www.globalpaymentsinc.com/en-ie/accept-payments/ecommerce/partners/opencart"><img src="view/image/payment/globalpay.png" alt="Global Payments" title="Global Payments" style="border: 0px solid #EEEEEE;" /></a>';
$_['text_googlepay_notice']			 = 'Google Pay is not enabled on your account by default, you need to contact Global Payments to activate the feature on your account (applies to Live and Demo accounts)';

// Column
$_['text_column_amount']			 = 'Amount';
$_['text_column_type']				 = 'Type';
$_['text_column_date_added']		 = 'Created';

// Entry
$_['entry_merchant_id']				 = 'Merchant ID';
$_['entry_secret']					 = 'Shared secret';
$_['entry_rebate_password']			 = 'Rebate password';
$_['entry_total']					 = 'Total';
$_['entry_sort_order']				 = 'Sort order';
$_['entry_geo_zone']				 = 'Geo zone';
$_['entry_status']					 = 'Status';
$_['entry_debug']					 = 'Debug logging';
$_['entry_auto_settle']				 = 'Settlement type';
$_['entry_tss_check']				 = 'TSS checks';
$_['entry_card_data_status']		 = 'Card info logging';
$_['entry_3d']						 = 'Enable 3D secure';
$_['entry_liability_shift']			 = 'Accept non-liability shifting scenarios';
$_['entry_status_success_settled']	 = 'Success - settled';
$_['entry_status_success_unsettled'] = 'Success - not settled';
$_['entry_status_decline']			 = 'Decline';
$_['entry_status_decline_pending']	 = 'Decline - offline auth';
$_['entry_status_decline_stolen']	 = 'Decline - lost or stolen card';
$_['entry_status_decline_bank']		 = 'Decline - bank error';
$_['entry_status_void']				 = 'Voided';
$_['entry_status_rebate']			 = 'Rebated';
$_['entry_test']					 = 'Test (Sandbox) Mode';
$_['entry_google_pay_status']		 = 'Display Google Pay option';
$_['entry_live_demo']		 		 = 'Live / Demo';

// Help
$_['help_total']					 = 'The checkout total the order must reach before this payment method becomes active';
$_['help_card_select']				 = 'Ask the user to choose their card type before they are redirected';
$_['help_notification']				 = 'You need to supply this URL to Global Payments to get payment notifications';
$_['help_debug']					 = 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise.';
$_['help_liability']				 = 'Accepting liability means you will still accept payments when a user fails 3D secure.';
$_['help_card_data_status']			 = 'Logs last 4 cards digits, expire, name, type and issuing bank information';

// Tab
$_['tab_api']					     = 'API Details';
$_['tab_account']				     = 'Accounts';
$_['tab_order_status']				 = 'Order Status';
$_['tab_payment']					 = 'Payment Settings';
$_['tab_google_pay']				 = 'Google Pay';

// Button
$_['button_capture']				 = 'Capture';
$_['button_rebate']					 = 'Rebate / refund';
$_['button_void']					 = 'Void';

// Error
$_['error_merchant_id']				 = 'Merchant ID is required';
$_['error_secret']					 = 'Shared secret is required';
$_['error_google_pay_install']		 = 'The Google Pay extension is not installed, please install first from the <a href="%s" target="_blank">payment extensions page</a>';
$_['error_google_pay_status']		 = 'The <a href="%s" target="_blank">Google Pay extension</a> is installed but not enabled, please update the module status to active ';
$_['error_google_pay_environment']	 = 'The <a href="%s" target="_blank">Google Pay extension</a> is in test/demo mode, if you are testing then check both extensions are set to the same';