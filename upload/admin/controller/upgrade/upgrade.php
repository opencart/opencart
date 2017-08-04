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
			'href' => $this->url->link('tool/upgrade', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['user_token'] = $this->session->data['user_token'];

		



		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('upgrade/upgrade', $data));
	}

	public function version() {
		$this->load->language('tool/upgrade');
		
		$json = array();

		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}	
				
		if (!$json) {
			$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/version');
	
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			
			$response = curl_exec($curl);
	
			curl_close($curl);	
			
			$response_info = json_decode($response, true);
			
			if (isset($response_info['version'])) {
				$json['version'] = $response_info['version'];
				
				if (VERSION >= $response_info['version']) {
					$json['success'] = sprintf($this->language->get('text_success'), $response_info['version']);
				} else {
					$json['error'] = sprintf($this->language->get('error_version'), $response_info['version']);
				}
			} else {
				$json['error'] = $this->language->get('error_connection');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function compatibility() {
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

		if (!$version) {
			$json['error'] = $this->language->get('error_compatibility');
		}	
				
		if (!$json) {
			$this->load->model('setting/extension');
			
			$request_data = array();
			
			$results = $this->model_setting_extension->getTotalExtensionInstalls();
			
			foreach ($results as $result) {
				$request_data['extension_download_ids'][] = $result['extension_download_id'];
			}
			
			$curl = curl_init(OPENCART_SERVER . 'index.php?route=marketplace/api/compatibility');
	
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data);
			
			$response = curl_exec($curl);
	
			curl_close($curl);	
			
			$response_info = json_decode($response, true);
			
			if ($response_info) {
				$json['extension'] = array();
				
				foreach ($response_info as $result) {
					$json['extension'][] = array(
						'extension_download_id' => $result['extension_download_id'],
						'extension_id'          => $result['extension_id'],
						'name'                  => $result['name'],
						'extension_download'    => $result['extension_download']
					); 
				}
			} else {
				$json['error'] = $this->language->get('error_download');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
				
	public function download() {
		$this->load->language('tool/upgrade');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}			
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
		
	public function install() {
		$this->load->language('tool/upgrade');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}			
		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}

}