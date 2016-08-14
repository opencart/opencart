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

		$api_info = $this->model_account_api->login($this->request->post['username'], $this->request->post['password']);

		if ($api_info) {
			$this->session->data['api_id'] = $api_info['api_id'];

			$json['cookie'] = $this->session->getId();

			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->language->get('error_login');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}