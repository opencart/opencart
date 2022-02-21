<?php
// Site
$_['site_url']          = HTTP_SERVER;

// Language
$_['language_code']     = 'en-gb';

// Template
$_['template_engine']   = 'twig';

// Error
$_['error_display']     = true;

// Actions
$_['action_default']    = 'install/step_1';
$_['action_error']      = 'error/not_found';
$_['action_pre_action'] = [
	'startup/install',
	'startup/upgrade',
	'startup/database'
];

// Action Events
$_['action_event']      = [];
