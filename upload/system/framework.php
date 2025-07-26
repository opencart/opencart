<?php
// Autoloader
$autoloader = new \Opencart\System\Engine\Autoloader();
$autoloader->register('Opencart\\' . APPLICATION, DIR_APPLICATION);
$autoloader->register('Opencart\Extension', DIR_EXTENSION);
$autoloader->register('Opencart\System', DIR_SYSTEM);

//require_once(DIR_SYSTEM . 'helper/vendor.php');
//oc_generate_vendor();

require_once(DIR_SYSTEM . 'vendor.php');

// Registry
$registry = new \Opencart\System\Engine\Registry();
$registry->set('autoloader', $autoloader);

// Config
$config = new \Opencart\System\Engine\Config();
$config->addPath(DIR_CONFIG);

// Load the default config
$config->load('default');
$config->load(strtolower(APPLICATION));
$registry->set('config', $config);

// Set the default application
$config->set('application', APPLICATION);

// Set the default time zone
date_default_timezone_set($config->get('date_timezone'));

// Logging
$log = new \Opencart\System\Library\Log($config->get('error_filename'));
$registry->set('log', $log);

// Error Handler
set_error_handler(function(int $code, string $message, string $file, int $line) {
	// error suppressed with @
	if (!(error_reporting() & $code)) {
		return false;
	}

	throw new \ErrorException($message, 0, $code, $file, $line);
});

// Exception Handler
set_exception_handler(function(object $e) use ($log, $config): void {
	$message = $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();

	if ($config->get('error_log')) {
		$log->write($message);
	}

	if ($config->get('error_display')) {
		echo $message;
	} else {
		header('Location: ' . $config->get('error_page'));
		exit();
	}
});

// Event
$event = new \Opencart\System\Engine\Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action_event')) {
	foreach ($config->get('action_event') as $key => $value) {
		foreach ($value as $priority => $action) {
			$event->register($key, new \Opencart\System\Engine\Action($action), $priority);
		}
	}
}

// Factory
$registry->set('factory', new \Opencart\System\Engine\Factory($registry));

// Loader
$loader = new \Opencart\System\Engine\Loader($registry);
$registry->set('load', $loader);

// Request
$request = new \Opencart\System\Library\Request();
$registry->set('request', $request);

// Compatibility
if (isset($request->get['route'])) {
	$request->get['route'] = str_replace('|', '.', $request->get['route']);
	$request->get['route'] = str_replace('%7C', '|', (string)$request->get['route']);
}

// Response
$response = new \Opencart\System\Library\Response();
$registry->set('response', $response);

// For none command line
if (php_sapi_name() != 'cli') {
	foreach ($config->get('response_header') as $header) {
		$response->addHeader($header);
	}

	$response->addHeader('Access-Control-Allow-Origin: *');
	$response->addHeader('Access-Control-Allow-Credentials: true');
	$response->addHeader('Access-Control-Max-Age: 1000');
	$response->addHeader('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding');
	$response->addHeader('Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE');
	$response->addHeader('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	$response->addHeader('Pragma: no-cache');
	$response->setCompression((int)$config->get('response_compression'));
}

// Database
if ($config->get('db_autostart')) {
	$db = new \Opencart\System\Library\DB($config->get('db_option'));
	$registry->set('db', $db);
}

// Session
if ($config->get('session_autostart')) {
	$session = new \Opencart\System\Library\Session($config->get('session_engine'), $registry);
	$registry->set('session', $session);

	if (isset($request->cookie[$config->get('session_name')])) {
		$session_id = $request->cookie[$config->get('session_name')];
	} else {
		$session_id = '';
	}

	$session->start($session_id);

	// Require higher security for session cookies
	$option = [
		'expires'  => 0,
		'path'     => $config->get('session_path'),
		'domain'   => $config->get('session_domain'),
		'secure'   => $request->server['HTTPS'],
		'httponly' => false,
		'SameSite' => $config->get('session_samesite')
	];

	setcookie($config->get('session_name'), $session->getId(), $option);
}

// Cache
$registry->set('cache', new \Opencart\System\Library\Cache($config->get('cache_engine'), $config->get('cache_expire')));

// Template
$template = new \Opencart\System\Library\Template($config->get('template_engine'));
$template->addPath(DIR_TEMPLATE);
$registry->set('template', $template);

// Language
$language = new \Opencart\System\Library\Language($config->get('language_code'));
$language->addPath(DIR_LANGUAGE);
$language->load('default');
$registry->set('language', $language);

// Url
$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

// Document
$registry->set('document', new \Opencart\System\Library\Document());

$action = '';
$args = [];

// Action error object to execute if any other actions cannot be executed.
$error = new \Opencart\System\Engine\Action($config->get('action_error'));

// Pre Actions
foreach ($config->get('action_pre_action') as $pre_action) {
	$pre_action = new \Opencart\System\Engine\Action($pre_action);

	$result = $pre_action->execute($registry, $args);

	if ($result instanceof \Opencart\System\Engine\Action) {
		$action = $result;

		break;
	}

	// If action cannot be executed, we return an action error object.
	if ($result instanceof \Exception) {
		$action = $error;

		// In case there is an error we only want to execute once.
		$error = '';

		break;
	}
}

// Route
if (isset($request->get['route'])) {
	$route = (string)$request->get['route'];
} else {
	$route = (string)$config->get('action_default');
}

// To block calls to controller methods we want to keep from being accessed directly
if (str_contains($route, '._')) {
	$action = new \Opencart\System\Engine\Action($config->get('action_error'));
}

if ($action) {
	$route = $action->getId();
}

// Keep the original trigger
$trigger = $route;

$args = [];

// Trigger the pre events
$event->trigger('controller/' . $trigger . '/before', [&$route, &$args]);

// Action to execute
if (!$action) {
	$action = new \Opencart\System\Engine\Action($route);
}

// Dispatch
while ($action) {
	// Execute action
	$output = $action->execute($registry, $args);

	// Make action a non-object so it's not infinitely looping
	$action = '';

	// Action object returned then we keep the loop going
	if ($output instanceof \Opencart\System\Engine\Action) {
		$action = $output;
	}

	// If action cannot be executed, we return the action error object.
	if ($output instanceof \Exception) {
		$action = $error;

		// In case there is an error we don't want to infinitely keep calling the action error object.
		$error = '';
	}
}

// Trigger the post events
$event->trigger('controller/' . $trigger . '/after', [&$route, &$args, &$output]);

// Output
$response->output();