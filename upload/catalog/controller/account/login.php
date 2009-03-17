<?php 
class ControllerAccountLogin extends Controller {
	private $error = array();
	
	public function index() {
		if ($this->customer->isLogged()) {  
      		$this->redirect($this->url->https('account/account'));
    	}
	
    	$this->load->language('account/login');

    	$this->document->title = $this->language->get('heading_title');
						
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {			
      		if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
      		} else {
				$this->redirect($this->url->https('account/account'));
      		} 
    	}  
		
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
        	'href'      => $this->url->http('account/login'),
        	'text'      => $this->language->get('text_login'),
        	'separator' => $this->language->get('text_separator')
      	);
				
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_new_customer'] = $this->language->get('text_new_customer');
    	$this->data['text_i_am_new_customer'] = $this->language->get('text_i_am_new_customer');
    	$this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
    	$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
    	$this->data['text_create_account'] = $this->language->get('text_create_account');
    	$this->data['text_forgotten_password'] = $this->language->get('text_forgotten_password');
    	$this->data['text_forgotten_password'] = $this->language->get('text_forgotten_password');

    	$this->data['entry_email'] = $this->language->get('entry_email_address');
    	$this->data['entry_password'] = $this->language->get('entry_password');

    	$this->data['button_continue'] = $this->language->get('button_continue');
    	$this->data['button_login'] = $this->language->get('button_login');

		$this->data['error'] = @$this->error['message'];
    
		$this->data['action'] = $this->url->https('account/login');

    	if (isset($this->request->post['redirect'])) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} else {
      		$this->data['redirect'] = @$this->session->data['redirect'];
	  		
			unset($this->session->data['redirect']);		  	
    	}

    	$this->data['success'] = @$this->session->data['success'];
    
		unset($this->session->data['success']);

    	$this->data['continue'] = $this->url->https('account/create');

    	$this->data['forgotten'] = $this->url->https('account/forgotten');
	
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'account/login.tpl';
		$this->layout   = 'module/layout';
		
		$this->render();
  	}
  
  	private function validate() {
    	if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
      		$this->error['message'] = $this->language->get('error_login');
    	}
	
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}  	
  	}
}
?>