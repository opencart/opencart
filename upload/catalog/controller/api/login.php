<?php
namespace Opencart\Application\Controller\Api;
class Login extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('api/login');

		$json = [];

		$this->load->model('account/api');

		// Login with API Key
		$api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['key']);

		if ($api_info) {
			// Check if IP is allowed
			$ip_data = [];
	
			$results = $this->model_account_api->getIps($api_info['api_id']);
	
			foreach ($results as $result) {
				$ip_data[] = trim($result['ip']);
			}
	
			if (!in_array($this->request->server['REMOTE_ADDR'], $ip_data)) {
				$json['error']['ip'] = sprintf($this->language->get('error_ip'), $this->request->server['REMOTE_ADDR']);
			}				
				
			if (!$json) {
				$json['success'] = $this->language->get('text_success');

				$session = new \Opencart\Engine\Library\Session($this->config->get('session_engine'), $this->registry);
				$session->start();
				
				$this->model_account_api->addSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
				
				$session->data['api_id'] = $api_info['api_id'];
				
				// Create Token
				$json['api_token'] = $session->getId();
			} else {
				$json['error']['key'] = $this->language->get('error_key');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
