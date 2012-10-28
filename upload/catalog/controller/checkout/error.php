<?php
class ControllerCheckoutError extends Controller { 
	public function index() { 	
		
		if ( isset($this->session->data['order_id']) && ( ! empty($this->session->data['order_id']))  ) {
			$this->session->data['last_order_id'] = $this->session->data['order_id'];
		}	
									   
		$this->language->load('checkout/error');
		
		if (! empty($this->session->data['last_order_id']) ) {
			$this->document->setTitle(sprintf($this->language->get('heading_title_customer'), $this->session->data['last_order_id']));
		} else {
    		$this->document->setTitle($this->language->get('heading_title'));
		}
		
		$this->data['breadcrumbs'] = array(); 

      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => false
      	); 
		
      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);
				
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/error', '', 'SSL'),
			'text'      => $this->language->get('text_checkout'),
			'separator' => $this->language->get('text_separator')
		);	
					
      	$this->data['breadcrumbs'][] = array(
        	'href'      => $this->url->link('checkout/error'),
        	'text'      => $this->language->get('text_error'),
        	'separator' => $this->language->get('text_separator')
      	);

		if (! empty($this->session->data['last_order_id']) ) {
			$this->data['heading_title'] = sprintf($this->language->get('heading_title_customer'), $this->session->data['last_order_id']);
		} else {
    		$this->data['heading_title'] = $this->language->get('heading_title');
		}
		
    	$this->data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('information/contact'));
		
    	$this->data['button_continue'] = $this->language->get('button_continue');

		$this->data['continue'] = $this->url->link('checkout/checkout');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/error.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/error.tpl';
		} else {
			$this->template = 'default/template/checkout/error.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'			
		);
				
		$this->response->setOutput($this->render());
  	}
}
?>