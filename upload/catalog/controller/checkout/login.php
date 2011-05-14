<?php  
class ControllerCheckoutLogin extends Controller { 
	public function index() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}	
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['account'])) {
				$this->session->data['account'] = $this->request->post['account'];
			}
	
			if (isset($this->request->post['email']) && isset($this->request->post['password'])) {
				if ($this->customer->login($this->request->post['email'], $this->request->post['password'])) {
					unset($this->session->data['guest']);
					
					$this->load->model('account/address');
		
					$address_info = $this->model_account_address->getAddress($this->customer->getAddressId());
		
					if ($address_info) {
						$this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
					}
				} else {
					$json['error']['warning'] = $this->language->get('error_login');
				}
			}
		} else {
			$this->data['text_new_customer'] = $this->language->get('text_new_customer');
			$this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
			$this->data['text_checkout'] = $this->language->get('text_checkout');
			$this->data['text_register'] = $this->language->get('text_register');
			$this->data['text_guest'] = $this->language->get('text_guest');
			$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
			$this->data['text_register_account'] = $this->language->get('text_register_account');
			$this->data['text_forgotten'] = $this->language->get('text_forgotten');
	 
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_password'] = $this->language->get('entry_password');
			
			$this->data['button_continue'] = $this->language->get('button_continue');
			$this->data['button_login'] = $this->language->get('button_login');
			
			$this->data['guest_checkout'] = ($this->config->get('config_guest_checkout') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());
			
			if (isset($this->session->data['account'])) {
				$this->data['account'] = $this->session->data['account'];
			} else {
				$this->data['account'] = 'register';
			}
			
			$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/login.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/login.tpl';
			} else {
				$this->template = 'default/template/checkout/login.tpl';
			}
					
			$json['output'] = $this->render();
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
	}
}
?>