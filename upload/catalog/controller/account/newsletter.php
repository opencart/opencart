<?php 
class ControllerAccountNewsletter extends Controller {  
	public function index() {
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/newsletter', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
		
		$this->language->load('account/newsletter');
    	
		$this->document->setTitle($this->language->get('heading_title'));
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('account/customer');
			
			$this->model_account_customer->editNewsletter($this->request->post['newsletter']);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_newsletter'),
			'href'      => $this->url->link('account/newsletter', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

    	$this->data['action'] = $this->url->link('account/newsletter', '', 'SSL');
		
		$this->data['newsletter'] = $this->customer->getNewsletter();
		
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/newsletter.tpl';
		} else {
			$this->template = 'default/template/account/newsletter.tpl';
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