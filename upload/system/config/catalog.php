<?php
$_['config_language']    = 'en-gb';
$_['config_cache']       = 'file';
$_['session'] = array(
	'type'      => 'file',
	'autostart' => false
);
$_['config_error_log']   = 'error.txt';
$_['config_default']     = '';
$_['config_route']       = 'action/route';
$_['config_error']       = 'error/not_found';
$_['config_pre_action']  = array(
	'action/setting',
	'action/error',
	'action/session',
	'action/langage',
	'action/maintenance',
	'action/cart',
	'action/seo_url',
	'action/event',
	'action/route'
);