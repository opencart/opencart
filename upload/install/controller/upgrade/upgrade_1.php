<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade1
 *
 * config.php changes.
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade1 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (isset($this->request->get['admin'])) {
			$admin = basename($this->request->get['admin']);
		} else {
			$admin = 'admin';
		}

		// Config and file structure changes
		$file = DIR_OPENCART . 'config.php';

		if (is_file($file)) {
			$config = [];

			// Catalog
			$lines = file($file);

			// Capture values
			foreach ($lines as $number => $line) {
				if (preg_match('/define\(\'(.*)\',\s+\'(.*)\'\)/', $line, $match, PREG_OFFSET_CAPTURE)) {
					$config[$match[1][0]] = $match[2][0];
				}
			}
		} else {
			$json['error'] = sprintf($this->language->get('error_file'), $file);
		}

		if (!$json) {
			if (!isset($config['HTTP_SERVER'])) {
				$json['error'] = $this->language->get('error_server');
			}

			if (!defined('DB_DRIVER')) {
				$json['error'] = $this->language->get('error_db_driver');
			}

			if (!defined('DB_HOSTNAME')) {
				$json['error'] = $this->language->get('error_db_hostname');
			}

			if (!defined('DB_USERNAME')) {
				$json['error'] = $this->language->get('error_db_username');
			}

			if (!defined('DB_PASSWORD')) {
				$json['error'] = $this->language->get('error_db_password');
			}

			if (!defined('DB_DATABASE')) {
				$json['error'] = $this->language->get('error_db_database');
			}

			if (!defined('DB_PREFIX')) {
				$json['error'] = $this->language->get('error_db_prefix');
			}

			if (!is_writable($file)) {
				$json['error'] = sprintf($this->language->get('error_writable'), $file);
			}
		}

		if (!$json) {
			// Catalog config.php
			$output  = '<?php' . "\n";
			$output .= '// APPLICATION' . "\n";
			$output .= 'define(\'APPLICATION\', \'Catalog\');' . "\n\n";

			$output .= '// HTTP' . "\n";

			if (!empty($config['HOST_NAME'])) {
				$output .= 'define(\'HOST_NAME\', getenv(\'HOST_NAME\', true));' . "\n";
				$output .= 'define(\'HTTP_SERVER\', HOST_NAME . \'/\');' . "\n\n";
			} else {
				if (!empty($config['HTTPS_SERVER'])) {
					$output .= 'define(\'HTTP_SERVER\', \'' . $config['HTTPS_SERVER'] . '\');' . "\n\n";
				} elseif (!empty($config['HTTP_SERVER'])) {
					$output .= 'define(\'HTTP_SERVER\', \'' . $config['HTTP_SERVER'] . '\');' . "\n\n";
				}
			}

			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_OPENCART\', \'' . DIR_OPENCART . '\');' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'catalog/\');' . "\n";
			$output .= 'define(\'DIR_EXTENSION\', DIR_OPENCART . \'extension/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', DIR_OPENCART . \'image/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', DIR_OPENCART . \'system/\');' . "\n";

			if (isset($config['DIR_STORAGE'])) {
				$output .= 'define(\'DIR_STORAGE\', \'' . $config['DIR_STORAGE'] . '\');' . "\n";
			} else {
				$output .= 'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');' . "\n";
			}

			$output .= 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
			$output .= 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . DB_DRIVER . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . DB_HOSTNAME . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . DB_USERNAME . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . DB_PASSWORD . '\');' . "\n";

			$output .= 'define(\'DB_DATABASE\', \'' . DB_DATABASE . '\');' . "\n";

			if (defined('DB_PORT')) {
				$output .= 'define(\'DB_PORT\', \'' . DB_PORT . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_PORT\', \'3306\');' . "\n";
			}

			$output .= 'define(\'DB_PREFIX\', \'' . DB_PREFIX . '\');' . "\n";

			if (defined('DB_SSL_KEY')) {
				$output .= 'define(\'DB_SSL_KEY\', \'' . DB_SSL_KEY . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_KEY\', \'\');' . "\n";
			}

			if (defined('DB_SSL_CERT')) {
				$output .= 'define(\'DB_SSL_CERT\', \'' . DB_SSL_CERT . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_CERT\', \'\');' . "\n";
			}

			if (defined('DB_SSL_CA')) {
				$output .= 'define(\'DB_SSL_CA\', \'' . DB_SSL_CA . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_CA\', \'\');' . "\n";
			}

			// Save file
			file_put_contents($file, $output);
		}

		//*************************************

		// Admin
		$file = DIR_OPENCART . $admin . '/config.php';

		if (is_file($file)) {
			$config = [];

			$lines = file($file);

			// Capture values
			foreach ($lines as $number => $line) {
				if (preg_match('/define\(\'(.*)\',\s+\'(.*)\'\)/', $line, $match, PREG_OFFSET_CAPTURE)) {
					$config[$match[1][0]] = $match[2][0];
				}
			}
		} else {
			$json['error'] = sprintf($this->language->get('error_file'), $file);
		}

		if (!$json) {
			if (!isset($config['HTTP_SERVER'])) {
				$json['error'] = $this->language->get('error_server');
			}

			if (!isset($config['HTTP_CATALOG'])) {
				$json['error'] = $this->language->get('error_catalog');
			}

			if (!defined('DB_DRIVER')) {
				$json['error'] = $this->language->get('error_db_driver');
			}

			if (!defined('DB_HOSTNAME')) {
				$json['error'] = $this->language->get('error_db_hostname');
			}

			if (!defined('DB_USERNAME')) {
				$json['error'] = $this->language->get('error_db_username');
			}

			if (!defined('DB_PASSWORD')) {
				$json['error'] = $this->language->get('error_db_password');
			}

			if (!defined('DB_DATABASE')) {
				$json['error'] = $this->language->get('error_db_database');
			}

			if (!defined('DB_PREFIX')) {
				$json['error'] = $this->language->get('error_db_prefix');
			}

			if (!is_writable($file)) {
				$json['error'] = sprintf($this->language->get('error_writable'), $file);
			}
		}

		if (!$json) {
			$path_old = DIR_OPENCART . 'admin/';
			$path_new = dirname($file) . '/';

			// 1. Check if default admin directory exists
			if (is_dir($path_old) && $path_old != $path_new) {
				// 2. Move current config file to default admin directory.
				rename($file, $path_old . 'config.php');

				// 3. Remove the current directory
				$files = [];

				// Make path into an array
				$directory = [$path_new];

				// While the path array is still populated keep looping through
				while (count($directory) != 0) {
					$next = array_shift($directory);

					if (is_dir($next)) {
						foreach (glob(trim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $delete) {
							// If directory add to path array
							$directory[] = $delete;
						}
					}

					// Add the file to the files to be deleted array
					$files[] = $next;
				}

				// Reverse sort the file array
				rsort($files);

				foreach ($files as $delete) {
					// If file just delete
					if (is_file($delete)) {
						unlink($delete);
					}

					// If directory use the remove directory function
					if (is_dir($delete)) {
						rmdir($delete);
					}
				}

				// 4. Rename folder to the old directory
				rename(DIR_OPENCART . 'admin/', $path_new);
			}

			// Admin config.php
			$output  = '<?php' . "\n";
			$output .= '// APPLICATION' . "\n";
			$output .= 'define(\'APPLICATION\', \'Admin\');' . "\n\n";

			$output .= '// HTTP' . "\n";

			if (!empty($config['HTTPS_SERVER'])) {
				$output .= 'define(\'HTTP_SERVER\', \'' . $config['HTTPS_SERVER'] . '\');' . "\n";
			} elseif (!empty($config['HTTP_SERVER'])) {
				$output .= 'define(\'HTTP_SERVER\', \'' . $config['HTTP_SERVER'] . '\');' . "\n";
			}

			if (!empty($config['HTTPS_CATALOG'])) {
				$output .= 'define(\'HTTP_CATALOG\', \'' . $config['HTTPS_CATALOG'] . '\');' . "\n\n";
			} elseif (!empty($config['HTTP_CATALOG'])) {
				$output .= 'define(\'HTTP_CATALOG\', \'' . $config['HTTP_CATALOG'] . '\');' . "\n\n";
			}

			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_OPENCART\', \'' . DIR_OPENCART . '\');' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'' . $admin . '/\');' . "\n";
			$output .= 'define(\'DIR_EXTENSION\', DIR_OPENCART . \'extension/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', DIR_OPENCART . \'image/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', DIR_OPENCART . \'system/\');' . "\n";
			$output .= 'define(\'DIR_CATALOG\', DIR_OPENCART . \'catalog/\');' . "\n";

			if (isset($config['DIR_STORAGE'])) {
				$output .= 'define(\'DIR_STORAGE\', \'' . $config['DIR_STORAGE'] . '\');' . "\n";
			} else {
				$output .= 'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');' . "\n";
			}

			$output .= 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
			$output .= 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . DB_DRIVER . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . DB_HOSTNAME . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . DB_USERNAME . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . DB_PASSWORD . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . DB_DATABASE . '\');' . "\n";

			if (defined('DB_PORT')) {
				$output .= 'define(\'DB_PORT\', \'' . DB_PORT . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_PORT\', \'3306\');' . "\n";
			}

			$output .= 'define(\'DB_PREFIX\', \'' . DB_PREFIX . '\');' . "\n";

			if (defined('DB_SSL_KEY')) {
				$output .= 'define(\'DB_SSL_KEY\', \'' . DB_SSL_KEY . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_KEY\', \'\');' . "\n";
			}

			if (defined('DB_SSL_CERT')) {
				$output .= 'define(\'DB_SSL_CERT\', \'' . DB_SSL_CERT . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_CERT\', \'\');' . "\n";
			}

			if (defined('DB_SSL_CA')) {
				$output .= 'define(\'DB_SSL_CA\', \'' . DB_SSL_CA . '\');' . "\n\n";
			} else {
				$output .= 'define(\'DB_SSL_CA\', \'\');' . "\n\n";
			}

			$output .= '// OpenCart API' . "\n";
			$output .= 'define(\'OPENCART_SERVER\', \'https://www.opencart.com/\');' . "\n";

			// Save file
			file_put_contents($file, $output);
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 1, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_2', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
