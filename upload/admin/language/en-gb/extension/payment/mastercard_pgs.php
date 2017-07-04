<?php

// Heading
$_['heading_title']										= 'Mastercard Payment Gateway Services';
$_['heading_title_transaction']							= 'View Transaction #%s';

// Regex
// This regex must preserve the match groups. It must match the key 'text_callback' in catalog/language/<language>/extension/payment/mastercard_pgs.php. It is used in the event extension/payment/mastercard_pgs/order_history_link
$_['regex_order_history_link']							= '~(^Mastercard Payment Gateway Services.*?)(Transaction #)([0-9a-zA-Z]+)(.*)~';

// Help
$_['help_total']										= 'The checkout total the order must reach before this payment method becomes active.';
$_['help_tokenize']										= 'Enabling this will store a token for your customers&apos; credit card details after they successfully complete their payment. This will allow them to complete their next purchases without getting redirected to the Mastercard Hosted Checkout page.<br /><br />NOTE: Tokens will be saved only for registered customers.';
$_['help_display_name']									= 'This text is the name of the payment method your customers will see during checkout. Default: Mastercard Payment Gateway Services';
$_['help_checkout']										= 'This is the type of transactions which will be done when your customers checkout with an already saved credit card token.';
$_['help_merchant']										= 'If you want to enable test mode, prepend <strong>TEST</strong> in front of the merchant name. Example: <strong>TESTMERCHANTID</strong>';
$_['help_google_analytics_property_id']					= 'The property ID for your shop site provided by Google Analytics in the form UA-XXXXX-Y. Provide this ID if you want to track interactions with the checkout using Google Analytics. See www.google.com/analytics.';
$_['help_notification_secret']							= 'Get your notification secret from <strong>Mastercard Merchant Administration &gt; Admin &gt; Webhook Notifications</strong>.<br /><br />IMPORTANT: Make sure that Webhook Notifications are <strong>Enabled</strong>.<br /><br />IMPORTANT: Make sure to select <strong>JSON/REST</strong> as the Web Services API Format.<br /><br />NOTE: Notification URL can be left empty.';
$_['help_integration_password']							= 'Get your integration password from <strong>Mastercard Merchant Administration &gt; Admin &gt; Integration Settings</strong>.';
$_['help_debug_log']									= 'Use this only for debugging purposes. Enabling this will log the following to your OpenCart error log: notification data, REST API requests, REST API responses';
$_['help_approved_authorization_order_status']          = 'Transaction of type AUTHORIZATION or AUTHORIZATION_UPDATE is approved.';
$_['help_approved_capture_order_status']                = 'Transaction of type CAPTURE is approved.';
$_['help_approved_payment_order_status']                = 'Transaction of type PAYMENT is approved.';
$_['help_approved_refund_order_status']                 = 'Transaction of type REFUND or REFUND_REQUEST is approved.';
$_['help_approved_void_order_status']                   = 'Transaction of type VOID_AUTHORIZATION, VOID_CAPTURE, VOID_PAYMENT or VOID_REFUND is approved.';
$_['help_approved_verification_order_status'] 			= 'Transaction of type VERIFICATION is approved.';
$_['help_unspecified_failure_order_status'] 			= 'Transaction could not be processed.';
$_['help_declined_order_status'] 						= 'Transaction declined by issuer.';
$_['help_timed_out_order_status'] 						= 'Response timed out.';
$_['help_expired_card_order_status'] 					= 'Transaction declined due to expired card.';
$_['help_insufficient_funds_order_status'] 				= 'Transaction declined due to insufficient funds.';
$_['help_acquirer_system_error_order_status'] 			= 'Acquirer system error occurred processing the transaction.';
$_['help_system_error_order_status'] 					= 'Internal system error occurred processing the transaction.';
$_['help_not_supported_order_status'] 					= 'Transaction type not supported.';
$_['help_declined_do_not_contact_order_status'] 		= 'Transaction declined - do not contact issuer.';
$_['help_aborted_order_status'] 						= 'Transaction aborted by payer.';
$_['help_blocked_order_status'] 						= 'Transaction blocked due to Risk or 3D Secure blocking rules.';
$_['help_cancelled_order_status'] 						= 'Transaction cancelled by payer.';
$_['help_deferred_transaction_received_order_status'] 	= 'Deferred transaction received and awaiting processing.';
$_['help_referred_order_status'] 						= 'Transaction declined - refer to issuer.';
$_['help_authentication_failed_order_status'] 			= '3D Secure authentication failed.';
$_['help_invalid_csc_order_status'] 					= 'Invalid card security code.';
$_['help_lock_failure_order_status'] 					= 'Order locked - another transaction is in progress for this order.';
$_['help_submitted_order_status'] 						= 'Transaction submitted - response has not yet been received.';
$_['help_not_enrolled_3d_secure_order_status'] 			= 'Card holder is not enrolled in 3D Secure.';
$_['help_pending_order_status'] 						= 'Transaction is pending.';
$_['help_exceeded_retry_limit_order_status'] 			= 'Transaction retry limit exceeded.';
$_['help_duplicate_batch_order_status'] 				= 'Transaction declined due to duplicate batch.';
$_['help_declined_avs_order_status'] 					= 'Transaction declined due to address verification.';
$_['help_declined_csc_order_status'] 					= 'Transaction declined due to card security code.';
$_['help_declined_avs_csc_order_status'] 				= 'Transaction declined due to address verification and card security code.';
$_['help_declined_payment_plan_order_status']			= 'Transaction declined due to payment plan.';
$_['help_approved_pending_settlement_order_status'] 	= 'Transaction Approved - pending batch settlement.';
$_['help_partially_approved_order_status'] 				= 'The transaction was approved for a lesser amount than requested.';
$_['help_unknown_order_status'] 						= 'Response unknown.';
$_['help_risk_rejected_order_status']                   = 'Transaction risk is high and order is automatically rejected.';
$_['help_risk_review_pending_order_status']             = 'Transaction risk review is pending.';
$_['help_risk_review_rejected_order_status']            = 'The order has been cancelled as a result of a transaction risk review, and a reversal transaction was attempted.';
$_['help_gateway'] 										= 'Select the payment gateway of your Merchant account. You also have the option to specify your own custom gateway.';

