<?php
// Heading
$_['heading_title']					 = 'Google Pay';

// Text
$_['text_google_pay']				 = '<img src="view/image/payment/googlepay.png" height="40" alt="Google Pay" title="Google Pay" style="border: 0px;" />';
$_['text_extension']			 	 = 'Extensions';
$_['text_edit']			 	 		 = 'Edit Google Pay';
$_['text_production']			 	 = 'Production / Live';
$_['text_test']			 			 = 'Test';
$_['text_default']			 		 = 'Default';
$_['text_black']			 		 = 'Black';
$_['text_white']			 		 = 'White';
$_['text_long']			 		 	 = 'Long - "Buy with Google Pay"';
$_['text_short']			 		 = 'Short - "Google Pay"';
$_['text_card_amex']			 	 = 'American Express';
$_['text_card_discover']			 = 'Discover';
$_['text_card_jcb']			 	 	 = 'JCB';
$_['text_card_mastercard']			 = 'Mastercard';
$_['text_card_visa']			 	 = 'Visa';
$_['text_pan_only']			 	 	 = 'Card payments';
$_['text_cryptogram_3ds']			 = 'Android device tokens';
$_['text_braintree']			 	 = 'Braintree';
$_['text_braintree_api_version']	 = 'Braintree API Version';
$_['text_braintree_sdk_version']	 = 'Braintree SDK Version';
$_['text_braintree_merchant_id']	 = 'Braintree Merchant ID';
$_['text_braintree_tokenization_key']= 'Braintree Tokenization Key';
$_['text_firstdata']			 	 = 'First Data';
$_['text_firstdata_merchant_id']	 = 'First Data Merchant ID';
$_['text_globalpayments']			 = 'Global Payments';
$_['text_globalpayments_merchant_id']= 'Global Payments Merchant ID';
$_['text_worldpay']			 		 = 'Worldpay';
$_['text_worldpay_merchant_id']		 = 'Worldpay Merchant ID';
$_['text_not_enabled']		 		 = 'Not enabled';
$_['text_no_gateways_enabled']		 = 'No supported gateways enabled';
$_['text_success']		 			 = 'Google Pay module has been updated';

// Entry
$_['entry_merchant_name']			 = 'Google Merchant Name';
$_['entry_total']					 = 'Total';
$_['entry_sort_order']				 = 'Sort order';
$_['entry_geo_zone']				 = 'Geo zone';
$_['entry_status']					 = 'Status';
$_['entry_debug']					 = 'Debug logging';
$_['entry_environment']				 = 'Environment';
$_['entry_shipping_require_phone']	 = 'Require shipping phone number';
$_['entry_shipping_country_limit'] 	 = 'Restrict shipping countries';
$_['entry_shipping_country_list'] 	 = 'Allowed countries';
$_['entry_billing_require_phone']	 = 'Require billing phone number';
$_['entry_button_color']	 	 	 = 'Button colour';
$_['entry_button_type']	 	 	 	 = 'Button type';
$_['entry_accept_prepay_cards']	 	 = 'Accept pre-pay cards';
$_['entry_auth_methods']	 	 	 = 'Auth methods';
$_['entry_card_networks']	 	 	 = 'Accepted card types';
$_['entry_merchant_gateway']	 	 = 'Merchant Gateway';

// Help
$_['help_total']					 = 'The checkout total the order must reach before this payment method becomes active';
$_['help_debug']					 = 'Enabling debug will write sensitive data to a log file and the browser console. NO NOT enable unless you understand why you need it.';
$_['help_shipping_restriction']		 = 'By default shipping is allowed to all countries, or you can select what countries you allow';
$_['help_merchant_name']		 	 = 'This should be your merchant name, this may be used for customer support, or displayed on transaction notifications. UTF-8 characters only.';
$_['help_auth_methods']		 	 	 = 'Card payments will provide the user with their stored cards in the Google account, Android device tokens may not be supported by all merchant gateways.';

// Tab
$_['tab_general']				 	 = 'General';
$_['tab_advanced']					 = 'Advanced';
$_['tab_button']					 = 'Button';
$_['tab_gateway']					 = 'Gateway';

// Button

// Error
$_['error_environment']				 = 'Invalid environment, must be PRODUCTION or TEST';
$_['error_merchant_name']		 	 = 'Invalid Merchant Name, must be greater than 3 characters and less than 50';
$_['error_status_no_gateway']		 = 'No Merchant Gateway is configured so you cannot set the status to active';
$_['error_auth_method']		 		 = 'No payment Auth method is set, you must choose at least one. Card payments should almost always be chosen';