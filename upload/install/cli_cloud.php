<?php
//
// Command line tool for installing cloud version of opencart
//
// Usage:
//
//   Cloud Install
//
//   php cli_cloud.php install --username admin
//                             --email    email@example.com
//                             --password password
//

namespace Install;

ini_set('display_errors', 1);

error_reporting(E_ALL);

// APPLICATION
define('APPLICATION', 'Install');

// DIR
define('DIR_OPENCART', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . '/');
define('DIR_SYSTEM', DIR_OPENCART . 'system/');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Engine
require_once(DIR_SYSTEM . 'engine/controller.php');
require_once(DIR_SYSTEM . 'engine/registry.php');

// Library
require_once(DIR_SYSTEM . 'library/request.php');
require_once(DIR_SYSTEM . 'library/response.php');
require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/db/mysqli.php');

// Helper
require_once(DIR_SYSTEM . 'helper/db_schema.php');

// Registry
$registry = new \Opencart\System\Engine\Registry();

// Request
$registry->set('request', new \Opencart\System\Library\Request());

// Response
$response = new \Opencart\System\Library\Response();
$response->addHeader('Content-Type: text/plain; charset=utf-8');
$registry->set('response', $response);

set_error_handler(/**
 * @param       $code
 * @param       $message
 * @param       $file
 * @param       $line
 * @param array $errcontext
 *
 * @return false
 * @throws \ErrorException
 */ function($code, $message, $file, $line, array $errcontext) {
	// error was suppressed with the @-operator
	if (error_reporting() === 0) {
		return false;
	}

	throw new \ErrorException($message, 0, $code, $file, $line);
});

/**
 *
 */
class CliCloud extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		if (isset($this->request->server['argv'])) {
			$argv = $this->request->server['argv'];
		} else {
			$argv = [];
		}

		// Just displays the path to the file
		$script = array_shift($argv);

		// Get the arguments passed with the command
		$command = array_shift($argv);

		switch ($command) {
			case 'install':
				$output = $this->install($argv);
				break;
			case 'usage':
			default:
				$output = $this->usage($argv);
				break;
		}

		$this->response->setOutput($output);
	}

	/**
	 * @param $argv
	 *
	 * @return string
	 */
	public function install($argv): string {
		// Options
		$option = [];

		// Turn args into an array
		for ($i = 0; $i < count($argv); $i++) {
			if (substr($argv[$i], 0, 2) == '--') {
				$key = substr($argv[$i], 2);

				// If the next line also starts with -- we need to fill in a null value for the current one
				if (isset($argv[$i + 1]) && substr($argv[$i + 1], 0, 2) != '--') {
					$option[$key] = $argv[$i + 1];

					// Skip the counter by 2
					$i++;
				} else {
					$option[$key] = '';
				}
			}
		}

		// Required
		$required = [
			'username',
			'email',
			'password'
		];

		// Validation
		$missing = [];

		foreach ($required as $value) {
			if (!array_key_exists($value, $option)) {
				$missing[] = $value;
			}
		}

		if (count($missing)) {
			return 'ERROR: Following inputs were missing or invalid: ' . implode(', ', $missing)  . "\n";
		}

		// Pre-installation check
		$error = '';

		if ((oc_strlen($option['username']) < 3) || (oc_strlen($option['username']) > 20)) {
			$error .= 'ERROR: Username must be between 3 and 20 characters!' . "\n";
		}

		if ((oc_strlen($option['email']) > 96) || !filter_var($option['email'], FILTER_VALIDATE_EMAIL)) {
			$error .= 'ERROR: E-Mail Address does not appear to be valid!' . "\n";
		}

		if (!$option['password']) {
			$error .= 'ERROR: Password hash required!' . "\n";
		}

		if ($error) {
			$output  = 'ERROR: Validation failed: ' . "\n";
			$output .= $error . "\n\n";

			return $output;
		}

		// Make sure there is a SQL file to load sample data
		$file = DIR_OPENCART . 'install/opencart.sql';

		if (!is_file($file)) {
			return 'ERROR: Could not load SQL file: ' . $file;
		}

		$db_driver   = getenv('DB_DRIVER', true);
		$db_hostname = getenv('DB_HOSTNAME', true);
		$db_username = getenv('DB_USERNAME', true);
		$db_password = getenv('DB_PASSWORD', true);
		$db_database = getenv('DB_DATABASE', true);
		$db_port     = getenv('DB_PORT', true);
		$db_prefix   = getenv('DB_PREFIX', true);

		try {
			// Database
			$db = new \Opencart\System\Library\DB($db_driver, $db_hostname, $db_username, $db_password, $db_database, $db_port);
		} catch (\Exception $e) {
			return 'ERROR: Could not make a database link using ' . $db_username . '@' . $db_hostname . '!' . "\n";
		}

		// Set up Database structure
		$tables = oc_db_schema();

		$this->load->model('install/install');
		$this->model_install_install->createDatabaseSchema($db, $tables, $db_prefix);
		// If cloud we do not need to hash the password as we will be passing the password hash
		$admin_password = $option['password'];
		$this->model_install_install->setupDatabaseData($db, $file, $db_prefix, $option['username'], $admin_password, $option['email']);

		// Return success message
		$output = 'SUCCESS! OpenCart successfully installed on your server' . "\n";

		return $output;
	}

	/**
	 * @return string
	 */
	public function usage(): string {
		$option = implode(' ', [
			'--username',
			'admin',
			'--email',
			'email@example.com',
			'--password',
			'password'
		]);

		$output  = 'Usage:' . "\n";
		$output .= '======' . "\n\n";
		$output .= 'php cli_install.php install ' . $option . "\n\n";

		return $output;
	}
}

// Controller
$controller = new \Install\CliCloud($registry);
$controller->index();

// Output
$response->output();
