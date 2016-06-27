<?php
// Headings
$_['heading_title']        	   = 'Settings';
$_['text_openbay']             = 'OpenBay Pro';
$_['text_fba']                 = 'Fulfillment by Amazon';

// Text
$_['text_success']     		   = 'Your settings have been saved';
$_['text_status']         	   = 'Status';
$_['text_account_ok']  		   = 'Connection to Fulfillment by Amazon OK';
$_['text_api_ok']       	   = 'API connection OK';
$_['text_api_status']          = 'API connection';
$_['text_edit']           	   = 'Edit Fulfillment by Amazon settings';
$_['text_standard']            = 'Standard';
$_['text_expedited']           = 'Expedited';
$_['text_priority']            = 'Priority';
$_['text_fillorkill']          = 'Fill or Kill';
$_['text_fillall']             = 'Fill All';
$_['text_fillallavailable']    = 'Fill All Available';
$_['text_prefix_warning']      = 'Do not change this setting after orders have been sent to Amazon, only set this when you first install.';
$_['text_disabled_cancel']     = 'Disabled - do not automatically cancel fulfillments';
$_['text_validate_success']    = 'Your API details are working correctly! You must press save to ensure settings are saved.';
$_['text_register_banner']     = 'Click here if you need to register for an account';

// Entry
$_['entry_api_key']            = 'API key';
$_['entry_account_id']         = 'Account ID';
$_['entry_send_orders']        = 'Send orders automatically';
$_['entry_fulfill_policy']     = 'Fulfillment policy';
$_['entry_shipping_speed']     = 'Default shipping speed';
$_['entry_debug_log']          = 'Enable debug logging';
$_['entry_new_order_status']   = 'New fulfillment trigger';
$_['entry_order_id_prefix']    = 'Order ID Prefix';
$_['entry_only_fill_complete'] = 'All items must be FBA';

// Help
$_['help_api_key']             = 'This is your API key, obtain this from your OpenBay Pro account area';
$_['help_account_id']          = 'This is the account ID that matches the registered Amazon account for OpenBay Pro, obtain this from your OpenBay Pro account area';
$_['help_send_orders']  	   = 'Orders containing matching Fulfillment by Amazon products will be send to Amazon automatically';
$_['help_fulfill_policy']  	   = 'The default fulfillment policy (FillAll - All fulfillable items in the fulfillment order are shipped. The fulfillment order remains in a processing state until all items are either shipped by Amazon or cancelled by the seller. FillAllAvailable - All fulfillable items in the fulfillment order are shipped. All unfulfillable items in the order are cancelled by Amazon.FillOrKill - If an item in a fulfillment order is determined to be unfulfillable before any shipment in the order moves to the Pending status (the process of picking units from inventory has begun), then the entire order is considered unfulfillable. However, if an item in a fulfillment order is determined to be unfulfillable after a shipment in the order moves to the Pending status, Amazon cancels as much of the fulfillment order as possible.)';
$_['help_shipping_speed']  	   = 'This is the default shipping speed category to apply to new orders, different service levels may incurr different costs';
$_['help_debug_log']  		   = 'Debug logs will record information to a log file about actions the module does. This should be left enabled to help find the cause of any problems.';
$_['help_new_order_status']    = 'This is the order status which will trigger the order to be created for fulfillment. Ensure that this is using a status only after you have received payment.';
$_['help_order_id_prefix']     = 'Having an order prefix will help identify orders that have come from your store not from other integrations. This is very helpful when merchants sell on many marketplaces and use FBA';
$_['help_only_fill_complete']  = 'This will only allow orders to be sent for fulfillment if ALL items in the order are matched to a Fulfillment by Amazon item. If any item is not then the whole order will remain unfilled.';

// Error
$_['error_api_connect']        = 'Failed to connect to the API';
$_['error_account_info']       = 'Unable to verify API connection to Fulfillment by Amazon ';
$_['error_api_key']    		   = 'The API key is invalid';
$_['error_api_account_id']     = 'The Account ID is invalid';
$_['error_validation']    	   = 'There was an error validating your details';

// Tabs
$_['tab_api_info']             = 'API details';

// Buttons
$_['button_verify']            = 'Verify details';