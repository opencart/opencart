<?php 
class ControllerAccountLogin extends Controller {
	private $error = array();
	
	public function index() {
		if ($this->customer->isLogged()) {  
      		$this->redirect(HTTPS_SERVER . 'index.php?route=account/account');
    	}
	
    	$this->language->load('account/login');

    	$this->document->title = $this->language->get('heading_title');
						
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (isset($this->request->post['account'])) {
				$this->session->data['account'] = $this->request->post['account'];
				
				if ($this->request->post['account'] == 'register') {
					$this->redirect(HTTPS_SERVER . 'index.php?route=account/create');
				}
				
				if ($this->request->post['account'] == 'guest') {
					$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/guest_step_1');
				}
			}
			
			if (isset($this->request->post['email']) && isset($this->request->post['password']) && $this->validate()) {
				unset($this->session->data['guest']);
				
				if (isset($this->request->post['redirect'])) {
					$this->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
				} else {
					$this->redirect(HTTPS_SERVER . 'index.php?route=account/account');
				} 
			}
    	}  
		
      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
 
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=account/account',
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=account/login',
        	'text'      => $this->language->get('text_login'),
        	'separator' => $this->language->get('text_separator')
      	);
				
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_new_customer'] = $this->language->get('text_new_customer');
    	$this->data['text_i_am_new_customer'] = $this->language->get('text_i_am_new_customer');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
    	$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_guest'] = $this->language->get('text_guest');   	
    	$this->data['text_create_account'] = $this->language->get('text_create_account');
		$this->data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$this->data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
    	$this->data['text_forgotten_password'] = $this->language->get('text_forgotten_password');

    	$this->data['entry_email'] = $this->language->get('entry_email_address');
    	$this->data['entry_password'] = $this->language->get('entry_password');

    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_guest'] = $this->language->get('button_guest');
		$this->data['button_login'] = $this->language->get('button_login');

		if (isset($this->error['message'])) {
			$this->data['error'] = $this->error['message'];
		} else {
			$this->data['error'] = '';
		}
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=account/login';

    	if (isset($this->request->post['redirect'])) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
      		$this->data['redirect'] = $this->session->data['redirect'];
	  		
			unset($this->session->data['redirect']);		  	
    	} else {
			$this->data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
    
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (isset($this->session->data['account'])) {
			$this->data['account'] = $this->session->data['account'];
		} else {
			$this->data['account'] = 'register';
		}
		
    	$this->data['forgotten'] = HTTPS_SERVER . 'index.php?route=account/forgotten';
		$this->data['guest_checkout'] = ($this->config->get('config_guest_checkout') && $this->cart->hasProducts() && !$this->cart->hasDownload());

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/login.tpl';
		} else {
			$this->template = 'default/template/account/login.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
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