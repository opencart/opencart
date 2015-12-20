<?php
$_['config_language']    = 'en-gb';
$_['config_cache']       = 'file';
$_['config.session_type'] = array(
	'type'      => 'file',
	'autostart' => false
);



$_['config_error_log']   = 'error.txt';

/*
Pre actions are paths to controllers to let you put in code that 
*/
$_['config_default']     = 'common/home';
$_['config_route']       = 'action/route';
$_['config_error']       = 'error/not_found';


$_['config_autoload'] = array(
	'customer',
	'affiliate',
	'currency',
	'tax',
	'weight',
	'length',
	'cart',
	'openbay'
);

/*
Pre actions are paths to controllers to let you put in code that 

*/
$_['config_pre_action'] = array(
	'action/setting',
	'action/error',
	'action/event',
	'action/session',
	'action/language',
	'action/cart',
	'action/maintenance',
	'action/seo_url',
	'action/route'
);