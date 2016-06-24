<?php
// Site
$_['site_base']         = HTTP_SERVER;
$_['site_ssl']          = HTTPS_SERVER;

// Database
$_['db_autostart']      = true;
$_['db_type']           = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname']       = DB_HOSTNAME;
$_['db_username']       = DB_USERNAME;
$_['db_password']       = DB_PASSWORD;
$_['db_database']       = DB_DATABASE;
$_['db_port']           = DB_PORT;

// Session
$_['session_autostart'] = true;

// Actions
$_['action_pre_action'] = array(
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/sass',
	'startup/login',
	'startup/permission',
	'startup/compatibility'
);

// Actions
$_['action_default'] = 'common/dashboard';

// Action Events
$_['action_event'] = array(
    'view/*/before'                           => 'event/theme',
	'controller/extension/analytics/*/before' => 'event/compatibility/controller',
	'controller/extension/captcha/*/before'   => 'event/compatibility/controller',
	'controller/extension/feed/*/before'      => 'event/compatibility/controller',
	'controller/extension/fraud/*/before'     => 'event/compatibility/controller',
	'controller/extension/module/*/before'    => 'event/compatibility/controller',
	'controller/extension/payment/*/before'   => 'event/compatibility/controller',
	'controller/extension/recurring/*/before' => 'event/compatibility/controller',
	'controller/extension/shipping/*/before'  => 'event/compatibility/controller',
	'controller/extension/theme/*/before'     => 'event/compatibility/controller',
	'controller/extension/total/*/before'     => 'event/compatibility/controller',
	'language/extension/*/before'             => 'event/compatibility/language'
);
