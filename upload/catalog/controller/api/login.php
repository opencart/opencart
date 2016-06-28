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
			
				// We want to create a seperate session so changes do not interfere with the admin user.
				$session_id_new = strtolower(token(32));
				
				//echo $this->session->getId() . '<br />';
				
				//$session_id_old = $this->session->getId();
				
				$file = session_save_path() . '\sess_' . $session_id_new;
				
				$handle = fopen($file, 'w');
				
				fwrite($handle, serialize(array('api_id' => $api_info['api_id'])));
				
				fclose($handle);
				
				//$data['api_id'] = $api_info['api_id'];			
				// Close and write the current session.
				//$this->session->close();
				
				// Start a new session.
				//$this->session->start($session_id_new, 'test');

				//setcookie('api', $session_id_new);
				//setcookie('test=' . $session_id_new);

				// Set API ID in the new session
				//$this->session->data['api_id'] = $api_info['api_id'];
				
				//header("Set-Cookie: PHPSESSID=" . session_id() . "; path=/");
				
				//echo $this->session->getId() . '<br />';
				
				// Close and write the new session.
				//$this->session->close();
				
				// Start the old session.
				//$this->session->start($session_id_old, 'PHPSESSID');

				//echo $this->session->getId() . '<br />';

				// Create Token
				$json['token'] = $this->model_account_api->addApiSession($api_info['api_id'], $session_id_new, $this->request->server['REMOTE_ADDR']);
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
