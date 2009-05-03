<?php 
class ControllerAccountSuccess extends Controller {  
	public function index() {
		if (!$this->customer->isLogged()) {
      		$this->redirect($this->url->https('account/login'));
    	}
	
    	$this->load->language('account/success');

    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/account'),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/success'),
        	'text'      => $this->language->get('text_success'),
        	'separator' => $this->language->get('text_separator')
      	);

    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');
		
		if ($this->cart->hasProducts()) {
			$this->data['continue'] = $this->url->http('checkout/cart');
		} else {
			$this->data['continue'] = $this->url->http('account/account');
		}
		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'common/success.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();				
  	}
}
?>