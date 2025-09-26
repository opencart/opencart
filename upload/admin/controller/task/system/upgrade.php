<?php
namespace Opencart\Admin\Controller\Task\System;
/**
 * Class Upgrade
 *
 * 1. Check for latest version
 * 2. Download a copy of the latest version
 * 3. Add and replace the files with the ones from latest version
 * 4. Redirect to upgrade page
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Upgrade extends \Opencart\System\Engine\Controller {
	/**
	 * Install
	 *
	 * @return array
	 */
	public function install(array $args = []): array {
		$this->load->language('task/system/upgrade');

		if (!array_key_exists('version', $args)) {
			return ['error' => sprintf($this->language->get('error_version'), 'version')];
		}

		if (!isset($args['version']) || version_compare($args['version'], VERSION, '<') || !preg_match('/^(\d+\.\d+\.\d+\.\d+)$/', $args['version'])) {
			return ['error' => $this->language->get('error_version')];
		}

		$file = DIR_DOWNLOAD . 'opencart-' . $args['version'] . '.zip';

		if (!is_file($file)) {
			return ['error' => $this->language->get('error_file')];
		}

		// Unzip the files
		$zip = new \ZipArchive();

		if (!$zip->open($file, \ZipArchive::RDONLY)) {
			return ['error' => $this->language->get('error_unzip')];
		}

		$remove = 'opencart-' . $args['version'] . '/upload/';

		// Check if any of the files already exist.
		for ($i = 0; $i < $zip->numFiles; $i++) {
			$source = $zip->getNameIndex($i);

			if (substr($source, 0, strlen($remove)) == $remove) {
				// Only extract the contents of the upload folder
				$destination = str_replace('\\', '/', substr($source, strlen($remove)));

				if (substr($destination, 0, 8) == 'install/') {
					// Default copy location
					$path = '';

					// Must not have a path before files and directories can be moved
					$directories = explode('/', dirname($destination));

					foreach ($directories as $directory) {
						if (!$path) {
							$path = $directory;
						} else {
							$path = $path . '/' . $directory;
						}

						if (!is_dir(DIR_OPENCART . $path) && !@mkdir(DIR_OPENCART . $path, 0777)) {
							return ['error' => sprintf($this->language->get('error_directory'), $path)];
						}
					}

					// Check if the path is not directory and check there is no existing file
					if (substr($destination, -1) != '/') {
						if (is_file(DIR_OPENCART . $destination)) {
							unlink(DIR_OPENCART . $destination);
						}

						if (file_put_contents(DIR_OPENCART . $destination, $zip->getFromIndex($i)) === false) {
							return ['error' => sprintf($this->language->get('error_copy'), $source, $destination)];
						}
					}
				}
			}
		}

		$zip->close();



		$json['next'] = HTTP_CATALOG . 'install/index.php?route=upgrade/upgrade_1&version=' . $version . '&admin=' . rtrim(substr(DIR_APPLICATION, strlen(DIR_OPENCART), -1));



		return ['success' => $this->language->get('text_success')];
	}
}
