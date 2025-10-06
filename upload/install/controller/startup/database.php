<?php
namespace Opencart\Install\Controller\Startup;
/**
 * Class Database
 *
 * @package Opencart\Install\Controller\Startup
 */
class Database extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function index(): void {
		if (is_file(DIR_OPENCART . 'config.php') && filesize(DIR_OPENCART . 'config.php') > 0) {
			$config = [];

			$lines = file(DIR_OPENCART . 'config.php');

			foreach ($lines as $number => $line) {
				if (strpos(strtoupper($line), 'DB_') !== false && preg_match('/define\(\'(.*)\',\s+["\'](.*)["\']\)/', $line, $match, PREG_OFFSET_CAPTURE)) {
					define($match[1][0], $match[2][0]);
				}
			}

			if (defined('DB_SSL_KEY')) {
				$db_ssl_key = DB_SSL_KEY;
			} else {
				$db_ssl_key = '';
			}

			if (defined('DB_SSL_CERT')) {
				$db_ssl_cert = DB_SSL_CERT;
			} else {
				$db_ssl_cert = '';
			}

			if (defined('DB_SSL_CA')) {
				$db_ssl_ca = DB_SSL_CA;
			} else {
				$db_ssl_ca = '';
			}

			if (defined('DB_PORT')) {
				$port = DB_PORT;
			} else {
				$port = ini_get('mysqli.default_port');
			}

			$option = [
				'engine'   => DB_DRIVER, // mysqli, pdo or pgsql
				'hostname' => DB_HOSTNAME,
				'username' => DB_USERNAME,
				'password' => DB_PASSWORD,
				'database' => DB_DATABASE,
				'port'     => DB_PORT,
				'ssl_key'  => $db_ssl_key,
				'ssl_cert' => $db_ssl_cert,
				'ssl_ca'   => $db_ssl_ca
			];

			$this->registry->set('db', new \Opencart\System\Library\DB($option));
		}
	}
}
