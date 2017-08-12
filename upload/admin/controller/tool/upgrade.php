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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/upgrade', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['user_token'] = $this->session->data['user_token'];

		$request_data = array();
		
		$this->load->model('setting/extension');
		
		$results = $this->model_setting_extension->getExtensionInstalls(0, 1000);
		
		foreach ($results as $result) {
			if ($result['extension_download_id']) {
				$request_data['extension_download'][] = $result['extension_download_id'];
			}
		}
		
		$curl = curl_init(OPENCART_SERVER . 'index.php?route=upgrade/upgrade');

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data);
		
		$response = curl_exec($curl);

		curl_close($curl);

        $data['version'] = VERSION;

		$response_info = json_decode($response, true);

		if ($response_info) {
            $data['version'] = $response_info['version'];

			if (version_compare(VERSION, $response_info['version'], '>=')) {
			//if (version_compare('2.0', $json['version'], '>=') {
				$data['success'] = sprintf($this->language->get('text_success'), $json['version']);
			} else {
				$data['error_warning'] = sprintf($this->language->get('error_version'), $json['version']);
			}
		} else {
			$data['error_warning'] = $this->language->get('error_connection');
		}		

		$data['extensions'] = array();	
		
		if (isset($response_info['extension'])) {
			foreach ($response_info['extension'] as $result) {
				$data['extensions'][] = array(
					'extension_download_id' => $result['extension_download_id'],
					'extension_id'          => $result['extension_id'],
					'name'                  => $result['name'],
					'status'                => $result['status']
				); 
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upgrade', $data));
	}

	public function download() {
		$this->load->language('tool/upgrade');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}			

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
            $version = '';
        }

        if (!$json) {
            $filename = 'opencart-' . $version . '.zip';

            $curl = curl_init('https://github.com/repos/opencart/opencart/releases/download/' . $version . '/' . $version . '/' . $filename);

            curl_setopt($curl, CURLOPT_USERAGENT, 'OpenCart ' . VERSION);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

            $response = curl_exec($curl);

			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			curl_close($curl);

            if ($status == 200) {
                $handle = fopen(DIR_DOWNLOAD . filename, 'w');

                fwrite($handle, $response);

                fclose($handle);
            } else {
                $json['error'] = $this->language->get('error_download');
            }
        }

        if ($version != VERSION) {
			$json['next'] = $this->url->link('tool/upgrade/unzip', 'version=' . VERSION);
		} else {
			$json['next'] = $this->url->link('tool/upgrade/download', 'version=' . $version);
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

		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
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
				$zip->extractTo(DIR_DOWNLOAD . $version);
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function compare() {
		$this->load->language('tool/upgrade');

		$json = array();

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_DOWNLOAD . $version . '/upload/';

		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			$this->session->data['patch'] = array();

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
				$destination = str_replace('\\', '/', substr($file, strlen($directory)));

				$path = '';

				// Check if the copy location exists or not
				if (substr($destination, 0, 5) == 'admin') {
					$path = DIR_APPLICATION . substr($destination, 6);
				}

				if (substr($destination, 0, 7) == 'catalog') {
					$path = DIR_CATALOG . substr($destination, 8);
				}

				if (substr($destination, 0, 5) == 'image') {
					$path = DIR_IMAGE . substr($destination, 6);
				}

				if (substr($destination, 0, 6) == 'system') {
					$path = DIR_SYSTEM . substr($destination, 7);
				}

				if (is_file($file) && sha1_file() != ) {
					$this->session->data['patch'][] = array();
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function patch() {
		if ($this->request->get['skip'] = false) {
			unset($this->session->data['patch'][0]);
		} else {
			//twig_date_format_filter();



			//file_put_contents();
		}

		$json['source'] = file_get_contents($this->session->data['patch'][0]);

		$json['patch'] = file_get_contents($this->session->data['patch'][0]);

		if (count($this->session->data['patch'])) {
			$json['next'] = $this->url->link('tool/upgrade/patch', 'version=' . $version);
		} else {
			$json['next'] = $this->url->link('tool/upgrade/install', 'version=' . $version);
		}
	}

	public function install() {
		$this->load->language('tool/upgrade');

		$json = array();

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_DOWNLOAD . $version . '/upload/';

		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
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
				$destination = str_replace('\\', '/', substr($file, strlen($directory)));

				$path = '';

				// Check if the copy location exists or not
				if (substr($destination, 0, 5) == 'admin') {
					$path = DIR_APPLICATION . substr($destination, 6);
				}

				if (substr($destination, 0, 7) == 'catalog') {
					$path = DIR_CATALOG . substr($destination, 8);
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
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}

	public function remove() {
		$this->load->language('tool/upgrade');

		$json = array();

		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}