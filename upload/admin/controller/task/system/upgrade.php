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
	 * Index
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/system/upgrade');

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/upgrade');

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

		$response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		if ($status == 200) {
			$response_info = json_decode($response, true);
		} else {
			$response_info = [];
		}

		if ($response_info && !version_compare(VERSION, $response_info['version'], '>=')) {
			$task_data = [
				'code'   => 'upgrade',
				'action' => 'task/system/upgrade.download',
				'args'   => ['version' => $response_info['version']]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * Download
	 *
	 * @return array
	 */
	public function download(array $args = []): array {
		$this->load->language('task/system/upgrade');

		if (!isset($args['version']) || version_compare($args['version'], VERSION, '<') || !preg_match('/^(\d+\.\d+\.\d+\.\d+)$/', $args['version'])) {
			return ['error' => $this->language->get('error_version')];
		}

		$file = DIR_DOWNLOAD . 'opencart-' . $args['version'] . '.zip';

		$handle = fopen($file, 'w');

		set_time_limit(0);

		$curl = curl_init('https://github.com/opencart/opencart/archive/' . $args['version'] . '.zip');

		curl_setopt($curl, CURLOPT_USERAGENT, 'OpenCart ' . VERSION);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 300);
		curl_setopt($curl, CURLOPT_FILE, $handle);

		curl_exec($curl);

		fclose($handle);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($status != 200) {
			return ['error' => $this->language->get('error_download')];
		}

		curl_close($curl);

		$task_data = [
			'code'   => 'upgrade',
			'action' => 'task/system/upgrade.install',
			'args'   => ['version' => $args['version']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_install')];
	}

	/**
	 * Install
	 *
	 * @return array
	 */
	public function install(array $args = []): array {
		$this->load->language('task/system/upgrade');

		if (!array_key_exists('version', $args)) {
			return ['error' => $this->language->get('error_version', 'version')];
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
							return ['error' => $this->language->get('error_directory', $path)];
						}
					}

					// Check if the path is not directory and check there is no existing file
					if (substr($destination, -1) != '/') {
						if (is_file(DIR_OPENCART . $destination)) {
							unlink(DIR_OPENCART . $destination);
						}

						if (file_put_contents(DIR_OPENCART . $destination, $zip->getFromIndex($i)) === false) {
							return ['error' => $this->language->get('error_copy', $source, $destination)];
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
