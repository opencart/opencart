<?php
// Site
$_['site_base']        = HTTP_SERVER;
$_['site_ssl']         = HTTPS_SERVER;

//Url
$_['url_autostart']    = false;

// Database
$_['db_autostart']     = true;
$_['db_type']          = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname']      = DB_HOSTNAME;
$_['db_username']      = DB_USERNAME;
$_['db_password']      = DB_PASSWORD;
$_['db_database']      = DB_DATABASE;
$_['db_port']          = DB_PORT;

// Autoload Libraries
$_['library_autoload'] = array(
	'openbay'
);

// Actions
$_['action_pre_action'] = array(
	'startup/session',
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/maintenance',
	'startup/seo_url'
);

// Action Events
$_['action_event'] = array(
	'view/*/before'               => 'event/theme',
	'language/extension/*/before' => 'event/translation',
	'language/extension/*/after'  => 'event/compatibility/language',
	
	'model/extension/analytics/*/before'     => 'event/compatibility/beforeModel',
	'model/extension/captcha/*/before'       => 'event/compatibility/beforeModel',
	'model/extension/credit_card/*/before'   => 'event/compatibility/afterModel',
	'model/extension/feed/*/before'          => 'event/compatibility/afterModel',
	'model/extension/fraud/*/before'         => 'event/compatibility/afterModel',
	'model/extension/module/*/before'        => 'event/compatibility/afterModel',
	'model/extension/payment/*/before'       => 'event/compatibility/afterModel',
	'model/extension/recurring/*/before'     => 'event/compatibility/afterModel',
	'model/extension/shipping/*/before'      => 'event/compatibility/afterModel',
	'model/extension/theme/*/before'         => 'event/compatibility/afterModel',
	'model/extension/total/*/before'         => 'event/compatibility/afterModel',
		
	'model/analytics/*/after'     => 'event/compatibility/afterModel',
	'model/captcha/*/after'       => 'event/compatibility/afterModel',
	'model/credit_card/*/after'   => 'event/compatibility/afterModel',
	'model/feed/*/after'          => 'event/compatibility/afterModel',
	'model/fraud/*/after'         => 'event/compatibility/afterModel',
	'model/module/*/after'        => 'event/compatibility/afterModel',
	'model/payment/*/after'       => 'event/compatibility/afterModel',
	'model/recurring/*/after'     => 'event/compatibility/afterModel',
	'model/shipping/*/after'      => 'event/compatibility/afterModel',
	'model/theme/*/after'         => 'event/compatibility/afterModel',
	'model/total/*/after'         => 'event/compatibility/afterModel'
	//'controller/*/before'       => 'event/debug/before',
	//'controller/*/after'        => 'event/debug/after'
);