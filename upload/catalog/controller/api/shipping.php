<?php
class ControllerApiShipping extends Controller {
	public function index() {
		$this->load->language('module/reward');
		
		$json = array();
				
		if ($this->cart->hasShipping()) {
			// Shipping Address
			if (!isset($this->session->data['shipping_address'])) {
				$json['error']['shipping_address'] = $this->language->get('error_shipping_address');
			}			
			
			if (!$json) {
				$json['shipping_method'] = array();
				
				$this->load->model('setting/extension');
				
				$results = $this->model_setting_extension->getExtensions('shipping');
				
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('shipping/' . $result['code']);
						
						$quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_data); 
			
						if ($quote) {
							$json['shipping_method'][$result['code']] = array( 
								'title'      => $quote['title'],
								'quote'      => $quote['quote'], 
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
							);
						}
					}
				}
		
				$sort_order = array();
			  
				foreach ($json['shipping_method'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
		
				array_multisort($sort_order, SORT_ASC, $json['shipping_method']);
	
				if ($json['shipping_method']) {
					$this->session->data['shipping_methods'] = $json['shipping_method'];
				} else {
					$json['error']['shipping_method'] = $this->language->get('error_no_shipping');
				}
			}
		}
		
		$this->response->setOutput(json_encode($json));	
	}
}