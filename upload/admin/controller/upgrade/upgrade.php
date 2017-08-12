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
class ControllerUpgradeUpgrade extends Controller {
    public function index() {
		$this->load->language('upgrade/upgrade');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('upgrade/upgrade', 'user_token=' . $this->session->data['user_token'], true)
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
		
		$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/upgrade/upgrade');

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

		$this->response->setOutput($this->load->view('upgrade/upgrade', $data));
	}

	public function download() {
		$this->load->language('upgrade/upgrade');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
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

            if () {
                $handle = fopen(DIR_DOWNLOAD . filename, 'w');

                fwrite($handle, $response);

                fclose($handle);
            } else {
                $json['error'] = $this->language->get('error_download');
            }

            curl_close($curl);
        }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function unzip() {
		$this->load->language('upgrade/upgrade');

		$json = array();

		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

		if (!is_file($file)) {

		}

		if (!$json) {

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {
		$this->load->language('upgrade/upgrade');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}


	public function remove() {
		$this->load->language('upgrade/upgrade');

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