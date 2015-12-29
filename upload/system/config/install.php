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
$_['action.event']      = array(
	'model/upgrade/upgrade/upgrade/after' => 'upgrade/1.5.5.0',
	'model/upgrade/upgrade/upgrade/after' => 'upgrade/2.0.1.1',
	'model/upgrade/upgrade/upgrade/after' => 'upgrade/2.10.0',
	'model/upgrade/upgrade/upgrade/after' => 'upgrade/2.2.0.0'
);