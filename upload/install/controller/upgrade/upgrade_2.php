<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade2
 *
 * Create any missing directories and move files.
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade2 extends \Opencart\System\Engine\Controller {
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

		$file = DIR_OPENCART . 'config.php';

		if (is_file($file)) {
			$config = [];

			$lines = file($file);

			foreach ($lines as $number => $line) {
				if (preg_match('/define\(\'(.*)\',\s+\'(.*)\'\)/', $line, $match, PREG_OFFSET_CAPTURE)) {
					$config[$match[1][0]] = $match[2][0];
				}
			}
		} else {
			$json['error'] = sprintf($this->language->get('error_file'), $file);
		}

		if (!$json) {
			// Create any missing storage directories
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
					mkdir($storage . $directory, 0644);

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
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 2, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_3', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
