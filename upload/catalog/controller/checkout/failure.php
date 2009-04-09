<?php 
class ControllerCheckoutFailure extends Controller {	 
	public function index() {
    	if (!$this->customer->isLogged()) {
      		$this->session->data['redirect'] = $this->url->https('checkout/failure'); 

			$this->redirect($this->url->https('account/login'));
    	}

    	$this->load->language('checkout/failure');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/shipping'),
        	'text'      => $this->language->get('text_shipping'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/payment'),
        	'text'      => $this->language->get('text_payment'),
        	'separator' => $this->language->get('text_separator')
      	);
      	
		$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/failure'),
        	'text'      => $this->language->get('text_failure'),
        	'separator' => $this->language->get('text_separator')
      	);
				
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = sprintf($this->language->get('text_message'), $this->url->http('information/contact'));

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->https('checkout/shipping');
		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'common/failure.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();		
  	}
}
?>
