<?php
// Heading
$_['heading_title']           = 'Mail';

// Text
$_['text_success']            = 'Success: You have modified you mail settings!';

// Entry
$_['entry_account_subject']   = 'Account Subject:';
$_['entry_account_message']   = 'Account Message:';
$_['entry_forgotten_subject'] = 'Forgotten Password Subject:';
$_['entry_forgotten_message'] = 'Forgotten Password Message:';
$_['entry_order_subject']     = 'New Order Subject:';
$_['entry_order_message']     = 'New Order Message:';
$_['entry_update_subject']    = 'Order Update Subject:';
$_['entry_update_message']    = 'Order Update Message:';

// Help
$_['help_account']            = '<b>Replacement Tags:</b><br />{store} - Store Name<br />{firstname} - Customers First Name<br />{login} - Login link';
$_['help_forgotten']          = '<b>Replacement Tags:</b><br />{store} - Store Name<br />{password} - New Password';
$_['help_order']              = '<b>Replacement Tags:</b><br />{store} - Store Name<br />{order_id} - Order ID<br />{date_added} - Date order was added<br />{status} - Order Status<br />{comment} - Order comments<br />{invoice} - Invoice link';
$_['help_update']             = '<b>Replacement Tags:</b><br />{store} - Store Name<br />{order_id} - Order ID<br />{date_added} - Date order was added<br />{status} - Order Status<br />{comment} - Order comments<br />{invoice} - Invoice link';

// Error
$_['error_permission']        = 'Warning: You do not have permission to modify mail settings!';
$_['error_account_subject']   = 'Account subject is required!';
$_['error_account_message']   = 'Account message is required!';
$_['error_forgotten_subject'] = 'Forgotten subject is required!';
$_['error_forgotten_message'] = 'Forgotten message is required!';
$_['error_order_subject']     = 'Order subject is required!';
$_['error_order_message']     = 'Order message is required!';
$_['error_update_subject']    = 'Order update subject is required!';
$_['error_update_message']    = 'Order update message is required!';
?>