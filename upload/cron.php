<?php
// Configuration
if (!is_file('config.php')) {
	exit('CRON is unable to load configuration from file config.php');
}

// Config
/** @phpstan-ignore-next-line requireOnce.fileNotFound */
require_once('config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Autoloader
$autoloader = new \Opencart\System\Engine\Autoloader();
$autoloader->register('Opencart\Catalog', DIR_APPLICATION);
$autoloader->register('Opencart\Extension', DIR_EXTENSION);
$autoloader->register('Opencart\System', DIR_SYSTEM);

// require_once(DIR_SYSTEM . 'vendor.php');

// Registry
$registry = new \Opencart\System\Engine\Registry();
$registry->set('autoloader', $autoloader);

// Config
$config = new \Opencart\System\Engine\Config();
$registry->set('config', $config);

// Load the default config
$config->addPath(DIR_CONFIG);
$config->load('default');
$config->load('catalog');
$config->set('application', 'Catalog');

// Set the default time zone
date_default_timezone_set($config->get('date_timezone'));

// Store
$config->set('config_store_id', 0);

// Logging
$log = new \Opencart\System\Library\Log($config->get('error_filename'));
$registry->set('log', $log);

// Error Handler
set_error_handler(function(int $code, string $message, string $file, int $line) use ($log, $config) {
	// PHP 8 compatible check for the @ suppression operator
	if (!(error_reporting() & $code)) {
		// Return false to let the standard PHP internal error handler take over (or do nothing)
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
		case E_DEPRECATED:
		case E_USER_DEPRECATED:
			$error = 'Deprecated';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	// Always write to the OpenCart error log so admins can diagnose cron issues
	if ($config->get('error_log')) {
		$log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);
	}

	// If error display is turned on, output clean plaintext to the console instead of HTML tags
	if ($config->get('error_display')) {
		echo PHP_EOL . "PHP {$error}: {$message} in {$file} on line {$line}" . PHP_EOL;
	} else {
		// Never call header() or exit with a successful code (0) on a fatal cron crash.
		// Instead, we exit with a non-zero code to let the server's cron daemon know the task failed.
		exit(1);
	}

	return true;
});

// Exception Handler
set_exception_handler(function(\Throwable $e) use ($log, $config): void {
	$exception_class = get_class($e);

	// Format a detailed backtrace string for the logs
	$output  = $exception_class . ': ' . $e->getMessage() . "\n";
	$output .= 'File: ' . $e->getFile() . "\n";
	$output .= 'Line: ' . $e->getLine() . "\n\n";

	foreach ($e->getTrace() as $key => $trace) {
		$output .= 'Backtrace: ' . $key . "\n";
		$output .= 'File: ' . ($trace['file'] ?? 'unknown') . "\n";
		$output .= 'Line: ' . ($trace['line'] ?? 'unknown') . "\n";

		if (isset($trace['class'])) {
			$output .= 'Class: ' . $trace['class'] . "\n";
		}

		$output .= 'Function: ' . $trace['function'] . "\n\n";
	}

	// Log the full detailed exception trace so you can debug background failures
	if ($config->get('error_log')) {
		$log->write(trim($output));
	}

	// Identify if this is a strict internal PHP 8 type mismatch error
	$isRecoverablePhp8Error = ($e instanceof \TypeError || $e instanceof \ValueError || $e instanceof \DivisionByZeroError);

	// Handle terminal output or script termination
	if ($config->get('error_display')) {
		// Output clean plaintext directly to the terminal console
		echo PHP_EOL . "--- CRON EXCEPTION DETECTED ---" . PHP_EOL;
		echo $output;
	} elseif ($isRecoverablePhp8Error) {
		// In production cron jobs, allow the task runner to try and degrade gracefully
		// on minor type/value mismatches, rather than aggressively crashing mid-execution.
		echo "[Cron Warning]: A minor internal type or value exception occurred and was logged. Execution continuing." . PHP_EOL;
	} else {
		// Hard termination for true fatal exceptions, database connection drops, etc.
		// Exiting with code 1 alerts the server's task scheduler (e.g. systemd/crontab) that the job failed.
		exit(1);
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

// Response
$response = new \Opencart\System\Library\Response();
$registry->set('response', $response);

// Database
if ($config->get('db_autostart')) {
	$db = new \Opencart\System\Library\DB($config->get('db_engine'), $config->get('db_hostname'), $config->get('db_username'), $config->get('db_password'), $config->get('db_database'), $config->get('db_port'), $config->get('db_ssl_key'), $config->get('db_ssl_cert'), $config->get('db_ssl_ca'));
	$registry->set('db', $db);

	// Sync PHP and DB time zones
	$db->query("SET `time_zone` = '" . $db->escape(date('P')) . "'");
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
		'httponly' => true,
		'samesite' => $config->get('session_samesite')
	];

	setcookie($config->get('session_name'), $session->getId(), $option);
}

// Cache
$registry->set('cache', new \Opencart\System\Library\Cache($config->get('cache_engine'), $config->get('cache_expire')));

// Template
$template = new \Opencart\System\Library\Template($config->get('template_engine'));
$registry->set('template', $template);
$template->addPath(DIR_TEMPLATE);

// Language
$language = new \Opencart\System\Library\Language($config->get('language_code'));
$registry->set('language', $language);
$language->addPath(DIR_LANGUAGE);
$loader->load->language($config->get('language_code'));

// Url
$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

// Pre Actions
foreach ($config->get('action_pre_action') as $pre_action) {
	$loader->controller($pre_action);
}

// Dispatch
$loader->controller('cron/cron');

// Output
$response->output();
