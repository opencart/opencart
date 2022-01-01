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

		$data['version'] = VERSION;
		$data['upgrade'] = false;

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/upgrade');

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

		$response = curl_exec($curl);

		curl_close($curl);

		$response_info = json_decode($response, true);

		// Extension compatibility check
		if ($response_info) {
			if (version_compare(VERSION, $response_info['version'], '>=')) {
				$data['success'] = sprintf($this->language->get('text_success'), $response_info['version']);
			} else {
				$data['version'] = $response_info['version'];
				$data['log'] = $response_info['log'];

				$data['upgrade'] = true;

				$data['error_warning'] = sprintf($this->language->get('error_version'), $response_info['version']);
			}
		} else {
			$data['error_warning'] = $this->language->get('error_connection');
		}

		$data['backup'] = $this->url->link('tool/backup', 'user_token=' . $this->session->data['user_token']);

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

		if (!$json) {
			set_time_limit(0);

			$handle = fopen(DIR_DOWNLOAD . 'opencart-' . $version . '.zip', 'w');

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

			curl_close($curl);

			if ($status == 200) {
				$json['text'] = $this->language->get('text_unzip');

				$json['next'] = $this->url->link('tool/upgrade|install', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version, true);
			} else {
				$json['error'] = $this->language->get('error_download');
			}
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

			if ($zip->open($file)) {
				// Check if any of the files already exist.
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$source = $zip->getNameIndex($i);

					$remove = 'opencart-' . $version . '/upload/';

					if (substr($source, 0, strlen($remove)) == $remove) {
						// Only extract the contents of the upload folder
						$destination = str_replace('\\', '/', substr($source, strlen($remove)));

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

						echo '$source ' . $source . "\n";
						echo '$destination ' . $destination . "\n";
						echo '$path ' . $path . "\n";

						// Must not have a path before files and directories can be moved
						if (substr($path, -1) == '/' && mkdir($path, 0777)) {
							$json['error'] = $this->language->get('error_download');
						}

						// If check if the path is not directory and check there is no existing file
						if (substr($path, -1) != '/' && copy('zip://' . $file . '#' . $source, $path)) {
							$json['error'] = $this->language->get('error_download');
						}
					}
				}

				$zip->close();

				// Delete upgrade zip
				unlink($file);

				$json['redirect'] = HTTP_CATALOG . 'install/';
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}




}