<?php
class ControllerCommonDeveloper extends Controller {
	public function index() {
		$this->load->language('common/developer');
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['developer_theme'] = $this->config->get('developer_theme');
		$data['developer_sass'] = $this->config->get('developer_sass');
		
		$this->response->setOutput($this->load->view('common/developer', $data));
	}
	
	public function edit() {
		$this->load->language('sale/developer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('sale/order');

			$this->model_marketing_affiliate->editTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);

			$json['success'] = $this->language->get('text_commission_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
		
	public function theme() {
		$this->load->language('common/developer');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {

			$json['success'] = $this->language->get('text_success');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
		
	public function sass() {
		$this->load->language('common/developer');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {

			$json['success'] = $this->language->get('text_success');
		}		
	}
}