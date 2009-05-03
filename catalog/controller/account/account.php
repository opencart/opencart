<?php 
class ControllerAccountAccount extends Controller { 
	public function index() {
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->https('account/account');
	  
	  		$this->redirect($this->url->https('account/login'));
    	} 
	
		$this->load->language('account/account');

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

		$this->document->title = $this->language->get('heading_title');

    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_my_account'] = $this->language->get('text_my_account');
		$this->data['text_my_orders'] = $this->language->get('text_my_orders');
		$this->data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
    	$this->data['text_information'] = $this->language->get('text_information');
    	$this->data['text_password'] = $this->language->get('text_password');
    	$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_history'] = $this->language->get('text_history');
    	$this->data['text_download'] = $this->language->get('text_download');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');

    	$this->data['success'] = @$this->session->data['success'];
    
		unset($this->session->data['success']);

    	$this->data['information'] = $this->url->https('account/edit');
    	$this->data['password'] = $this->url->https('account/password');
		$this->data['address'] = $this->url->https('account/address');
    	$this->data['history'] = $this->url->https('account/history');
    	$this->data['download'] = $this->url->https('account/download');
		$this->data['newsletter'] = $this->url->https('account/newsletter');
	
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'account/account.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();		
  	}
}
?>
