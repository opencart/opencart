<?php
//
// Command line tool for installing opencart
// Author: Vineet Naik <vineet.naik@kodeplay.com> <naikvin@gmail.com>
//
// (Currently tested on linux only)
//
// Usage:
//
//   cd install
//   php cli_install.php install --db_hostname localhost \
//                               --db_username root \
//                               --db_password pass \
//                               --db_database opencart \
//                               --db_driver mysqli \
//								 --db_port 3306 \
//                               --username admin \
//                               --password admin \
//                               --email youremail@example.com \
//                               --http_server http://localhost/opencart/
//
// Windows
// php.exe c:\xampp/htdocs/opencart-master/upload/install/cli_install.php install
//                               --db_hostname localhost \
//                               --db_username root \
//                               --db_password pass \
//                               --db_database opencart \
//                               --db_driver mysqli \
//								 --db_port 3306 \
//                               --username admin \
//                               --password admin \
//                               --email youremail@example.com \
//                               --http_server http://localhost/opencart/

ini_set('display_errors', 1);

error_reporting(E_ALL);

// DIR
define('DIR_APPLICATION', str_replace('\\', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SYSTEM', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . '/system/');
define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
define('DIR_OPENCART', str_replace('\\', '/', realpath(DIR_APPLICATION . '../')) . '/');
define('DIR_DATABASE', DIR_SYSTEM . 'database/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_SYSTEM . 'storage/cache/');
define('DIR_LOGS', DIR_SYSTEM . 'storage/logs/');
define('DIR_MODIFICATION', DIR_SYSTEM . 'storage/modification/');
define('DIR_DOWNLOAD', DIR_SYSTEM . 'storage/download/');
define('DIR_SESSION', DIR_SYSTEM . 'storage/session/');
define('DIR_UPLOAD', DIR_SYSTEM . 'storage/upload/');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$registry->set('request', new Request());

// Response
$response = new Response();
$response->addHeader('Content-Type: text/plain; charset=utf-8');
$registry->set('response', $response);

set_error_handler(function($code, $message, $file, $line, array $errcontext) {
	// error was suppressed with the @-operator
	if (error_reporting() === 0) {
		return false;
	}

	throw new ErrorException($message, 0, $code, $file, $line);
});

class ControllerCliInstall extends Controller {
	public function index() {
		$argv = $this->request->server['argv'];

		$script = array_shift($argv);

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

	public function install($argv) {
		// Options
		$option = array(
			'db_hostname' => 'localhost',
			'db_database' => 'opencart',
			'db_prefix'   => 'oc_',
			'db_driver'   => 'mysqli',
			'db_port'     => '3306',
			'username'    => 'admin'
		);

		$total = count($argv);

		for ($i = 0; $i < $total; $i = $i + 2) {
			$is_flag = preg_match('/^--(.*)$/', $argv[$i], $match);

			if (!$is_flag) {
				return $argv[$i] . ' found in command line args instead of a valid option name starting with \'--\'';
			}

			$option[$match[1]] = $argv[$i + 1];
		}

		// Validation
		$required = array(
			'db_hostname',
			'db_username',
			'db_password',
			'db_database',
			'db_prefix',
			'db_port',
			'username',
			'password',
			'email',
			'http_server',
		);

		$missing = array();

		foreach ($required as $value) {
			if (!array_key_exists($value, $option)) {
				$missing[] = $value;
			}
		}

		if (count($missing)) {
			return 'FAILED! Following inputs were missing or invalid: ' . implode(', ', $missing) . "\n\n";
		}

		// Requirements
		$error = '';

		if (phpversion() < '5.4') {
			$error = 'Warning: You need to use PHP 5.4+ or above for OpenCart to work!';
		}

		if (!ini_get('file_uploads')) {
			$error = 'Warning: file_uploads needs to be enabled!';
		}

		if (ini_get('session.auto_start')) {
			$error = 'Warning: OpenCart will not work with session.auto_start enabled!';
		}

		if (!extension_loaded('mysqli')) {
			$error = 'Warning: MySQLi extension needs to be loaded for OpenCart to work!';
		}

		if (!extension_loaded('gd')) {
			$error = 'Warning: GD extension needs to be loaded for OpenCart to work!';
		}

		if (!extension_loaded('curl')) {
			$error = 'Warning: CURL extension needs to be loaded for OpenCart to work!';
		}

		if (!function_exists('openssl_encrypt')) {
			$error = 'Warning: OpenSSL extension needs to be loaded for OpenCart to work!';
		}

		if (!extension_loaded('zlib')) {
			$error = 'Warning: ZLIB extension needs to be loaded for OpenCart to work!';
		}

		if ($error) {
			$output  = 'FAILED! Pre-installation check failed: ' . "\n\n";
			$output .= $error . "\n\n";

			return $output;
		}

		// Make sure the directories are writable
		$directories = array(
			DIR_OPENCART . 'image/',
			DIR_OPENCART . 'system/storage/cache/',
			DIR_OPENCART . 'system/storage/download/',
			DIR_OPENCART . 'system/storage/logs/',
			DIR_OPENCART . 'system/storage/modification/',
			DIR_OPENCART . 'system/storage/session/',
			DIR_OPENCART . 'system/storage/upload/'
		);

		exec('chmod o+w -R ' . implode(' ', $directories));



		try {
			// Database
			$db = new DB($data['db_driver'], htmlspecialchars_decode($data['db_hostname']), htmlspecialchars_decode($data['db_username']), htmlspecialchars_decode($data['db_password']), htmlspecialchars_decode($data['db_database']), $data['db_port']);

			$file = DIR_APPLICATION . 'opencart.sql';

			if (!is_file($file)) {
				return 'Could not load sql file: ' . $file;
			}

			// Set up Database structure
			$this->load->helper('db_schema');

			$tables = db_schema();

			foreach ($tables as $table) {
				$table_query = $db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $data['db_database'] . "' AND TABLE_NAME = '" . $data['db_prefix'] . $table['name'] . "'");

				if ($table_query->num_rows) {
					$db->query("DROP TABLE `" . $data['db_prefix'] . $table['name'] . "`");
				}

				$sql = "CREATE TABLE `" . $data['db_prefix'] . $table['name'] . "` (" . "\n";

				foreach ($table['field'] as $field) {
					$sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . $db->escape($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
				}

				if (isset($table['primary'])) {
					$primary_data = array();

					foreach ($table['primary'] as $primary) {
						$primary_data[] = "`" . $primary . "`";
					}

					$sql .= "  PRIMARY KEY (" . implode(",", $primary_data) . "),\n";
				}

				if (isset($table['index'])) {
					foreach ($table['index'] as $index) {
						$index_data = array();

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
						$db->query(str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $sql));

						$start = false;
					}
				}

				$db->query("SET CHARACTER SET utf8");

				$db->query("SET @@session.sql_mode = 'MYSQL40'");

				$db->query("DELETE FROM `" . $data['db_prefix'] . "user` WHERE user_id = '1'");

				$db->query("INSERT INTO `" . $data['db_prefix'] . "user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($data['username']) . "', salt = '', password = '" . $db->escape(password_hash($data['password'], PASSWORD_DEFAULT)) . "', firstname = 'John', lastname = 'Doe', email = '" . $db->escape($data['email']) . "', status = '1', date_added = NOW()");

				$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_email'");
				$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_email', value = '" . $db->escape($data['email']) . "'");

				$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_encryption'");
				$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_encryption', value = '" . $db->escape(token(1024)) . "'");

				$db->query("UPDATE `" . $data['db_prefix'] . "product` SET `viewed` = '0'");

				$db->query("INSERT INTO `" . $data['db_prefix'] . "api` SET username = 'Default', `key` = '" . $db->escape(token(256)) . "', status = 1, date_added = NOW(), date_modified = NOW()");

				$api_id = $db->getLastId();

				$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_api_id'");
				$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_api_id', value = '" . (int)$api_id . "'");

				// set the current years prefix
				$db->query("UPDATE `" . $data['db_prefix'] . "setting` SET `value` = 'INV-" . date('Y') . "-00' WHERE `key` = 'config_invoice_prefix'");
			}

		} catch (ErrorException $e) {
			return 'FAILED!: ' . $e->getMessage() . "\n";
		}

		// Write config files
		$output = '<?php' . "\n";
		$output .= '// HTTP' . "\n";
		$output .= 'define(\'HTTP_SERVER\', \'' . $option['http_server'] . '\');' . "\n";

		$output .= '// HTTPS' . "\n";
		$output .= 'define(\'HTTPS_SERVER\', \'' . $option['http_server'] . '\');' . "\n";

		$output .= '// DIR' . "\n";
		$output .= 'define(\'DIR_APPLICATION\', \'' . addslashes(DIR_OPENCART) . 'catalog/\');' . "\n";
		$output .= 'define(\'DIR_SYSTEM\', \'' . addslashes(DIR_OPENCART) . 'system/\');' . "\n";
		$output .= 'define(\'DIR_IMAGE\', \'' . addslashes(DIR_OPENCART) . 'image/\');' . "\n";
		$output .= 'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');' . "\n";
		$output .= 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
		$output .= 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/theme/\');' . "\n";
		$output .= 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
		$output .= 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
		$output .= 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
		$output .= 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
		$output .= 'define(\'DIR_MODIFICATION\', DIR_STORAGE . \'modification/\');' . "\n";
		$output .= 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
		$output .= 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n\n";

		$output .= '// DB' . "\n";
		$output .= 'define(\'DB_DRIVER\', \'' . addslashes($option['db_driver']) . '\');' . "\n";
		$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($option['db_hostname']) . '\');' . "\n";
		$output .= 'define(\'DB_USERNAME\', \'' . addslashes($options['db_username']) . '\');' . "\n";
		$output .= 'define(\'DB_PASSWORD\', \'' . addslashes($options['db_password']) . '\');' . "\n";
		$output .= 'define(\'DB_DATABASE\', \'' . addslashes($options['db_database']) . '\');' . "\n";
		$output .= 'define(\'DB_PREFIX\', \'' . addslashes($options['db_prefix']) . '\');' . "\n";
		$output .= 'define(\'DB_PORT\', \'' . addslashes($options['db_port']) . '\');' . "\n";

		$file = fopen(DIR_OPENCART . 'config.php', 'w');

		fwrite($file, $output);

		fclose($file);

		$output = '<?php' . "\n";
		$output .= '// HTTP' . "\n";
		$output .= 'define(\'HTTP_SERVER\', \'' . $option['http_server'] . 'admin/\');' . "\n";
		$output .= 'define(\'HTTP_CATALOG\', \'' . $option['http_server'] . '\');' . "\n";

		$output .= '// HTTPS' . "\n";
		$output .= 'define(\'HTTPS_SERVER\', \'' . $option['http_server'] . 'admin/\');' . "\n";
		$output .= 'define(\'HTTPS_CATALOG\', \'' . $option['http_server'] . '\');' . "\n";

		$output .= '// DIR' . "\n";
		$output .= 'define(\'DIR_APPLICATION\', \'' . addslashes(DIR_OPENCART) . 'admin/\');' . "\n";
		$output .= 'define(\'DIR_SYSTEM\', \'' . addslashes(DIR_OPENCART) . 'system/\');' . "\n";
		$output .= 'define(\'DIR_IMAGE\', \'' . addslashes(DIR_OPENCART) . 'image/\');' . "\n";
		$output .= 'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');' . "\n";
		$output .= 'define(\'DIR_CATALOG\', \'' . addslashes(DIR_OPENCART) . 'catalog/\');' . "\n";
		$output .= 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
		$output .= 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/template/\');' . "\n";
		$output .= 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
		$output .= 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
		$output .= 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
		$output .= 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
		$output .= 'define(\'DIR_MODIFICATION\', DIR_STORAGE . \'modification/\');' . "\n";
		$output .= 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
		$output .= 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n\n";

		$output .= '// DB' . "\n";
		$output .= 'define(\'DB_DRIVER\', \'' . addslashes($options['db_driver']) . '\');' . "\n";
		$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($options['db_hostname']) . '\');' . "\n";
		$output .= 'define(\'DB_USERNAME\', \'' . addslashes($options['db_username']) . '\');' . "\n";
		$output .= 'define(\'DB_PASSWORD\', \'' . addslashes($options['db_password']) . '\');' . "\n";
		$output .= 'define(\'DB_DATABASE\', \'' . addslashes($options['db_database']) . '\');' . "\n";
		$output .= 'define(\'DB_PREFIX\', \'' . addslashes($options['db_prefix']) . '\');' . "\n";
		$output .= 'define(\'DB_PORT\', \'' . addslashes($options['db_port']) . '\');' . "\n";

		$output .= '// OpenCart API' . "\n";
		$output .= 'define(\'OPENCART_SERVER\', \'https://www.opencart.com/\');' . "\n";

		$file = fopen(DIR_OPENCART . 'admin/config.php', 'w');

		fwrite($file, $output);

		fclose($file);

		// Return success message
		$output  = 'SUCCESS! OpenCart successfully installed on your server' . "\n";
		$output .= 'Store link: ' . $option['http_server'] . "\n";
		$output .= 'Admin link: ' . $option['http_server'] . 'admin/' . "\n\n";

		return $output;
	}

	public function usage() {
		$option = implode(' ', array(
			'--db_hostname',
			'localhost',
			'--db_username',
			'root',
			'--db_password',
			'pass',
			'--db_database',
			'opencart',
			'--db_driver',
			'mysqli',
			'--db_port',
			'3306',
			'--username',
			'admin',
			'--password',
			'admin',
			'--email',
			'youremail@example.com',
			'--http_server',
			'http://localhost/opencart/'
		));

		$output  = 'Usage:' . "\n";
		$output .= '======' . "\n\n";
		$output .= 'php cli_install.php install ' . $option . "\n\n";

		return $output;
	}
}

$controller = new ControllerCliInstall($registry);
$controller->index();

// Output
$response->output();