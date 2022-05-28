<?php
namespace Opencart\Catalog\Controller\Api\Account;
class Login extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/account/login');

		$json = [];

		$this->load->model('account/api');

		// Login with API Key
		if (!empty($this->request->post['username']) && !empty($this->request->post['key'])) {
			$api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['key']);
		} else {
			$api_info = [];
		}

		if ($api_info) {
			// Check if IP is allowed
			$ip_data = [];

			$results = $this->model_account_api->getIps($api_info['api_id']);

			foreach ($results as $result) {
				$ip_data[] = trim($result['ip']);
			}

			if (!in_array($this->request->server['REMOTE_ADDR'], $ip_data)) {
				$json['error'] = sprintf($this->language->get('error_ip'), $this->request->server['REMOTE_ADDR']);
			}
		} else {
			$json['error'] = $this->language->get('error_key');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$session = new \Opencart\System\Library\Session($this->config->get('session_engine'), $this->registry);
			$session->start();

			$this->model_account_api->addSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);

			$session->data['api_id'] = $api_info['api_id'];

			// Create Token
			$json['api_token'] = $session->getId();
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
