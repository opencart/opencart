<?php
// Heading
$_['heading_title']				= 'eWAY Payment';

// Text
$_['text_extension']			= 'Extensions';
$_['text_success']				= 'Success: You have modified your eWAY details!';
$_['text_edit']				   	= 'Edit eWAY';
$_['text_eway']					  = '<a target="_BLANK" href="http://www.eway.com.au/"><img src="view/image/payment/eway.png" alt="eWAY" title="eWAY" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_authorisation']	= 'Authorisation';
$_['text_sale']					  = 'Sale';
$_['text_transparent']		= 'Transparent Redirect (payment form on site)';
$_['text_iframe']				  = 'IFrame (payment form in window)';
$_['text_connect_eway']	  = 'Start accepting credit card payments with eWAY and OpenCart in as little as 5 minutes. Don’t have an eWAY Account? <a target="_blank" href="https://myeway.force.com/success/accelerator-signup?pid=4382&pa=0012000000ivcWf">Click Here</a>';
$_['text_eway_image']	    = '<a target="_BLANK" href="https://myeway.force.com/success/accelerator-signup?pid=4382&pa=0012000000ivcWf"><img src="view/image/payment/eway_connect.png" alt="eWAY" title="eWAY" class="img-fluid" /></a>';

// Entry
$_['entry_paymode']				= 'Payment Mode';
$_['entry_test']				  = 'Test mode';
$_['entry_order_status']	= 'Order status';
$_['entry_order_status_refund'] = 'Refunded order status';
$_['entry_order_status_auth']	  = 'Authorised order status';
$_['entry_order_status_fraud']	= 'Suspected Fraud order status';
$_['entry_status']				= 'Status';
$_['entry_username']			= 'eWAY API Key';
$_['entry_password']			= 'eWAY password';
$_['entry_payment_type']	= 'Payment Type';
$_['entry_geo_zone']			= 'Geo Zone';
$_['entry_sort_order']		= 'Sort order';
$_['entry_transaction_method']	= 'Transaction Method';

// Error
$_['error_permission']		= 'Warning: You do not have permission to modify the eWAY payment module';
$_['error_username']			= 'eWAY API Key is required!';
$_['error_password']			= 'eWAY password is required!';
$_['error_payment_type']	= 'At least one payment type is required!';

// Help hints
$_['help_testmode']				= 'Set to Yes to use the eWAY Sandbox.';
$_['help_username']				= 'Your eWAY API Key from your MYeWAY account.';
$_['help_password']				= 'Your eWAY API Password from your MYeWAY account.';
$_['help_transaction_method']	= 'Authorisation is only available for Australian banks';

// Order page - payment tab
$_['text_payment_info']		     	= 'Payment information';
$_['text_order_total']			    = 'Total authorised';
$_['text_transactions']			    = 'Transactions';
$_['text_column_transactionid'] = 'eWAY Transaction ID';
$_['text_column_amount']		    = 'Amount';
$_['text_column_type']			    = 'Type';
$_['text_column_created']		    = 'Created';
$_['text_total_captured']		    = 'Total captured';
$_['text_capture_status']		    = 'Payment captured';
$_['text_void_status']			    = 'Payment voided';
$_['text_refund_status']		    = 'Payment refunded';
$_['text_total_refunded']		    = 'Total refunded';
$_['text_refund_success']		    = 'Refund succeeded!';
$_['text_capture_success']		  = 'Capture succeeded!';
$_['text_refund_failed']		    = 'Refund failed: ';
$_['text_capture_failed']		    = 'Capture failed: ';
$_['text_unknown_failure']		  = 'Invalid order or amount';

$_['text_confirm_capture']		= 'Are you sure you want to capture the payment?';
$_['text_confirm_release']		= 'Are you sure you want to release the payment?';
$_['text_confirm_refund']		  = 'Are you sure you want to refund the payment?';

$_['text_empty_refund']			= 'Please enter an amount to refund';
$_['text_empty_capture']		= 'Please enter an amount to capture';

