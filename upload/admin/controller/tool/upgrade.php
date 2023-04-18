<?php
/*
Upgrade Process

1. Check for latest version

2. Download a copy of the latest version

3. Add and replace the files with the ones from latest version

4. Redirect to upgrade page
*/
namespace Opencart\Admin\Controller\Tool;
class Upgrade extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('tool/upgrade');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/upgrade', 'user_token=' . $this->session->data['user_token'])
		];

		$data['current_version'] = VERSION;
		$data['upgrade'] = false;

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/upgrade');

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

		$response = curl_exec($curl);

		curl_close($curl);

		$response_info = json_decode($response, true);

		if ($response_info) {
			$data['latest_version'] = $response_info['version'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($response_info['date_added']));
			$data['log'] = nl2br($response_info['log']);

			if (!version_compare(VERSION, $response_info['version'], '>=')) {
				$data['upgrade'] = true;
			}
		} else {
			$data['latest_version'] = '';
			$data['date_added'] = '';
			$data['log'] = '';
		}

		// For testing
		//$data['latest_version'] = 'master';

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upgrade', $data));
	}

	public function download(): void {
		$this->load->language('tool/upgrade');

		$json = [];

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (version_compare($version, VERSION, '>=')) {
			$json['error'] = $this->language->get('error_version');
		}

		$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

		$handle = fopen($file, 'w');

		set_time_limit(0);

		$curl = curl_init('https://github.com/opencart/opencart/archive/' . $version . '.zip');

		curl_setopt($curl, CURLOPT_USERAGENT, 'OpenCart ' . VERSION);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 300);
		curl_setopt($curl, CURLOPT_FILE, $handle);

		curl_exec($curl);

		fclose($handle);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($status != 200) {
			$json['error'] = $this->language->get('error_download');
		}

		curl_close($curl);

		if (!$json) {
			$json['text'] = $this->language->get('text_install');

			$json['next'] = $this->url->link('tool/upgrade.install', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void {
		$this->load->language('tool/upgrade');

		$json = [];

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

		if (!is_file($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file, \ZipArchive::RDONLY)) {
				$remove = 'opencart-' . $version . '/upload/';

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
									$json['error'] = sprintf($this->language->get('error_directory'), $path);
								}
							}

							// Check if the path is not directory and check there is no existing file
							if (substr($destination, -1) != '/') {
								if (is_file(DIR_OPENCART . $destination)) {
									unlink(DIR_OPENCART . $destination);
								}

								if (!copy('zip://' . $file . '#' . $source, DIR_OPENCART . $destination)) {
									$json['error'] = sprintf($this->language->get('error_copy'), $source, $destination);
								}
							}
						}
					}
				}

				$zip->close();

				$json['text'] = $this->language->get('text_patch');

				$json['next'] = HTTP_CATALOG . 'install/index.php?route=upgrade/upgrade_1&version=' . $version . '&admin=' . rtrim(substr(DIR_APPLICATION, strlen(DIR_OPENCART), -1));
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
