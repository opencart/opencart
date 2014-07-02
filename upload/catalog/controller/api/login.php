<?php
class ControllerApiLogin extends Controller {
	public function index() {
		$this->load->language('api/login');
		
		$json = array();
		
		$this->model->load('api');
		
		$api_info = $this->model_user_api->login($this->request->post['username'], $this->request->post['password']);
		
		if ($api_info) {
			$this->session->data['api_id'] = $api_info['api_id'];
			
			$json['cookie'] = session_id();
			
			$json['success'] = $this->language->get('text_success');			
		} else {
			$json['error'] = $this->language->get('error_login');
		}
		
		$this->response->setOutput(json_encode($json));					
	}
}