$_['btn_refund']				= 'Refund';
$_['btn_capture']				= 'Capture';

// Validation Error codes
$_['text_card_message_V6000']	  = 'Undefined Validation Error';
$_['text_card_message_V6001'] 	= 'Invalid Customer IP';
$_['text_card_message_V6002'] 	= 'Invalid DeviceID';
$_['text_card_message_V6011'] 	= 'Invalid Amount';
$_['text_card_message_V6012'] 	= 'Invalid Invoice Description';
$_['text_card_message_V6013'] 	= 'Invalid Invoice Number';
$_['text_card_message_V6014'] 	= 'Invalid Invoice Reference';
$_['text_card_message_V6015'] 	= 'Invalid Currency Code';
$_['text_card_message_V6016'] 	= 'Payment Required';
$_['text_card_message_V6017'] 	= 'Payment Currency Code Required';
$_['text_card_message_V6018'] 	= 'Unknown Payment Currency Code';
$_['text_card_message_V6021'] 	= 'Cardholder Name Required';
$_['text_card_message_V6022'] 	= 'Card Number Required';
$_['text_card_message_V6023'] 	= 'CVN Required';
$_['text_card_message_V6031'] 	= 'Invalid Card Number';
$_['text_card_message_V6032'] 	= 'Invalid CVN';
$_['text_card_message_V6033'] 	= 'Invalid Expiry Date';
$_['text_card_message_V6034'] 	= 'Invalid Issue Number';
$_['text_card_message_V6035'] 	= 'Invalid Start Date';
$_['text_card_message_V6036'] 	= 'Invalid Month';
$_['text_card_message_V6037'] 	= 'Invalid Year';
$_['text_card_message_V6040'] 	= 'Invalid Token Customer Id';
$_['text_card_message_V6041'] 	= 'Customer Required';
$_['text_card_message_V6042'] 	= 'Customer First Name Required';
$_['text_card_message_V6043'] 	= 'Customer Last Name Required';
$_['text_card_message_V6044'] 	= 'Customer Country Code Required';
$_['text_card_message_V6045'] 	= 'Customer Title Required';
$_['text_card_message_V6046'] 	= 'Token Customer ID Required';
$_['text_card_message_V6047'] 	= 'RedirectURL Required';
$_['text_card_message_V6051'] 	= 'Invalid First Name';
$_['text_card_message_V6052'] 	= 'Invalid Last Name';
$_['text_card_message_V6053'] 	= 'Invalid Country Code';
$_['text_card_message_V6054'] 	= 'Invalid Email';
$_['text_card_message_V6055'] 	= 'Invalid Phone';
$_['text_card_message_V6056'] 	= 'Invalid Mobile';
$_['text_card_message_V6057'] 	= 'Invalid Fax';
$_['text_card_message_V6058'] 	= 'Invalid Title';
$_['text_card_message_V6059'] 	= 'Redirect URL Invalid';
$_['text_card_message_V6060'] 	= 'Redirect URL Invalid';
$_['text_card_message_V6061'] 	= 'Invalid Reference';
$_['text_card_message_V6062'] 	= 'Invalid Company Name';
$_['text_card_message_V6063'] 	= 'Invalid Job Description';
$_['text_card_message_V6064'] 	= 'Invalid Street1';
$_['text_card_message_V6065'] 	= 'Invalid Street2';
$_['text_card_message_V6066'] 	= 'Invalid City';
$_['text_card_message_V6067'] 	= 'Invalid State';
$_['text_card_message_V6068'] 	= 'Invalid Postalcode';
$_['text_card_message_V6069'] 	= 'Invalid Email';
$_['text_card_message_V6070'] 	= 'Invalid Phone';
$_['text_card_message_V6071'] 	= 'Invalid Mobile';
$_['text_card_message_V6072'] 	= 'Invalid Comments';
$_['text_card_message_V6073'] 	= 'Invalid Fax';
$_['text_card_message_V6074'] 	= 'Invalid Url';
$_['text_card_message_V6075'] 	= 'Invalid Shipping Address First Name';
$_['text_card_message_V6076'] 	= 'Invalid Shipping Address Last Name';
$_['text_card_message_V6077'] 	= 'Invalid Shipping Address Street1';
$_['text_card_message_V6078'] 	= 'Invalid Shipping Address Street2';
$_['text_card_message_V6079'] 	= 'Invalid Shipping Address City';
$_['text_card_message_V6080'] 	= 'Invalid Shipping Address State';
$_['text_card_message_V6081'] 	= 'Invalid Shipping Address PostalCode';
$_['text_card_message_V6082'] 	= 'Invalid Shipping Address Email';
$_['text_card_message_V6083'] 	= 'Invalid Shipping Address Phone';
$_['text_card_message_V6084'] 	= 'Invalid Shipping Address Country';
$_['text_card_message_V6091'] 	= 'Unknown Country Code';
$_['text_card_message_V6100'] 	= 'Invalid Card Name';
$_['text_card_message_V6101'] 	= 'Invalid Card Expiry Month';
$_['text_card_message_V6102'] 	= 'Invalid Card Expiry Year';
$_['text_card_message_V6103'] 	= 'Invalid Card Start Month';
$_['text_card_message_V6104'] 	= 'Invalid Card Start Year';
$_['text_card_message_V6105'] 	= 'Invalid Card Issue Number';
$_['text_card_message_V6106'] 	= 'Invalid Card CVN';
$_['text_card_message_V6107'] 	= 'Invalid AccessCode';
$_['text_card_message_V6108'] 	= 'Invalid CustomerHostAddress';
$_['text_card_message_V6109'] 	= 'Invalid UserAgent';
$_['text_card_message_V6110'] 	= 'Invalid Card Number';
$_['text_card_message_V6111'] 	= 'Unauthorised API Access, Account Not PCI Certified';
$_['text_card_message_V6112'] 	= 'Redundant card details other than expiry year and month';
$_['text_card_message_V6113'] 	= 'Invalid transaction for refund';
$_['text_card_message_V6114'] 	= 'Gateway validation error';
$_['text_card_message_V6115'] 	= 'Invalid DirectRefundRequest, Transaction ID';
$_['text_card_message_V6116'] 	= 'Invalid card data on original TransactionID';
$_['text_card_message_V6124'] 	= 'Invalid Line Items. The line items have been provided however the totals do not match the TotalAmount field';
$_['text_card_message_V6125'] 	= 'Selected Payment Type not enabled';
$_['text_card_message_V6126'] 	= 'Invalid encrypted card number, decryption failed';
$_['text_card_message_V6127'] 	= 'Invalid encrypted cvn, decryption failed';
$_['text_card_message_V6128'] 	= 'Invalid Method for Payment Type';
$_['text_card_message_V6129'] 	= 'Transaction has not been authorised for Capture/Cancellation';
$_['text_card_message_V6130'] 	= 'Generic customer information error';
$_['text_card_message_V6131'] 	= 'Generic shipping information error';
$_['text_card_message_V6132'] 	= 'Transaction has already been completed or voided, operation not permitted';
$_['text_card_message_V6133'] 	= 'Checkout not available for Payment Type';
$_['text_card_message_V6134'] 	= 'Invalid Auth Transaction ID for Capture/Void';
$_['text_card_message_V6135'] 	= 'PayPal Error Processing Refund';
$_['text_card_message_V6140'] 	= 'Merchant account is suspended';
$_['text_card_message_V6141'] 	= 'Invalid PayPal account details or API signature';
$_['text_card_message_V6142'] 	= 'Authorise not available for Bank/Branch';
$_['text_card_message_V6150'] 	= 'Invalid Refund Amount';
$_['text_card_message_V6151'] 	= 'Refund amount greater than original transaction';
$_['text_card_message_D4406'] 	= 'Unknown error';
$_['text_card_message_S5010'] 	= 'Unknown error';
