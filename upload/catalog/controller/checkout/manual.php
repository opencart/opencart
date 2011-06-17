<?php 
class ControllerCheckoutManual extends Controller {
	public function cart() {
	}
	
	public function shipping() {
	}
	
	public function shipping() {
		$json = array();
		
		$this->load->library('user');
		
		if ($this->user->isLogged()) {
			$this->language->load('checkout/checkout');
			
			$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
			
			if (!isset($this->session->data['shipping_methods'])) {
				$quote_data = array();
				
				$this->load->model('setting/extension');
				
				$results = $this->model_setting_extension->getExtensions('shipping');
				
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('shipping/' . $result['code']);
						
						$quote = $this->{'model_shipping_' . $result['code']}->getQuote($shipping_address); 
			
						if ($quote) {
							$quote_data[$result['code']] = array( 
								'title'      => $quote['title'],
								'quote'      => $quote['quote'], 
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
							);
						}
					}
				}
		
				$sort_order = array();
			  
				foreach ($quote_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
		
				array_multisort($sort_order, SORT_ASC, $quote_data);
				
				$this->session->data['shipping_methods'] = $quote_data;
			}
			
		}				
				
		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
  	}
}
?>