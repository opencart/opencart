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
		
		// Login by token
		if (!empty($this->request->get['signature'])) {
			$encypt = new Encryption($this->config->get('config_encyption'));
		
			$string = $encrypt->decrypt($this->request->get['signature']);
			
			$part = explode(':', $string);
			
			if (isset($part[0])) {
				$username = $part[0];
			}
			
			if (isset($part[1])) {
				$password = $part[1];
			}
			
			if (isset($part[2])) {
				$time = $part[2];
			}
			
			if (isset($part[3])) {
				$ip = $part[3];
			}
			
			if ($ip == ) {
				
			}					
		}

		if (isset($this->request->post['username'])) {
			$username = $this->request->post['username'];
		}
		
		if (isset($this->request->post['password'])) {
			$password = $this->request->post['password'];
		}
		
		// Login with username and password
		$api_info = $this->model_account_api->login($username, $password);

		if ($api_info) {
			$json['success'] = $this->language->get('text_success');
			
			$this->session->data['api_id'] = $api_info['api_id'];
			
			$json['cookie'] = $this->session->getId();
			
			// Create a token to send back. this must be included in request url			
			$token = token(64);
			
			$json['token'] = $token;

			$this->session->data['token'] = $token;
		} else {
			$json['error'] = $this->language->get('error_login');
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