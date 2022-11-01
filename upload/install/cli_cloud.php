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

set_error_handler(function($code, $message, $file, $line, array $errcontext) {
	// error was suppressed with the @-operator
	if (error_reporting() === 0) {
		return false;
	}

	throw new \ErrorException($message, 0, $code, $file, $line);
});

class CliCloud extends \Opencart\System\Engine\Controller {
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

		foreach ($tables as $table) {
			$table_query = $db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $db_database . "' AND TABLE_NAME = '" . $db_prefix . $table['name'] . "'");

			if ($table_query->num_rows) {
				$db->query("DROP TABLE `" . $db_prefix . $table['name'] . "`");
			}

			$sql = "CREATE TABLE `" . $db_prefix . $table['name'] . "` (" . "\n";

			foreach ($table['field'] as $field) {
				$sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . $db->escape($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
			}

			if (isset($table['primary'])) {
				$primary_data = [];

				foreach ($table['primary'] as $primary) {
					$primary_data[] = "`" . $primary . "`";
				}

				$sql .= "  PRIMARY KEY (" . implode(",", $primary_data) . "),\n";
			}

			if (isset($table['index'])) {
				foreach ($table['index'] as $index) {
					$index_data = [];

					foreach ($index['key'] as $key) {
						$index_data[] = "`" . $key . "`";
					}

					$sql .= "  KEY `" . $index['name'] . "` (" . implode(",", $index_data) . "),\n";
				}
			}

			$sql = rtrim($sql, ",\n") . "\n";
			$sql .= ") ENGINE=" . $table['engine'] . " CHARSET=" . $table['charset'] . " COLLATE=" . $table['collate'] . ";\n";

			$db->query($sql);
		}

		// Setup database data
		$lines = file($file, FILE_IGNORE_NEW_LINES);

		if ($lines) {
			$sql = '';

			$start = false;

			foreach ($lines as $line) {
				if (substr($line, 0, 12) == 'INSERT INTO ') {
					$sql = '';

					$start = true;
				}

				if ($start) {
					$sql .= $line;
				}

				if (substr($line, -2) == ');') {
					$db->query(str_replace("INSERT INTO `oc_", "INSERT INTO `" . $db_prefix, $sql));

					$start = false;
				}
			}

			$db->query("SET CHARACTER SET utf8");

			$db->query("SET @@session.sql_mode = ''");

			$db->query("DELETE FROM `" . $db_prefix . "user` WHERE `user_id` = '1'");

			// If cloud we do not need to hash the password as we will be passing the password hash
			$db->query("INSERT INTO `" . $db_prefix . "user` SET `user_id` = '1', `user_group_id` = '1', `username` = '" . $db->escape($option['username']) . "', `password` = '" . $db->escape($option['password']) . "', `firstname` = 'John', `lastname` = 'Doe', `email` = '" . $db->escape($option['email']) . "', `status` = '1', `date_added` = NOW()");

			$db->query("DELETE FROM `" . $db_prefix . "setting` WHERE `key` = 'config_email'");
			$db->query("INSERT INTO `" . $db_prefix . "setting` SET `code` = 'config', `key` = 'config_email', `value` = '" . $db->escape($option['email']) . "'");

			$db->query("DELETE FROM `" . $db_prefix . "setting` WHERE `key` = 'config_encryption'");
			$db->query("INSERT INTO `" . $db_prefix . "setting` SET `code` = 'config', `key` = 'config_encryption', `value` = '" . $db->escape(oc_token(1024)) . "'");

			$db->query("INSERT INTO `" . $db_prefix . "api` SET `username` = 'Default', `key` = '" . $db->escape(oc_token(256)) . "', `status` = 1, `date_added` = NOW(), `date_modified` = NOW()");

			$last_id = $db->getLastId();

			$db->query("DELETE FROM `" . $db_prefix . "setting` WHERE `key` = 'config_api_id'");
			$db->query("INSERT INTO `" . $db_prefix . "setting` SET `code` = 'config', `key` = 'config_api_id', `value` = '" . (int)$last_id . "'");

			// set the current years prefix
			$db->query("UPDATE `" . $db_prefix . "setting` SET `value` = 'INV-" . date('Y') . "-00' WHERE `key` = 'config_invoice_prefix'");
		}

		// Return success message
		$output = 'SUCCESS! OpenCart successfully installed on your server' . "\n";

		return $output;
	}

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
