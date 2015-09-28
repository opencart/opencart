<?php
// Heading
$_['heading_title']        				= 'OpenBay Pro';

// Buttons
$_['button_retry']						= 'Retry';
$_['button_update']						= 'Update';
$_['button_patch']						= 'Patch';
$_['button_ftp_test']					= 'Test connection';
$_['button_faq']						= 'View FAQ topic';

// Tab
$_['tab_setting']						= 'Settings';
$_['tab_update']						= 'Software updates';
$_['tab_update_v1']						= 'Easy updater';
$_['tab_update_v2']						= 'Legacy updater';
$_['tab_patch']							= 'Patch';
$_['tab_developer']						= 'Developer';

// Text
$_['text_dashboard']         			= 'Dashboard';
$_['text_success']         				= 'Success: Settings have been saved';
$_['text_products']          			= 'Items';
$_['text_orders']          				= 'Orders';
$_['text_manage']          				= 'Manage';
$_['text_help']                     	= 'Help';
$_['text_tutorials']                    = 'Tutorials';
$_['text_suggestions']                  = 'Ideas';
$_['text_version_latest']               = 'You are running the latest version';
$_['text_version_check']     			= 'Checking software version';
$_['text_version_installed']    		= 'Installed version of OpenBay Pro: v';
$_['text_version_current']        		= 'Your version is';
$_['text_version_available']        	= 'the latest is';
$_['text_language']             		= 'API response language';
$_['text_getting_messages']     		= 'Getting OpenBay Pro messages';
$_['text_complete']     				= 'Complete';
$_['text_test_connection']              = 'Test FTP connection';
$_['text_run_update']           		= 'Run update';
$_['text_patch_complete']           	= 'Patch has been applied';
$_['text_connection_ok']				= 'Connected to server OK. OpenCart folders found';
$_['text_updated']						= 'Module has been updated (v.%s)';
$_['text_update_description']			= 'The update tool will make changes to your shop file system. Make sure you have a complete file and database backup before updating.';
$_['text_patch_description']			= 'If you uploaded the update files manually, you need to run the patch to complete the update';
$_['text_clear_faq']                    = 'Clear hidden FAQ popups';
$_['text_clear_faq_complete']           = 'Notifications will now show again';
$_['text_install_success']              = 'Marketplace has been installed';
$_['text_uninstall_success']            = 'Marketplace has been removed';
$_['text_title_messages']               = 'Messages &amp; notifications';
$_['text_marketplace_shipped']			= 'The order status will be updated to shipped on the marketplace';
$_['text_action_warning']				= 'This action is dangerous so is password protected.';
$_['text_check_new']					= 'Checking for newer version';
$_['text_downloading']					= 'Downloading update files';
$_['text_extracting']					= 'Extracting files';
$_['text_running_patch']				= 'Running patch files';
$_['text_fail_patch']					= 'Unable to extract update files';
$_['text_updated_ok']					= 'Update complete, installed version is now ';
$_['text_check_server']					= 'Checking server requirements';
$_['text_version_ok']					= 'Software is already up to date, installed version is ';
$_['text_remove_files']					= 'Removing files no longer required';
$_['text_confirm_backup']				= 'Ensure that you have a full backup before continuing';

// Column
$_['column_name']          				= 'Plugin name';
$_['column_status']        				= 'Status';
$_['column_action']        				= 'Action';

// Entry
$_['entry_patch']            			= 'Manual update patch';
$_['entry_ftp_username']				= 'FTP Username';
$_['entry_ftp_password']				= 'FTP Password';
$_['entry_ftp_server']					= 'FTP server address';
$_['entry_ftp_root']					= 'FTP path on server';
$_['entry_ftp_admin']            		= 'Admin directory';
$_['entry_ftp_pasv']                    = 'PASV mode';
$_['entry_ftp_beta']             		= 'Use beta version';
$_['entry_courier']						= 'Courier';
$_['entry_courier_other']           	= 'Other courier';
$_['entry_tracking']                	= 'Tracking #';
$_['entry_empty_data']					= 'Empty store data?';
$_['entry_password_prompt']				= 'Please enter the data wipe password';
$_['entry_update']						= 'Easy 1 click update';

// Error
$_['error_username']             		= 'FTP username required';
$_['error_password']             		= 'FTP password required';
$_['error_server']               		= 'FTP server required';
$_['error_admin']             			= 'Admin directory expected';
$_['error_no_admin']					= 'Connection OK but your OpenCart admin directory was not found';
$_['error_no_files']					= 'Connection OK but OpenCart folders were not found! Is your root path correct?';
$_['error_ftp_login']					= 'Could not login with that user';
$_['error_ftp_connect']					= 'Could not connect to server';
$_['error_failed']						= 'Failed to load, retry?';
$_['error_tracking_id_format']			= 'Your tracking ID cannot contain the characters > or <';
$_['error_tracking_courier']			= 'You must select a courier if you want to add a tracking ID';
$_['error_tracking_custom']				= 'Please leave courier field empty if you want to use custom courier';
$_['error_permission']					= 'You do not have permission to modify the OpenBay Pro extension';
$_['error_mkdir']						= 'PHP mkdir function is disabled, contact your host';
$_['error_file_delete']					= 'Unable to remove these files, you should delete them manually';
$_['error_mcrypt']            			= 'PHP function "mcrypt_encrypt" is not enabled. Contact your hosting provider.';
$_['error_mbstring']               		= 'PHP library "mb strings" is not enabled. Contact your hosting provider.';
$_['error_ftpconnect']             		= 'PHP FTP functions are not enabled. Contact your hosting provider.';
$_['error_oc_version']             		= 'Your version of OpenCart is not tested to work with this module. You may experience problems.';
$_['error_fopen']             			= 'PHP function "fopen" is disabled by your host - you will be unable to import images when importing products';
$_['lang_error_vqmod']             		= 'Your vqmod folder contains older OpenBay Pro files - these need to be removed!';

// Help
$_['help_ftp_username']           		= 'Use the FTP username from your host';
$_['help_ftp_password']           		= 'Use the FTP password from your host';
$_['help_ftp_server']      				= 'IP address or domain name for your FTP server';
$_['help_ftp_root']           			= '(No trailing slash e.g. httpdocs/www)';
$_['help_ftp_admin']               		= 'If you have changed your admin directory update it to the new location';
$_['help_ftp_pasv']                    	= 'Change your FTP connection to passive mode';
$_['help_ftp_beta']             		= 'Caution! The beta version may not work correctly';
$_['help_clear_faq']					= 'Show all of the help notifications again';
$_['help_empty_data']					= 'This can cause serious damage, do not use it if you do not know what it does!';
$_['help_easy_update']					= 'Click update to install the latest version of OpenBay Pro automatically';
$_['help_patch']						= 'Click to run the patch scripts';