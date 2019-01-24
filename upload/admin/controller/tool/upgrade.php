<?php
/*

Installed versionh
current version
latest version

Preparation

1. Check compatibility of extensions with the latest version

Backup

2. Download a copy of the current version

3. Scan files to confirm what changes have been made to the installed version and the current

4. Let the user download the copies of all the modified files

5. database

6. image


Upgrade

5. Download a copy of the latest version

6. Scan files to confirm what changes have been made between the current version and latest

6. Alert the user to any modified files that have not be updated

7. Allow the user to download the changed files.

8. Replace the files
*/
class ControllerToolUpgrade extends Controller {
    public function index() {
		$this->load->language('tool/upgrade');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/upgrade', 'user_token=' . $this->session->data['user_token'])
		);

		$data['user_token'] = $this->session->data['user_token'];

		$data['version'] = VERSION;
		$data['upgrade'] = false;

		$request_data['extension'] = array();

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensionInstalls(0, 1000);

		foreach ($results as $result) {
			if ($result['extension_id']) {
				$request_data['extension'][] = $result['extension_id'];
			}
		}

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/upgrade');

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($request_data));

		$response = curl_exec($curl);

		curl_close($curl);

		$response_info = json_decode($response, true);

		// Extension compatibility check
		$data['extensions'] = array();

		if ($response_info) {
			if (version_compare(VERSION, $response_info['version'], '>=')) {
				$data['success'] = sprintf($this->language->get('text_success'), $response_info['version']);
			} else {
				$data['version'] = $response_info['version'];
				$data['log'] = $response_info['log'];

				$data['upgrade'] = true;

				$data['error_warning'] = sprintf($this->language->get('error_version'), $response_info['version']);

				if (isset($response_info['extension'])) {
					foreach ($results as $result) {
						if (isset($response_info['extension'][$result['extension_id']])) {
							$compatible = false;

							$extension = $response_info['extension'][$result['extension_id']];

							if (isset($extension['download'][$result['extension_download_id']]) && in_array($response_info['version'], $extension['download'][$result['extension_download_id']])) {
								$compatible = true;
							}

							$available = false;

							foreach ($extension['download'] as $extension_download_id => $download) {
								if (in_array($response_info['version'], $download)) {
									$available = true;

									break;
								}
							}

							$data['extensions'][] = array(
								'name'       => $extension['name'],
								'link'       => $this->url->link('marketplace/marketplace/info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id']),
								'compatible' => $compatible,
								'available'  => $available
							);
						}
					}
				}
			}
		} else {
			$data['error_warning'] = $this->language->get('error_connection');
		}

		$data['backup'] = $this->url->link('tool/backup', 'user_token=' . $this->session->data['user_token']);
    	$data['opencart_account'] = 'https://www.opencart.com/index.php?route=account/account';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upgrade', $data));
	}

	public function modified() {
		$this->load->language('tool/upgrade');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			set_time_limit(0);

			$curl = curl_init('https://www.opencart.com/index.php?route=api/modified/' . VERSION);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

			$response = curl_exec($curl);

			curl_close($curl);

			$response_info = json_decode($response, true);

			if ($response_info) {
				foreach ($response_info['file'] as $file) {
					$destination = str_replace('\\', '/', substr($file, strlen($directory . '/')));

					$path = str_replace('\\', '/', realpath(DIR_CATALOG . '../')) . '/' . $destination;

					// Check if the copy location exists or not
					if (substr($destination, 0, 5) == 'admin') {
						$path = DIR_APPLICATION . substr($destination, 6);
					}

					if (substr($destination, 0, 7) == 'catalog') {
						$path = DIR_CATALOG . substr($destination, 8);
					}

					if (substr($destination, 0, 7) == 'install') {
						$path = DIR_IMAGE . substr($destination, 8);
					}

					if (substr($destination, 0, 5) == 'image') {
						$path = DIR_IMAGE . substr($destination, 6);
					}

					if (substr($destination, 0, 6) == 'system') {
						$path = DIR_SYSTEM . substr($destination, 7);
					}

					if (is_dir($file) && !is_dir($path)) {
						if (!mkdir($path, 0777)) {
							$json['error'] = sprintf($this->language->get('error_directory'), $destination);
						}
					}

					if (is_file($file)) {
						if (!rename($file, $path)) {
							$json['error'] = sprintf($this->language->get('error_file'), $destination);
						}
					}
				}
			} else {
				$json['error'] = $this->language->get('error_download');
			}

			$json['text'] = $this->language->get('text_unzip');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('tool/upgrade/unzip', 'user_token=' . $this->session->data['user_token']));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function download() {
		$this->load->language('tool/upgrade');

		$json = array();

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
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
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

				$json['next'] = str_replace('&amp;', '&', $this->url->link('tool/upgrade/unzip', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version));
			} else {
				$json['error'] = $this->language->get('error_download');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function unzip() {
		$this->load->language('tool/upgrade');

		$json = array();

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
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(DIR_DOWNLOAD . 'opencart-' . $version);
				$zip->close();

				$json['text'] = $this->language->get('text_move');

				$json['next'] = str_replace('&amp;', '&', $this->url->link('tool/upgrade/move', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version));
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function move() {
		$this->load->language('tool/upgrade');

		$json = array();

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_DOWNLOAD . 'opencart-' . $version . '/opencart-' . $version . '/upload/';

		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			$ignore = array(
				'config-dist.php',
				'admin/config-dist.php'
			);

			$files = array();

			// Get a list of files ready to upload
			$path = array($directory . '/*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					$files[] = $file;
				}
			}

			foreach ($files as $file) {
				$destination = str_replace('\\', '/', substr($file, strlen($directory . '/')));

				$path = str_replace('\\', '/', realpath(DIR_CATALOG . '../')) . '/' . $destination;

				// Check if the copy location exists or not
				if (substr($destination, 0, 5) == 'admin') {
					$path = DIR_APPLICATION . substr($destination, 6);
				}

				if (substr($destination, 0, 7) == 'catalog') {
					$path = DIR_CATALOG . substr($destination, 8);
				}

				if (substr($destination, 0, 7) == 'install') {
					$path = DIR_IMAGE . substr($destination, 8);
				}

				if (substr($destination, 0, 5) == 'image') {
					$path = DIR_IMAGE . substr($destination, 6);
				}

				if (substr($destination, 0, 6) == 'system') {
					$path = DIR_SYSTEM . substr($destination, 7);
				}

				if (is_dir($file) && !is_dir($path)) {
					if (!mkdir($path, 0777)) {
						$json['error'] = sprintf($this->language->get('error_directory'), $destination);
					}
				}

				if (is_file($file)) {
					if (!rename($file, $path)) {
						$json['error'] = sprintf($this->language->get('error_file'), $destination);
					}
				}
			}

			$json['text'] = $this->language->get('text_remove');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('tool/upgrade/remove', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('tool/upgrade');

		$json = array();

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/upgrade/remove&version=' . VERSION);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

			$response = curl_exec($curl);

			curl_close($curl);

			$response_info = json_decode($response, true);

			if ($response_info) {
				foreach ($response_info['remove'] as $remove) {
					$path = '';

					// Check if the location exists or not
					if (substr($remove, 0, 5) == 'admin') {
						$path = DIR_APPLICATION . substr($remove, 6);
					}

					if (substr($remove, 0, 7) == 'catalog') {
						$path = DIR_CATALOG . substr($remove, 8);
					}

					if (substr($remove, 0, 5) == 'image') {
						$path = DIR_IMAGE . substr($remove, 6);
					}

					if (substr($remove, 0, 6) == 'system') {
						$path = DIR_SYSTEM . substr($remove, 7);
					}

					if ($path) {
						if (is_file($path)) {
							unlink($path);
						} elseif (is_dir($path)) {
							$files = glob($path . '/*');

							if (!count($files)) {
								rmdir($path);
							}
						}
					}
				}

				$json['text'] = $this->language->get('text_db');

				$json['next'] = str_replace('&amp;', '&', $this->url->link('tool/upgrade/db', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version));
			} else {
				$data['error'] = $this->language->get('error_connection');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function db() {
		$this->load->language('tool/upgrade');

		$json = array();

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$files = glob(DIR_APPLICATION .  'model/upgrade/*.php');

			if ($files) {
				foreach ($files AS $file) {
					$upgrade = basename($file, '.php');

					$this->load->model('upgrade/' . $upgrade);

					$this->{'model_upgrade_' . $upgrade}->upgrade();
				}
			}

			$json['text'] = $this->language->get('text_clear');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('tool/upgrade/clear', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('tool/upgrade');

		$json = array();

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$directory = DIR_DOWNLOAD . 'opencart-' . $version . '/';

			if (is_dir($directory)) {
				// Get a list of files ready to upload
				$files = array();

				$path = array($directory);

				while (count($path) != 0) {
					$next = array_shift($path);

					// We have to use scandir function because glob will not pick up dot files.
					foreach (array_diff(scandir($next), array('.', '..')) as $file) {
						$file = $next . '/' . $file;

						if (is_dir($file)) {
							$path[] = $file;
						}

						$files[] = $file;
					}
				}

				rsort($files);

				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}

				if (is_dir($directory)) {
					rmdir($directory);
				}
			}

			$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

			if (is_file($file)) {
				unlink($file);
			}

			$json['success'] = sprintf($this->language->get('text_success'), VERSION);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
