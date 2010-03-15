<?php
// Heading
$_['heading_title']           = 'Settings';

// Text
$_['text_success']            = 'Success: You have successfully saved your settings!';
$_['text_mail']               = 'Mail';
$_['text_smtp']               = 'SMTP';

// Entry
$_['entry_store']             = 'Deafult Store:<br /><span class="help">Select a default store that will be used when a unregistered domain is pointed to your hosting.</span>';
$_['entry_owner']             = 'Store Owner:';
$_['entry_address']           = 'Address:';
$_['entry_email']             = 'E-Mail:';
$_['entry_telephone']         = 'Telephone:';
$_['entry_fax']               = 'Fax:';
$_['entry_template']          = 'Template:';
$_['entry_language']          = 'Language:';
$_['entry_currency']          = 'Currency:';
$_['entry_currency_auto']     = 'Auto Update Currency:<br /><span class="help">Set your store to automatically update currencies daily.</span>';
$_['entry_weight_class']      = 'Weight Class:';
$_['entry_length_class']      = 'Length Class:';
$_['entry_alert_mail']        = 'Alert Mail:<br /><span class="help">Send a email to the store owner when a new order is created.</span>';
$_['entry_download']          = 'Allow Downloads:';
$_['entry_download_status']   = 'Download Order Status:<br /><span class="help">Set the order status the customers order must reach before they are allowed to access their downloadable products.</span>';
$_['entry_mail_protocol']     = 'Mail Protocol:<span class="help">Only choose \'Mail\' unless your host has disabled the php mail function.';
$_['entry_smtp_host']         = 'SMTP Host:';
$_['entry_smtp_username']     = 'SMTP Username:';
$_['entry_smtp_password']     = 'SMTP Password:';
$_['entry_smtp_port']         = 'SMTP Port:';
$_['entry_smtp_timeout']      = 'SMTP Timeout:';
$_['entry_ssl']               = 'Use SSL:<br /><span class="help">To use SSL check with your host if a SSL certificate is installed.</span>';
$_['entry_encryption']        = 'Encryption Key:<br /><span class="help">Please provide a secret key that will be used to encrypt private information when processing orders.</span>';
$_['entry_seo_url']           = 'Use SEO URL\'s:<br /><span class="help">To use SEO URL\'s apache module mod-rewrite must be installed and you need to rename the htaccess.txt to .htaccess.</span>';
$_['entry_compression']       = 'Output Compression Level:<br /><span class="help">GZIP for more efficient transfer to requesting clients. Compression level must be between 0 - 9</span>';
$_['entry_error_display']     = 'Display Errors:';
$_['entry_error_log']         = 'Log Errors:';
$_['entry_error_filename']    = 'Error Log Filename:';

// Error
$_['error_permission']        = 'Warning: You do not have permission to modify settings!';
$_['error_title']             = 'Title must be greater than 3 and less than 32 characters!';
$_['error_owner']             = 'Store Owner must be greater than 3 and less than 64 characters!';
$_['error_address']           = 'Store Address must be greater than 10 and less than 256 characters!';
$_['error_email']             = 'E-Mail Address does not appear to be valid!';
$_['error_telephone']         = 'Telephone must be greater than 3 and less than 32 characters!';
$_['error_error_filename']    = 'Error Log Filename required!';
?>