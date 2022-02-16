<?php
namespace Opencart\Install\Controller\Startup;
class Database extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if (is_file(DIR_OPENCART . 'config.php') && filesize(DIR_OPENCART . 'config.php') > 0) {
			$config = [];

			$lines = file(DIR_OPENCART . 'config.php');

			foreach ($lines as $number => $line) {
				if (strpos(strtoupper($line), 'DB_') !== false && preg_match('/define\(\'(.*)\',\s+\'(.*)\'\)/', $line, $match, PREG_OFFSET_CAPTURE) && isset($match[2][0])) {
					$config[$match[1][0]] = $match[2][0];
				}
			}

			if (isset($config['DB_PORT'])) {
				$port = (int)$config['DB_PORT'];
			} else {
				$port = ini_get('mysqli.default_port');
			}
			
			$this->registry->set('db', new \Opencart\System\Library\DB($config['DB_DRIVER'], $config['DB_HOSTNAME'], $config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE'], $port));
		}
	}
}
