<?php
// Heading
$_['heading_title']					 = 'PayPal (Powered by Braintree)';

// Text
$_['text_payment']					 = 'Payment';
$_['text_success']					 = 'Success: You have modified Braintree account details!';
$_['text_success_connect']			 = 'Success: You have connected your PayPal (Powered by Braintree) account!';
$_['text_edit']						 = 'Edit Braintree';
$_['text_production']				 = 'Production';
$_['text_sandbox']					 = 'Sandbox';
$_['text_currency']					 = 'Currency';
$_['text_immediate']				 = 'Immediate';
$_['text_deferred']					 = 'Deferred';
$_['text_hosted']					 = 'Hosted Fields';
$_['text_dropin']					 = 'Drop-in UI';
$_['text_merchant_account_id']		 = 'Merchant Account ID (Leave blank to use default merchant account)';
$_['text_payment_info']				 = 'Payment information';
$_['text_confirm_void']				 = 'Are you sure you want to void this transaction?';
$_['text_avs_response']				 = 'Street Address: %s, Postal Code: %s';
$_['text_confirm_settle']			 = 'Are you sure you want to settle/capture';
$_['text_confirm_refund']			 = 'Are you sure you want to refund';
$_['text_success_action']			 = 'Success';
$_['text_error_settle']				 = 'Error: %s';
$_['text_error_generic']			 = 'Error: There was an error with your request.';
$_['text_na']						 = 'N/A';
$_['text_all']						 = 'All';
$_['text_sale']						 = 'Sale';
$_['text_credit']					 = 'Credit';
$_['text_credit_card']				 = 'Credit Card';
$_['text_paypal']					 = 'PayPal';
$_['text_enable_transactions']		 = 'Please enable Braintree before viewing transactions.';
$_['text_yes']						 = 'Yes';
$_['text_no']						 = 'No';
$_['text_no_refund']				 = 'No refund history';
$_['text_app_connected']		     = 'Module is connected via Braintree auth';
$_['text_braintree']        		 = '<a href="http://go.amazonservices.com/opencart.html" target="_blank" title="Sign-up to Login and Pay with Amazon"><img width="100" src="https://s3-us-west-1.amazonaws.com/bt-partner-assets/paypal-braintree.png" alt="PayPal powered by Braintree" style="border: 1px solid #EEEEEE;"></a>';

// Column
$_['column_void']					 = 'Void';
$_['column_settle']					 = 'Settle';
$_['column_refund']					 = 'Refund';
$_['column_transaction_id']			 = 'Transaction ID';
$_['column_transaction_type']		 = 'Transaction Type';
$_['column_transaction_date']		 = 'Transaction Date';
$_['column_merchant_account']		 = 'Merchant Account';
$_['column_payment_type']			 = 'Payment Type';
$_['column_order_id']				 = 'Order ID';
$_['column_processor_code']			 = 'Processor Authorization Code';
$_['column_cvv_response']			 = 'CVV Response';
$_['column_avs_response']			 = 'AVS Response';
$_['column_3ds_enrolled']			 = '3DS Enrolled';
$_['column_3ds_status']				 = '3DS Status';
$_['column_3ds_shifted']			 = '3DS Liability Shifted';
$_['column_3ds_shift_possible']		 = '3DS Liability Shift Possible';
$_['column_transaction_history']	 = 'Transaction Status History';
$_['column_date']					 = 'Date';
$_['column_refund_history']			 = 'Refund History';
$_['column_action']					 = 'Action';
$_['column_amount']					 = 'Amount';
$_['column_status']					 = 'Status';
$_['column_type']					 = 'Type';
$_['column_customer']				 = 'Customer';
$_['column_order']					 = 'Order';
$_['column_date_added']				 = 'Date Added';

// Entry
$_['entry_merchant_id']				 = 'Merchant ID';
$_['entry_public_key']				 = 'Public Key';
$_['entry_private_key']				 = 'Private Key';
$_['entry_environment']				 = 'Environment';
$_['entry_settlement_type']			 = 'Settlement Type';
$_['entry_integration_type']		 = 'Integration Type';
$_['entry_vault']					 = 'Allow Vaulted Cards?';
$_['entry_debug']					 = 'Debug Logging';
$_['entry_total']					 = 'Total';
$_['entry_geo_zone']				 = 'Geo Zone';
$_['entry_status']					 = 'Status';
$_['entry_sort_order']				 = 'Sort Order';
$_['entry_authorization_expired']	 = 'Authorization Expired';
$_['entry_authorized']				 = 'Authorized';
$_['entry_authorizing']				 = 'Authorizing';
$_['entry_settlement_pending']		 = 'Settlement Pending';
$_['entry_failed']					 = 'Failed';
$_['entry_gateway_rejected']		 = 'Gateway Rejected';
$_['entry_processor_declined']		 = 'Processor Declined';
$_['entry_settled']					 = 'Settled';
$_['entry_settling']				 = 'Settling';
$_['entry_submitted_for_settlement'] = 'Submitted For Settlement';
$_['entry_voided']					 = 'Voided';
$_['entry_3ds_status']				 = 'Enable 3-D Secure';
$_['entry_3ds_full_liability_shift'] = 'Accept only transactions with full liability shift?';
$_['entry_transaction_id']			 = 'Transaction ID';
$_['entry_transaction_type']		 = 'Transaction Type';
$_['entry_date_from']				 = 'Date From';
$_['entry_date_to']					 = 'Date To';
$_['entry_payment_type']			 = 'Payment Type';
$_['entry_card_type']				 = 'Card Type';
$_['entry_amount_from']				 = 'Amount From';
$_['entry_amount_to']				 = 'Amount To';
$_['entry_transaction_status']		 = 'Transaction Status';
$_['entry_merchant_account_id']		 = 'Merchant Account ID';
$_['entry_connection']		 		 = 'API connection status';

// Help
$_['help_settlement_type']			 = 'Immediate will Submit For Settlement straight away. Deferred will set the transaction to Authorized and the merchant must Submit For Settlement manually in the OpenCart order details.';
$_['help_vault']					 = 'Allows customers to securely save cards to the website. (Hosted Fields with Credit/Debit Cards only)';
$_['help_debug']					 = 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise.';
$_['help_total']					 = 'The checkout total the order must reach before this payment method becomes active.';

// Button
$_['button_void']					 = 'Void';
$_['button_settle']					 = 'Settle';
$_['button_refund']					 = 'Refund';
$_['button_filter']					 = 'Filter';

// Error
$_['error_permission']				 = 'Warning: You do not have permission to modify payment Braintree!';
$_['error_php_version']				 = 'Minimum version of PHP 5.4.0 is required!';
$_['error_merchant_id']				 = 'Merchant ID Required!';
$_['error_public_key']				 = 'Public Key Required!';
$_['error_private_key']				 = 'Private Key Required!';
$_['error_connection']				 = 'There was a problem establishing a connection to the Braintree API. Please check your Merchant ID, Public Key, Private Key and Environment settings.';
$_['error_account']					 = 'Please enter a valid Merchant Account ID as specified in your Braintree Account';
$_['error_warning']					 = 'Warning: Please check the form carefully for errors!';

// Tab
$_['tab_setting']					 = 'Settings';
$_['tab_currency']					 = 'Currencies';
$_['tab_order_status']				 = 'Order Statuses (New orders)';
$_['tab_3ds']						 = '3-D Secure';
$_['tab_transaction']				 = 'Transactions';