<?php
class ControllerApiLogin extends Controller {
	public function index() {
		$this->load->language('api/login');
		
		$json = array();
		
		if (!$json) {
		//	$json['success'] = $this->language->get('text_success');			
		}
		
		$json['cookie'] = session_id();
		
		$this->response->setOutput(json_encode($json));					
	}
}