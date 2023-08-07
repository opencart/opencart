<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade1
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade1 extends \Opencart\System\Engine\Controller {
	/**
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
				$json['error'] =  sprintf($this->language->get('error_writable'), $file);
			}
		} else {
			$json['error'] = sprintf($this->language->get('error_file'), $file);
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
				} else {
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
			$output .= 'define(\'DB_HOSTNAME\', \'' .DB_HOSTNAME . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . DB_USERNAME . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . DB_PASSWORD . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . DB_DATABASE . '\');' . "\n";

			if (defined('DB_PORT')) {
				$output .= 'define(\'DB_PORT\', \'' . DB_PORT . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_PORT\', \'3306\');' . "\n";
			}

			$output .= 'define(\'DB_PREFIX\', \'' . DB_PREFIX . '\');' . "\n\n";

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
		} else {
			$json['error'] = sprintf($this->language->get('error_file'), $file);
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
			} else {
				$output .= 'define(\'HTTP_SERVER\', \'' . $config['HTTP_SERVER'] . '\');' . "\n";
			}

			if (!empty($config['HTTPS_CATALOG'])) {
				$output .= 'define(\'HTTP_CATALOG\', \'' . $config['HTTPS_CATALOG'] . '\');' . "\n\n";
			} else {
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
			$output .= 'define(\'DB_HOSTNAME\', \'' .DB_HOSTNAME . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . DB_USERNAME . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . DB_PASSWORD . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . DB_DATABASE . '\');' . "\n";

			if (defined('DB_PORT')) {
				$output .= 'define(\'DB_PORT\', \'' . DB_PORT . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_PORT\', \'3306\');' . "\n";
			}

			$output .= 'define(\'DB_PREFIX\', \'' . DB_PREFIX . '\');' . "\n\n";

			$output .= '// OpenCart API' . "\n";
			$output .= 'define(\'OPENCART_SERVER\', \'https://www.opencart.com/\');' . "\n";

			// Save file
			file_put_contents($file, $output);
		}

		// If create any missing storage directories
		$directories = [
			'backup',
			'cache',
			'download',
			'logs',
			'marketplace',
			'session',
			'upload'
		];

		if (isset($config['DIR_STORAGE'])) {
			$storage = $config['DIR_STORAGE'];
		} else {
			$storage = DIR_SYSTEM . 'storage/';
		}

		foreach ($directories as $directory) {
			if (!is_dir($storage . $directory)) {
				mkdir($storage . $directory, '0644');

				$handle = fopen($storage . $directory . '/index.html', 'w');

				fclose($handle);
			}
		}

		// Move files from old directories to new ones.
		$move = [
			DIR_IMAGE . 'data/'      => DIR_IMAGE . 'catalog/', // Merge image/data to image/catalog
			DIR_SYSTEM . 'upload/'   => $storage . 'upload/', // Merge system/upload to system/storage/upload
			DIR_SYSTEM . 'download/' => $storage . 'download/' // Merge system/download to system/storage/download
		];

		foreach ($move as $source => $destination) {
			$files = [];

			$directory = [$source];

			while (count($directory) != 0) {
				$next = array_shift($directory);

				foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$directory[] = $file;
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			foreach ($files as $file) {
				$path = substr($file, strlen($source));

				if (is_dir($source . $path) && !is_dir($destination . $path)) {
					mkdir($destination . $path, 0777);
				}

				if (is_file($source . $path) && !is_file($destination . $path)) {
					copy($source . $path, $destination . $path);
				}
			}

			// Start deleting old storage location files.
			rsort($files);

			foreach ($files as $file) {
				// If file just delete
				if (is_file($file)) {
					unlink($file);
				}

				// If directory use the remove directory function
				if (is_dir($file)) {
					rmdir($file);
				}
			}
		}

		// Remove files in old directories
		$remove = [
			DIR_SYSTEM . 'logs/',
			DIR_SYSTEM . 'cache/',
		];

		$files = [];

		foreach ($remove as $directory) {
			if (is_dir($directory)) {
				// Make path into an array
				$path = [$directory . '*'];

				// While the path array is still populated keep looping through
				while (count($path) != 0) {
					$next = array_shift($path);

					foreach (glob($next) as $file) {
						// If directory add to path array
						if (is_dir($file)) {
							$path[] = $file . '/*';
						}

						// Add the file to the files to be deleted array
						$files[] = $file;
					}

					// Reverse sort the file array
					rsort($files);

					// Clear all modification files
					foreach ($files as $file) {
						if ($file != $directory . 'index.html') {
							// If file just delete
							if (is_file($file)) {
								@unlink($file);

							}

							// If directory use the remove directory function
							if (is_dir($file)) {
								@rmdir($file);
							}
						}
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_progress'), 1, 1, 9);

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
