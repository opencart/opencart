<?php
class ControllerApiLogin extends Controller {
	public function index() {
		$this->load->language('api/login');

		// Delete old login so not to cause any issues if there is an error
		unset($this->session->data['api_id']);
			
		$keys = array(
			'username',
			'password'
		);

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		$json = array();

		$this->load->model('account/api');
		
		// Login with username and password
		$api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['password']);

		if ($api_info) {
			$json['success'] = $this->language->get('text_success');
			
			echo $this->session->getId() . '<br>';
			
			$this->session->close();
			
			$session = new Session();
			
			$session->setName('PHPSESSID_' . uniqid());
			$session->setId();
			$session->start();
			
			$session->data['api_id'] = $api_info['api_id'];
						
			// Create Token		
			$json['token'] = $this->model_account_api->addSession($api_info['api_id'], $this->session->getName(), $this->session->getId(), $this->request->server['REMOTE_ADDR']);		
		} else {
			$json['error'] = $this->language->get('error_login');
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Credentials: true');
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}