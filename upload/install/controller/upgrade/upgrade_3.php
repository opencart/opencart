<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade3
 *
 * Extract the contents of the upgrade zip file to the correct locations.
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade3 extends \Opencart\System\Engine\Controller {
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

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
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
			$json['error'] = $this->language->get('error_file');
		}

		$total = 0;
		$limit = 200;

		$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

		if (is_file($file)) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file, \ZipArchive::RDONLY)) {
				$total = $zip->numFiles;

				$start = ($page - 1) * $limit;
				$end = $start > ($total - $limit) ? $total : ($start + $limit);

				$remove = 'opencart-' . $version . '/upload/';

				// Check if any of the files already exist.
				for ($i = $start; $i < $end; $i++) {
					$source = $zip->getNameIndex($i);

					if (substr($source, 0, strlen($remove)) == $remove) {
						// Only extract the contents of the upload folder
						$destination = str_replace('\\', '/', substr($source, strlen($remove)));

						if (substr($destination, 0, 8) != 'install/') {
							// Default copy location
							$base = DIR_OPENCART;

							// Fixes admin folder being under a different name
							if (substr($destination, 0, 6) == 'admin/') {
								$destination = $admin . '/' . substr($destination, 6);
							}

							// We need to use a different path for vendor folders.
							if (substr($destination, 0, 15) == 'system/storage/' && isset($config['DIR_STORAGE'])) {
								$destination = substr($destination, 15);
								$base = $config['DIR_STORAGE'];
							}

							// Default copy location
							$path = '';

							// Must have a path before files can be moved
							$directories = explode('/', dirname($destination));

							foreach ($directories as $directory) {
								if (!$path) {
									$path = $directory;
								} else {
									$path = $path . '/' . $directory;
								}

								if (!is_dir($base . $path) && !@mkdir($base . $path, 0777)) {
									$json['error'] = sprintf($this->language->get('error_directory'), $path);
								}
							}

							// Check if the path is not directory, and check there is no existing file
							if (substr($destination, -1) != '/' && !is_dir($base . $destination)) {
								if (is_file($base . $destination)) {
									unlink($base . $destination);
								}

								if (file_put_contents($base . $destination, $zip->getFromIndex($i)) === false) {
									$json['error'] = sprintf($this->language->get('error_copy'), $source, $path);
								}
							}
						}
					}
				}

				$zip->close();
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 3, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			if (($page * $limit) <= $total) {
				$json['next'] = $this->url->link('upgrade/upgrade_3', $url . '&page=' . ($page + 1), true);
			} else {
				$json['next'] = $this->url->link('upgrade/upgrade_4', $url, true);

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
