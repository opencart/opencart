<?php
class ControllerApiPayment extends Controller {
	public function index() {
		$this->load->language('module/reward');
		
		$json = array();
		
		// Payment Address
		if (!isset($this->session->data['payment_address'])) {
			$json['error']['payment_address'] = $this->language->get('error_payment_address');
		}		
		
		if (!$json) {
			// Totals
			$total_data = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();
			
			$this->load->model('setting/extension');
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
			}
			
			$json['payment_method'] = array();
			
			$results = $this->model_setting_extension->getExtensions('payment');

			$cart_has_recurring = $this->cart->hasRecurringProducts();
	
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('payment/' . $result['code']);
					
					$method = $this->{'model_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total); 
					
					if ($method) {
						if ($cart_has_recurring > 0) {
							if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments')) {
								if ($this->{'model_payment_' . $result['code']}->recurringPayments() == true) {
									$json['payment_method'][$result['code']] = $method;
								}
							}
						} else {
							$json['payment_method'][$result['code']] = $method;
						}
					}
				}
			}

			$sort_order = array(); 
		  
			foreach ($json['payment_method'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $json['payment_method']);			
			
			if ($json['payment_method']) {
				$this->session->data['payment_methods'] = $json['payment_method'];
			} else {
				$json['error']['payment_method'] = $this->language->get('error_no_payment');
			}			
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}