// Tab
$_['tab_setting'] 										= 'Settings';
$_['tab_transaction'] 									= 'Transactions';

// Text
$_['text_edit']											= 'Edit Mastercard Payment Gateway Services';
$_['text_extension']									= 'Extensions';
$_['text_mastercard_pgs'] 								= '<a target="_BLANK" href="http://www.mastercard.com/index.html"><img src="view/image/payment/mastercard_pgs.png" alt="Mastercard Payment Gateway Services" title="Mastercard Payment Gateway Services" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_success']										= 'Success: You have modified Mastercard Payment Gateway Services payment module!';
$_['text_gateway_na']									= 'NA - North America / South America';
$_['text_gateway_eu']									= 'EU - Europe / UK / MEA';
$_['text_gateway_ap']									= 'AP - Asia / Pacific';
$_['text_gateway_other']								= 'Other...';
$_['text_lightbox']										= 'Lightbox';
$_['text_hosted_payment_page']							= 'Hosted Payment Page';
$_['text_pay']											= 'Pay';
$_['text_authorize']									= 'Authorize';
$_['text_notification_ssl']								= 'Careful: This payment extension will not work on stores without SSL.';
$_['text_success_events']								= 'Events have been successfully hooked!';
$_['text_loading']										= 'Loading data... Please wait...';
$_['text_loading_short']								= 'Please wait...';
$_['text_view']											= 'View';
$_['text_void']											= 'Void Transaction';
$_['text_refund']										= 'Refund Transaction';
$_['text_capture']										= 'Capture Transaction';
$_['text_confirm_void']									= 'You are about to void the following amount: {AMOUNT}. Proceed?';
$_['text_confirm_refund']								= 'You are about to refund the following amount: {AMOUNT}. Proceed?';
$_['text_confirm_capture']								= 'You are about to capture the following amount: {AMOUNT}. Proceed?';
$_['text_success_void']									= 'Transaction successfully voided! Refresh the transaction list in several seconds to see it.';
$_['text_success_refund']								= 'Transaction successfully refunded! Refresh the transaction list in several seconds to see it.';
$_['text_success_capture']								= 'Transaction successfully captured! Refresh the transaction list in several seconds to see it.';
$_['text_refresh']										= 'Refresh transaction list';
$_['text_general_settings']								= 'General Settings';
$_['text_transaction_statuses']                         = 'Transaction Statuses';
$_['text_copy_clipboard']								= 'Copy to Clipboard';
$_['text_show_hide']									= 'Show/Hide';
$_['text_log_api_intro']                                = '[Mastercard Payment Gateway Services - REST API %s]: ';
$_['text_no_transactions']                              = 'No transactions have been logged yet.';

