<?php 
class ControllerCheckoutShipping extends Controller {
  	public function index() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		$this->load->model('account/address');
		
		if ($this->customer->isLogged()) {					
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}	
		
		if (!isset($shipping_address)) {								
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}
				
		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$json) {
				if (!isset($this->request->post['shipping_method'])) {
					$json['error']['warning'] = $this->language->get('error_shipping');
				} else {
					$shipping = explode('.', $this->request->post['shipping_method']);
					
					if (!isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
						$json['error']['warning'] = $this->language->get('error_shipping');
					}
				}			
			}
			
			if (!$json) {
				$shipping = explode('.', $this->request->post['shipping_method']);
				
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				
				$this->session->data['comment'] = strip_tags($this->request->post['comment']);
			}			
		} else {
			if (isset($shipping_address)) {
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
						
			$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$this->data['text_comments'] = $this->language->get('text_comments');
		
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			if (isset($this->session->data['shipping_methods']) && !$this->session->data['shipping_methods']) {
				$this->data['error_warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
			} else {
				$this->data['error_warning'] = '';
			}	
						
			if (isset($this->session->data['shipping_methods'])) {
				$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
			} else {
				$this->data['shipping_methods'] = array();
			}
			
			if (isset($this->session->data['shipping_method']['code'])) {
				$this->data['code'] = $this->session->data['shipping_method']['code'];
			} else {
				$this->data['code'] = '';
			}
			
			if (isset($this->session->data['comment'])) {
				$this->data['comment'] = $this->session->data['comment'];
			} else {
				$this->data['comment'] = '';
			}
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/shipping.tpl';
			} else {
				$this->template = 'default/template/checkout/shipping.tpl';
			}
					
			$json['output'] = $this->render();	
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
  	}
}
?>