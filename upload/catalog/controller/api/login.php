<?php
class ControllerApiLogin extends Controller {
	public function index() {
		$this->load->language('api/login');

		$json = array();

		$this->load->model('account/api');

		// Login with API Key
		$api_info = $this->model_account_api->getApiByKey($this->request->post['key']);

		if ($api_info) {
			// Check if IP is allowed
			$ip_data = array();
	
			$results = $this->model_account_api->getApiIps($api_info['api_id']);
	
			foreach ($results as $result) {
				$ip_data[] = trim($result['ip']);
			}
	
			if (!in_array($this->request->server['REMOTE_ADDR'], $ip_data)) {
				$json['error']['ip'] = sprintf($this->language->get('error_ip'), $this->request->server['REMOTE_ADDR']);
			}				
				
			if (!$json) {	
				$json['success'] = $this->language->get('text_success');

				$session_name = 'temp_session_' . uniqid();

				$session = new Session();
				$session->start($this->session->getId(), $session_name);

				// Set API ID
				$session->data['api_id'] = $api_info['api_id'];

				// Create Token
				$json['token'] = $this->model_account_api->addApiSession($api_info['api_id'], $session_name, $session->getId(), $this->request->server['REMOTE_ADDR']);
			} else {
				$json['error']['key'] = $this->language->get('error_key');
			}
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
