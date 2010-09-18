<?php 
class ControllerAccountAccount extends Controller { 
	public function index() {
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = HTTPS_SERVER . 'index.php?route=account/account';
	  
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=account/login');
    	} 
	
		$this->language->load('account/account');

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=account/account',
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

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

    	$this->data['information'] = HTTPS_SERVER . 'index.php?route=account/edit';
    	$this->data['password'] = HTTPS_SERVER . 'index.php?route=account/password';
		$this->data['address'] = HTTPS_SERVER . 'index.php?route=account/address';
    	$this->data['history'] = HTTPS_SERVER . 'index.php?route=account/history';
    	$this->data['download'] = HTTPS_SERVER . 'index.php?route=account/download';
		$this->data['newsletter'] = HTTPS_SERVER . 'index.php?route=account/newsletter';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/account.tpl';
		} else {
			$this->template = 'default/template/account/account.tpl';
		}
		
		$this->children = array(
			'common/column_right',
			'common/footer',
			'common/column_left',
			'common/header'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}
}
?>
