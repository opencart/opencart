<?php
// Site
$_['site.base']         = substr(HTTP_SERVER, 7);
$_['site.ssl']          = false;

// Language
$_['language.default']  = 'en-gb';
$_['language.autoload'] = array('en-gb');

// Actions
$_['action.default']    = 'install/step_1';
$_['action.router']     = 'startup/router';
$_['action.error']      = 'error/not_found';
$_['action.pre_action'] = array(
	'startup/language',
	'startup/upgrade',
	'startup/database'
);
$_['action.event']      = array();