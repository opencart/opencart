<?php
class ControllerSaleApi extends Controller {	
	public function index() {
		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		}
				
		if (!isset($this->session->data['cookie'])) {
			$json['error'] = $this->language->get('error_login');
		}		
		
		// Store
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->post['store_id'];
		} else {
			$store_id = 0;
		}
			
		$this->load->model('setting/store');
		
		$store_info = $this->model_setting_store->getStore($store_id);
		
		if ($store_info) {
			$url = $store_info['url'];
		} else {
			$url = HTTP_CATALOG;
		}
				
		$curl = curl_init();
		
		// Set SSL if required
		if (substr($url, 0, 5) == 'https') {
			curl_setopt($curl, CURLOPT_PORT, 443);
		}
		
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/login');
		
		if ($data) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		}
		
		if ($cookie) {
			curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $cookie . ';');
		}
		
		$response = curl_exec($curl);

		if (!$response) {
			return array('error' => curl_error($curl) . '(' . curl_errno($curl) . ')');
		}
				
		curl_close($curl);
		
		return json_decode($response, true);
	}
	
	public function login() {
		// Unset any old cookies fromthe last session
		unset($this->session->data['cookie']);
				
		// Store
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->post['store_id'];
		} else {
			$store_id = 0;
		}
			
		$this->load->model('setting/store');
		
		$store_info = $this->model_setting_store->getStore($store_id);
		
		if ($store_info) {
			$url = $store_info['url'];
		} else {
			$url = HTTP_CATALOG;
		}
		
		$this->load->model('user/api');
		
		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));
		
		if ($api_info) {
			$curl = curl_init();
			
			// Set SSL if required
			if (substr($url, 0, 5) == 'https') {
				curl_setopt($curl, CURLOPT_PORT, 443);
			}
			
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
			curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/login');
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));
		
			$response = curl_exec($curl);
	
			if (!$response) {
				$json = json_encode(array('error' => curl_error($curl) . '(' . curl_errno($curl) . ')'));
			} else {
				$json = json_encode($response);
			}
			
			if (isset($response['cookie'])) {
				$this->session->data['cookie'] = $response['cookie'];
			}
					
			curl_close($curl);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}