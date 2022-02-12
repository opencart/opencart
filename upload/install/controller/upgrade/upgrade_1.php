<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade1 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (isset($this->request->get['admin'])) {
			$admin = $this->request->get['admin'];
		} else {
			$admin = '';
		}

		// Extract
		try {

			if (is_file(DIR_OPENCART . 'config.php') && filesize(DIR_OPENCART . 'config.php') > 0) {
				$lines = file(DIR_OPENCART . 'config.php');

				foreach ($lines as $line) {
					if (strpos($line, 'DB_') !== false) {
						eval($line);
					}
				}
			}

			$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

			if (!is_file($file)) {
				$json['error'] = $this->language->get('error_file');
			}

			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file)) {
				$remove = 'opencart-' . $version . '/upload/';

				// Check if any of the files already exist.
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$source = $zip->getNameIndex($i);

					if (substr($source, 0, strlen($remove)) == $remove) {
						// Only extract the contents of the upload folder
						$destination = str_replace('\\', '/', substr($source, strlen($remove)));

						if (substr($destination, 0, 8) != 'install/') {
							// Default copy location
							$path = DIR_OPENCART . $destination;

							// Fixes admin folder being under a different name
							if (substr($destination, 0, 6) == 'admin/') {
								$path = DIR_APPLICATION . substr($destination, 6);
							}

							// We need to use a different path for vendor folders.
							if (substr($destination, 0, 15) == 'system/storage/') {
								$path = DIR_STORAGE . substr($destination, 15);
							}

							// Must not have a path before files and directories can be moved
							if (substr($path, -1) == '/') {
								if (!is_dir($path) && !mkdir($path, 0777)) {
									$json['error'] = sprintf($this->language->get('error_directory'), $path);
								}
							}

							// Check if the path is not directory and check there is no existing file
							if (substr($path, -1) != '/') {
								if (is_file($path)) {
									unlink($path);
								}

								if (!copy('zip://' . $file . '#' . $source, $path)) {
									$json['error'] = sprintf($this->language->get('error_copy'), $source, $path);
								}
							}
						}
					}
				}

				$zip->close();

				unlink($file);
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['success'] = sprintf($this->language->get('text_progress'), 1, 1, 8);

			$json['next'] = $this->url->link('upgrade/upgrade_2', '', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
