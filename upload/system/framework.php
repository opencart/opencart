<?php
namespace Opencart;
function start($application) {
	// Autoloader
	$autoloader = new System\Engine\Autoloader();

	// Registry
	$registry = new System\Engine\Registry();
	$registry->set('autoloader', $autoloader);

	// Config
	$config = new System\Engine\Config();

	// Load the default config
	$config->load('default');
	$config->load($application);
	$registry->set('config', $config);

	// Log
	$log = new System\Library\Log($config->get('error_filename'));
	$registry->set('log', $log);

	date_default_timezone_set($config->get('date_timezone'));

	set_error_handler(function ($code, $message, $file, $line) use ($log, $config) {
		// error suppressed with @
		if (error_reporting() === 0) {
			return false;
		}

		switch ($code) {
			case E_NOTICE:
			case E_USER_NOTICE:
				$error = 'Notice';
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$error = 'Warning';
				break;
			case E_ERROR:
			case E_USER_ERROR:
				$error = 'Fatal Error';
				break;
			default:
				$error = 'Unknown';
				break;
		}

		if ($config->get('error_display')) {
			echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
		}

		if ($config->get('error_log')) {
			$log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);
		}

		return true;
	});

	set_exception_handler(function ($e) use ($log, $config) {
		if ($config->get('error_display')) {
			echo '<b>' . get_class($e) . '</b>: ' . $e->getMessage() . ' in <b>' . $e->getFile() . '</b> on line <b>' . $e->getLine() . '</b>';
		}

		if ($config->get('error_log')) {
			$log->write(get_class($e) . ':  ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
		}
	});

	// Event
	$event = new System\Engine\Event($registry);
	$registry->set('event', $event);

	// Event Register
	if ($config->has('action_event')) {
		foreach ($config->get('action_event') as $key => $value) {
			foreach ($value as $priority => $action) {
				$event->register($key, new System\Engine\Action($action), $priority);
			}
		}
	}

	// Loader
	$loader = new System\Engine\Loader($registry);
	$registry->set('load', $loader);

	// Request
	$request = new System\Library\Request();
	$registry->set('request', $request);

	// Response
	$response = new System\Library\Response();
	foreach ($config->get('response_header') as $header) {
		$response->addHeader($header);
	}
	$response->setCompression($config->get('response_compression'));
	$registry->set('response', $response);

	// Database
	if ($config->get('db_autostart')) {
		$db = new System\Library\DB($config->get('db_engine'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port'));
		$registry->set('db', $db);

		// Sync PHP and DB time zones
		$db->query("SET time_zone = '" . $db->escape(date('P')) . "'");
	}

	// Session
	$session = new System\Library\Session($config->get('session_engine'), $registry);
	$registry->set('session', $session);

	if ($config->get('session_autostart')) {
		/*
		We are adding the session cookie outside of the session class as I believe
		PHP messed up in a big way handling sessions. Why in the hell is it so hard to
		have more than one concurrent session using cookies!

		Is it not better to have multiple cookies when accessing parts of the system
		that requires different cookie sessions for security reasons.

		Also cookies can be accessed via the URL parameters. So why force only one cookie
		for all sessions!
		*/

		if (isset($_COOKIE[$config->get('session_name')])) {
			$session_id = $_COOKIE[$config->get('session_name')];
		} else {
			$session_id = '';
		}

		$session->start($session_id);

		// Require higher security for session cookies
		$option = [
			'max-age' => time() + $config->get('session_expire'),
			'path' => !empty($_SERVER['PHP_SELF']) ? dirname($_SERVER['PHP_SELF']) . '/' : '',
			'domain' => $_SERVER['HTTP_HOST'],
			'secure' => $_SERVER['HTTPS'],
			'httponly' => false,
			'SameSite' => 'strict'
		];

		oc_setcookie($config->get('session_name'), $session->getId(), $option);
	}

	// Cache
	$registry->set('cache', new System\Library\Cache($config->get('cache_engine'), $config->get('cache_expire')));

	// Url
	$registry->set('url', new System\Library\Url($config->get('site_url')));

	// Language
	$registry->set('language', new System\Library\Language($config->get('language_directory')));

	// Document
	$registry->set('document', new System\Library\Document());

	// Config Autoload
	if ($config->has('config_autoload')) {
		foreach ($config->get('config_autoload') as $value) {
			$loader->config($value);
		}
	}

	// Language Autoload
	if ($config->has('language_autoload')) {
		foreach ($config->get('language_autoload') as $value) {
			$loader->language($value);
		}
	}

	// Helper Autoload
	if ($config->has('helper_autoload')) {
		foreach ($config->get('helper_autoload') as $value) {
			$loader->helper($value);
		}
	}

	// Library Autoload
	if ($config->has('library_autoload')) {
		foreach ($config->get('library_autoload') as $value) {
			$loader->library($value);
		}
	}

	// Model Autoload
	if ($config->has('model_autoload')) {
		foreach ($config->get('model_autoload') as $value) {
			$loader->model($value);
		}
	}

	// Route
	if (!empty($request->get['route'])) {
		$action = new System\Engine\Action((string)$request->get['route']);
	} else {
		$action = new System\Engine\Action($config->get('action_default'));
	}

	// Action error object to execute if any other actions can not be executed.
	$error = new System\Engine\Action($config->get('action_error'));

	$pre_actions = $config->get('action_pre_action');

	// So the pre-actions can be changed or triggered.
	//$event->trigger('pre_action', array(&$pre_actions));

	// Pre Actions
	foreach ($pre_actions as $pre_action) {
		$pre_action = new System\Engine\Action($pre_action);

		$result = $pre_action->execute($registry);

		if ($result instanceof System\Engine\Action) {
			$action = $result;

			break;
		}

		// If action can not be executed then we return an action error object.
		if ($result instanceof Exception) {
			$action = $error;

			$error = '';
			break;
		}
	}

	// Dispatch
	while ($action) {
		// Get the route path of the object to be executed.
		$route = $action->getId();

		$args = [];

		// Keep the original trigger.
		$trigger = $action->getId();

		$event->trigger('controller/' . $trigger . '/before', [&$route, &$args]);

		// Execute the action.
		$result = $action->execute($registry, $args);

		$action = '';

		if ($result instanceof \System\Engine\Action) {
			$action = $result;
		}

		// If action can not be executed then we return the action error object.
		if ($result instanceof Exception) {
			$action = $error;

			// In case there is an error we don't want to infinitely keep calling the action error object.
			$error = '';
		}

		$event->trigger('controller/' . $trigger . '/after', [&$route, &$args, &$output]);
	}

	// Output
	$response->output();
}