// Entry
$_['entry_total']										= 'Total';
$_['entry_tokenize']									= 'Tokenize Payment Details';
$_['entry_display_name']								= 'Display Name';
$_['entry_checkout']									= 'Tokenized Checkout Type';
$_['entry_geo_zone']									= 'Geo Zone';
$_['entry_status']										= 'Status';
$_['entry_sort_order']									= 'Sort Order';
$_['entry_gateway']										= 'Payment Gateway';
$_['entry_gateway_other']								= 'some-alternative-gateway.com';
$_['entry_onclick']										= 'Checkout Interaction';
$_['entry_google_analytics_property_id']				= 'Google Analytics Property ID';
$_['entry_merchant']									= 'Merchant ID';
$_['entry_notification_secret']							= 'Webhook Notification Secret';
$_['entry_integration_password']						= 'Integration Password';
$_['entry_debug_log']									= 'Debug Logging';
$_['entry_transaction_id']								= 'Transaction ID';
$_['entry_order_id']									= 'Order ID';
$_['entry_partner_solution_id']							= 'Partner Solution ID';
$_['entry_result']										= 'Transaction Result';
$_['entry_type']										= 'Transaction Type';
$_['entry_currency']									= 'Currency';
$_['entry_amount']										= 'Amount';
$_['entry_risk_code']									= 'Risk Status';
$_['entry_risk_score']									= 'Risk Score';
$_['entry_api_version']									= 'API Version';
$_['entry_browser']										= 'Customer User Agent';
$_['entry_ip']											= 'Customer IP';
$_['entry_date_created']								= 'Date Created';
$_['entry_approved_authorization_order_status']         = 'Approved: Authorization';
$_['entry_approved_capture_order_status']               = 'Approved: Capture';
$_['entry_approved_payment_order_status']               = 'Approved: Payment';
$_['entry_approved_refund_order_status']                = 'Approved: Refund';
$_['entry_approved_void_order_status']                  = 'Approved: Void';
$_['entry_approved_verification_order_status'] 			= 'Approved: Verification';
$_['entry_unspecified_failure_order_status'] 			= 'Unspecified Failure';
$_['entry_declined_order_status'] 						= 'Declined';
$_['entry_timed_out_order_status'] 						= 'Timed Out';
$_['entry_expired_card_order_status'] 					= 'Expired Card';
$_['entry_insufficient_funds_order_status'] 			= 'Insufficient Funds';
$_['entry_acquirer_system_error_order_status'] 			= 'Acquirer System Error';
$_['entry_system_error_order_status'] 					= 'System Error';
$_['entry_not_supported_order_status'] 					= 'Not Supported';
$_['entry_declined_do_not_contact_order_status'] 		= 'Declined Do Not Contact';
$_['entry_aborted_order_status'] 						= 'Aborted';
$_['entry_blocked_order_status'] 						= 'Blocked';
$_['entry_cancelled_order_status'] 						= 'Cancelled';
$_['entry_deferred_transaction_received_order_status'] 	= 'Deferred Transaction Received';
$_['entry_referred_order_status'] 						= 'Referred';
$_['entry_authentication_failed_order_status'] 			= 'Authentication Failed';
$_['entry_invalid_csc_order_status'] 					= 'Invalid CSC';
$_['entry_lock_failure_order_status'] 					= 'Lock Failure';
$_['entry_submitted_order_status'] 						= 'Submitted';
$_['entry_not_enrolled_3d_secure_order_status'] 		= 'Not Enrolled 3D Secure';
$_['entry_pending_order_status'] 						= 'Pending';
$_['entry_exceeded_retry_limit_order_status'] 			= 'Exceeded Retry Limit';
$_['entry_duplicate_batch_order_status'] 				= 'Duplicate Batch';
$_['entry_declined_avs_order_status'] 					= 'Declined AVS';
$_['entry_declined_csc_order_status'] 					= 'Declined CSC';
$_['entry_declined_avs_csc_order_status'] 				= 'Declined AVS/CSC';
$_['entry_declined_payment_plan_order_status'] 			= 'Declined Payment Plan';
$_['entry_approved_pending_settlement_order_status'] 	= 'Approved Pending Settlement';
$_['entry_partially_approved_order_status'] 			= 'Partially Approved';
$_['entry_unknown_order_status'] 						= 'Unknown';
$_['entry_risk_rejected_order_status']                  = 'Risk Assessment: Rejected';
$_['entry_risk_review_pending_order_status']            = 'Risk Review: Pending';
$_['entry_risk_review_rejected_order_status']           = 'Risk Review: Rejected';

// Error
$_['error_permission'] 									= 'Warning: You do not have permission to modify payment Mastercard Payment Gateway Services!';
$_['error_merchant']									= 'You must specify a Merchant ID between 1 and 16 characters.';
$_['error_notification_secret']							= 'You must specify your notification secret, which is 32 characters long.';
$_['error_integration_password']						= 'You must specify your integration password, which is 32 characters long.';
$_['error_not_authorization']							= 'Invalid transaction type. Expecting: AUTHORIZATION, AUTHORIZATION_UPDATE.';
$_['error_not_capture']									= 'Invalid transaction type. Expecting: CAPTURE, PAYMENT.';
$_['error_invalid_amount']								= 'Invalid amount. Please insert a valid numeric value.';
$_['error_api']											= 'An API error occurred: %s';
$_['error_api_transaction']								= 'Could not create new transaction.';
$_['error_unknown']										= 'An unexpected error has occurred. Please report this to the store owners.';
$_['error_warning']										= 'Warning: Please check the form carefully for errors!';
$_['error_gateway_other']								= 'Please specify an alternative gateway!';

// Column
$_['column_transaction_id'] 							= 'Transaction ID';
$_['column_merchant'] 									= 'Merchant ID';
$_['column_order_id'] 									= 'Order ID';
$_['column_status'] 									= 'Status';
$_['column_type'] 										= 'Type';
$_['column_amount'] 									= 'Amount';
$_['column_risk'] 										= 'Risk';
$_['column_ip'] 										= 'IP';
$_['column_date_created'] 								= 'Date Created';
$_['column_action'] 									= 'Action';

// Button
$_['button_void']										= 'Void';
$_['button_refund']										= 'Refund';
$_['button_capture']									= 'Capture';
$_['button_copy_clipboard']								= 'Copy to Clipboard';
$_['button_rehook_events']								= 'Re-Hook Events';