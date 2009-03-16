<?php   
class ControllerModuleHeader extends Controller {
	protected function index() {
		$this->load->language('module/header');
	    	
		$this->data['store'] = $this->config->get('config_store');
		$this->data['text_home'] = $this->language->get('text_home');
    	$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_login'] = $this->language->get('text_login');
    	$this->data['text_logout'] = $this->language->get('text_logout');
    	$this->data['text_cart'] = $this->language->get('text_cart'); 
    	$this->data['text_checkout'] = $this->language->get('text_checkout');

		$this->data['home'] = $this->url->http('common/home');
    	$this->data['account'] = $this->url->https('account/account');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['login'] = $this->url->https('account/login');
		$this->data['logout'] = $this->url->http('account/logout');
    	$this->data['cart'] = $this->url->http('checkout/cart');
		$this->data['checkout'] = $this->url->https('checkout/shipping');

		$this->id       = 'header';
		$this->template = $this->config->get('config_template') . 'module/header.tpl';
		$this->children = array(
			'module/language',
			'module/search'
		);
		
    	$this->render();
	}	
}
?>