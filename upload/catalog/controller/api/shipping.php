<?php
class ControllerApiShipping extends Controller {
	public function index() {
		$json = array();		
		
		if ($this->cart->hasShipping()) {
			$json['error']['cart'] = '';
		}	
					
		// Validate if payment address has been set.
		if (!isset($this->session->data['shipping_address'])) {
			$json['error']['shipping_address'] = '';
		}			
		
		if (!$json) {
			$json['shipping_method'] = array();

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

			if (!$json['shipping_method']) {
				$json['error']['shipping_method'] = $this->language->get('error_no_shipping');
			} elseif ($this->request->post['shipping_code']) {
				$shipping = explode('.', $this->request->post['shipping_code']);
				
				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($json['shipping_method'][$shipping[0]]['quote'][$shipping[1]])) {		
					$json['error']['shipping_method'] = $this->language->get('error_shipping');
				} else {
					$this->session->data['shipping_method'] = $json['shipping_method'][$shipping[0]]['quote'][$shipping[1]];
				}				
			}					
		}
		
		$this->response->setOutput(json_encode($json));	
	}
}