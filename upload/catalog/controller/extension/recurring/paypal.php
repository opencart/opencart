<?php
class ControllerExtensionRecurringPayPal extends Controller {
	private $error = array();
			
	public function index() {
		$content = '';
		
		if ($this->config->get('payment_paypal_status') && !empty($this->request->get['order_recurring_id'])) {
			$this->load->language('extension/recurring/paypal');
		
			$this->load->model('account/recurring');
			
			$data['order_recurring_id'] = (int)$this->request->get['order_recurring_id'];

			$order_recurring_info = $this->model_account_recurring->getOrderRecurring($data['order_recurring_id']);
			
			if ($order_recurring_info) {
				$data['recurring_status'] = $order_recurring_info['status'];
				
				$data['info_url'] =  str_replace('&amp;', '&', $this->url->link('extension/recurring/paypal/getRecurringInfo', 'order_recurring_id=' . $data['order_recurring_id'], true));
				$data['enable_url'] =  str_replace('&amp;', '&', $this->url->link('extension/recurring/paypal/enableRecurring', '', true));
				$data['disable_url'] =  str_replace('&amp;', '&', $this->url->link('extension/recurring/paypal/disableRecurring', '', true));
				
				$content = $this->load->view('extension/recurring/paypal', $data);
			}
		}
		
		return $content;
	}
		
	public function getRecurringInfo() {
		$this->response->setOutput($this->index());
	}
	
	public function enableRecurring() {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_recurring_id'])) {
			$this->load->language('extension/recurring/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_recurring_id = $this->request->post['order_recurring_id'];
			
			$this->model_extension_payment_paypal->editOrderRecurringStatus($order_recurring_id, 1);
			
			$data['success'] = $this->language->get('success_enable_recurring');	
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function disableRecurring() {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_recurring_id'])) {
			$this->load->language('extension/recurring/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_recurring_id = $this->request->post['order_recurring_id'];
			
			$this->model_extension_payment_paypal->editOrderRecurringStatus($order_recurring_id, 2);
			
			$data['success'] = $this->language->get('success_disable_recurring');	
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
}