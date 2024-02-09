<?php
// Heading
$_['heading_title']		 				= 'Opayo';

// Text
$_['text_opayo']	  					= '<img src="view/image/payment/opayo.png" alt="Opayo" title="Opayo" />';
$_['text_extensions']     				= 'Extensions';
$_['text_edit']          				= 'Edit Opayo';
$_['text_tab_general']				 	= 'General';
$_['text_tab_cron']						= 'Cron';
$_['text_test']				  			= 'Test';
$_['text_live']				  			= 'Live';
$_['text_defered']			  			= 'Defered';
$_['text_authenticate']		  			= 'Authenticate';
$_['text_payment']		  				= 'Payment';
$_['text_payment_info']		  			= 'Payment information';
$_['text_release_status']	  			= 'Payment released';
$_['text_void_status']		 			= 'Payment voided';
$_['text_rebate_status']	  			= 'Payment rebated';
$_['text_order_ref']		  			= 'Order ref';
$_['text_order_total']		  			= 'Total authorised';
$_['text_total_released']	  			= 'Total released';
$_['text_transactions']		  			= 'Transactions';
$_['text_column_amount']	  			= 'Amount';
$_['text_column_type']		  			= 'Type';
$_['text_column_date_added']  			= 'Created';
$_['text_confirm_void']		  			= 'Are you sure you want to void the payment?';
$_['text_confirm_release']	  			= 'Are you sure you want to release the payment?';
$_['text_confirm_rebate']	  			= 'Are you sure you want to rebate the payment?';

// Entry
$_['entry_vendor']			  			= 'Vendor';
$_['entry_environment']			  		= 'Environment';
$_['entry_transaction_method']		  	= 'Transaction Method';
$_['entry_total']             			= 'Total';
$_['entry_order_status']	  			= 'Order Status';
$_['entry_geo_zone']		  			= 'Geo Zone';
$_['entry_status']			  			= 'Status';
$_['entry_sort_order']		  			= 'Sort Order';
$_['entry_debug']			  			= 'Debug logging';
$_['entry_card_save']			  		= 'Store Cards';
$_['entry_cron_token']	  				= 'Secret Token';
$_['entry_cron_url']	  				= 'URL';
$_['entry_cron_last_run']	 			= 'Last run time:';

// Help
$_['help_total']			  			= 'The checkout total the order must reach before this payment method becomes active.';
$_['help_debug']			  			= 'Enabling debug will write sensitive data to a log file. You should always disable unless instructed otherwise.';
$_['help_transaction_method']  			= 'Transaction method MUST be set to Payment to allow subscription payments.';
$_['help_card_save']			  		= 'In order for buyer can save card details for subsequent payments, MID TOKEN must be subscribed. You will need to contact Opayo customer support to discuss enabling the token system for your account.';
$_['help_cron_token']	  				= 'Make this long and hard to guess.';
$_['help_cron_url']		  				= 'Set a cron to call this URL.';

// Button
$_['button_release']		  			= 'Release';
$_['button_rebate']			  			= 'Rebate / refund';
$_['button_void']			  			= 'Void';
$_['button_enable_recurring']			= 'Enable Recurring';
$_['button_disable_recurring']			= 'Disable Recurring';

// Success
$_['success_save']		 				= 'Success: You have modified Opayo!';
$_['success_release_ok']		  		= 'Success: Release was successful!';
$_['success_release_ok_order']	  		= 'Success: Release was successful, order status updated to success - settled!';
$_['success_rebate_ok']		  			= 'Success: Rebate was successful!';
$_['success_rebate_ok_order']	  		= 'Success: Rebate was successful, order status updated to rebated!';
$_['success_void_ok']			  		= 'Success: Void was successful, order status updated to voided!';
$_['success_enable_recurring']			= 'Success: Recurring payment was enabled!';
$_['success_disable_recurring']			= 'Success: Recurring payment was disabled!';

// Error
$_['error_warning']          			= 'Warning: Please check the form carefully for errors!';
$_['error_permission']		  			= 'Warning: You do not have permission to modify payment Opayo!';
$_['error_vendor']			  			= 'Vendor ID Required!';
