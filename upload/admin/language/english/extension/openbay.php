<?php
// Heading
$_['heading_title']        				= 'OpenBay Pro';

// Buttons
$_['button_retry']						= 'Retry';
$_['button_faq_clear']					= 'Clear';
$_['button_update']						= 'Update';
$_['button_patch']						= 'Patch';
$_['button_ftp_test']					= 'Test connection';
$_['button_faq']						= 'View FAQ';

// Tab
$_['tab_setting']						= 'Settings';
$_['tab_update']						= 'Updates';
$_['tab_patch']							= 'Patch';

// Text
$_['text_install']              		= 'Install';
$_['text_uninstall']            		= 'Uninstall';
$_['text_success_settings']         	= 'Success: Settings have been saved';
$_['text_text_no_results']      		= 'No results found';
$_['text_products']          			= 'Items';
$_['text_orders']          				= 'Orders';
$_['text_manage']          				= 'Manage';
$_['text_help']                     	= 'Help';
$_['text_tutorials']                    = 'Tutorials';
$_['text_suggestions']                  = 'Ideas';
$_['text_checking_version']     		= 'Checking software version';

$_['text_latest']               = 'You are running the latest version';
$_['text_installed_version']    = 'Installed version of OpenBay Pro: v';
$_['text_version_old_1']        = 'Your version is';
$_['text_version_old_2']        = 'the latest is';
$_['text_no']                   = 'No';
$_['text_yes']                  = 'Yes';
$_['text_language']             = 'API response language';
$_['text_getting_messages']     = 'Getting OpenBay Pro messages';
$_['text_complete']     = 'Complete';

// Column
$_['text_column_name']          = 'Plugin name';
$_['text_column_status']        = 'Status';
$_['text_column_action']        = 'Action';

// Updates
$_['entry_ftp_username']                    = 'FTP Username';
$_['entry_ftp_password']                      = 'FTP Password';
$_['entry_ftp_server']          = 'FTP server address';
$_['entry_ftp_root']               = 'FTP path on server';
$_['entry_ftp_admin']            		= 'Admin directory';
$_['entry_ftp_pasv']                    = 'PASV mode';
$_['entry_ftp_beta']             		= 'Use beta version';
$_['text_test_connection']              = 'Test FTP connection';
$_['text_run_update']           		= 'Run update';
$_['text_run']           				= 'Run';

//Updates
$_['text_patch']            			= 'Post update patch';
$_['text_patch_button']                 = 'Patch';
$_['text_patch_complete']           	= 'Patch has been applied';
$_['update_error_username']             = 'Username expected';
$_['update_error_password']             = 'Password expected';
$_['update_error_server']               = 'Server expected';
$_['update_error_admindir']             = 'Admin directory expected';
$_['update_okcon_noadmin']              = 'Connection OK but your OpenCart admin directory was not found';
$_['update_okcon_nofiles']              = 'Connection OK but OpenCart folders were not found! Is your root path correct?';
$_['update_okcon']                      = 'Connected to server OK. OpenCart folders found';
$_['update_failed_user']                = 'Could not login with that user';
$_['update_failed_connect']             = 'Could not connect to server';
$_['update_success']                    = 'Module has been updated (v.%s)';
$_['text_update_description']           = "The update tool will make changes to your shop's file system. Make sure you have a backup before using this tool.";

$_['error_mcrypt_not_enabled']          = 'PHP function "mcrypt_encrypt" is not enabled. Contact your hosting provider.';
$_['error_mb_not_enabled']              = 'PHP library "mb strings" is not enabled. Contact your hosting provider.';
$_['error_ftp_not_enabled']             = 'PHP FTP functions are not enabled. Contact your hosting provider.';
$_['error_oc_version']             		= 'Your version of OpenCart is not tested to work with this module. You may experience problems.';
$_['error_failed_to_load']              = 'Failed to load, retry?';

$_['text_clear_faq']                    = 'Clear hidden FAQ popups';
$_['text_clear_faq_complete']           = 'Notifications will now show again';

// Ajax elements
$_['text_ajax_ebay_shipped']            = 'The order will be marked as shipped on eBay automatically';
$_['text_ajax_amazoneu_shipped']        = 'The order will be marked as shipped on Amazon EU automatically';
$_['text_ajax_amazonus_shipped']        = 'The order will be marked as shipped on Amazon US automatically';
$_['text_ajax_refund_reason']           = 'Refund reason';
$_['text_ajax_refund_message']          = 'Refund message';
$_['text_ajax_refund_entermsg']         = 'You must enter a refund message';
$_['text_ajax_refund_charmsg']          = 'Your refund message must be less than 1000 characters';
$_['text_ajax_refund_charmsg2']         = 'Your message cannot contain the characters > or <';
$_['text_ajax_courier']                 = 'Courier';
$_['text_ajax_courier_other']           = 'Other courier';
$_['text_ajax_tracking']                = 'Tracking #';
$_['text_ajax_tracking_msg']            = 'You must enter a tracking id, use "none" if you do not have one';
$_['text_ajax_tracking_msg2']           = 'Your tracking ID cannot contain the characters > or <';
$_['text_ajax_tracking_msg3']           = 'You must select courier if you want to upload tracking no.';
$_['text_ajax_tracking_msg4']           = 'Please leave courier field empty if you want to use custom courier.';

$_['text_title_help']                   = 'Need help with OpenBay Pro?';
$_['text_title_manage']                 = 'Manage OpenBay Pro; updates, settings and more';
$_['text_title_shop']                   = 'OpenBay Pro store; addons, templates and more';
$_['text_install_success']              = 'Marketplace has been installed';
$_['text_uninstall_success']            = 'Marketplace has been removed';
$_['text_error_permission']             = 'You do not have permission to modify the OpenBay Pro extension';

$_['text_checking_messages']            = 'Checking for messages';
$_['text_title_messages']               = 'Messages &amp; notifications';

// Help
$_['help_ftp_username']           		= 'Use the FTP username from your host';
$_['help_ftp_password']           		= 'Use the FTP password from your host';
$_['help_ftp_server']      				= 'IP address or domain name for your FTP server';
$_['help_ftp_root']           			= '(No trailing slash e.g. httpdocs/www)';
$_['help_ftp_admin']               		= 'If you have changed your admin directory update it to the new location';
$_['help_ftp_pasv']                    	= 'Change your FTP connection to passive mode';
$_['help_ftp_beta']             		= 'Caution! The beta version may not work correctly';
$_['help_patch']						= 'If you update your files through FTP you need to run the patch to complete the update';
$_['help_clear_faq']					= 'Show all of the help notifications again';