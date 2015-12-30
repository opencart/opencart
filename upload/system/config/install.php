<?php
// Site
$_['site.base']         = substr(HTTP_SERVER, 7);
$_['site.ssl']          = false;

// Language
$_['language.default']  = 'en-gb';
$_['language.autoload'] = array('en-gb');

// Actions
$_['action.default']    = 'install/step_1';
$_['action.router']     = 'action/router';
$_['action.error']      = 'error/not_found';
$_['action.pre_action'] = array(
	'action/language',
	'action/upgrade',
	'action/database'
);
$_['action.event']      = array();