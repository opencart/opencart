<?php
class ControllerCommonReset extends Controller {
	private $error = array();
	
	public function index() {
		if ($this->user->isLogged()) {
			$this->redirect($this->url->link('common/home', '', 'SSL'));
		}
				
		if (!$this->config->get('config_password')) {
			$this->redirect($this->url->link('common/login', '', 'SSL'));
		}
						
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		
		$this->load->model('user/user');
		
		$user_info = $this->model_user_user->getUserByCode($code);
		
		if ($user_info) {
			$this->language->load('common/reset');
			
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$this->model_user_user->editPassword($user_info['user_id'], $this->request->post['password']);
	 
				$this->session->data['success'] = $this->language->get('text_success');
		  
				$this->redirect($this->url->link('common/login', '', 'SSL'));
			}
			
			$this->data['breadcrumbs'] = array();
	
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),        	
				'separator' => false
			); 
			
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_reset'),
				'href'      => $this->url->link('common/reset', '', 'SSL'),       	
				'separator' => $this->language->get('text_separator')
			);
			
			$this->data['heading_title'] = $this->language->get('heading_title');
	
			$this->data['text_password'] = $this->language->get('text_password');
	
			$this->data['entry_password'] = $this->language->get('entry_password');
			$this->data['entry_confirm'] = $this->language->get('entry_confirm');
	
			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
	
			if (isset($this->error['password'])) { 
				$this->data['error_password'] = $this->error['password'];
			} else {
				$this->data['error_password'] = '';
			}
	
			if (isset($this->error['confirm'])) { 
				$this->data['error_confirm'] = $this->error['confirm'];
			} else {
				$this->data['error_confirm'] = '';
			}
			
			$this->data['action'] = $this->url->link('common/reset', 'code=' . $code, 'SSL');
	 
			$this->data['cancel'] = $this->url->link('common/login', '', 'SSL');
			
			if (isset($this->request->post['password'])) {
				$this->data['password'] = $this->request->post['password'];
			} else {
				$this->data['password'] = '';
			}
	
			if (isset($this->request->post['confirm'])) {
				$this->data['confirm'] = $this->request->post['confirm'];
			} else {
				$this->data['confirm'] = '';
			}
			
			$this->template = 'common/reset.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);
									
			$this->response->setOutput($this->render());						
		} else {
			$this->model_setting_setting->editSettingValue('config', 'config_password', '0');
			
			return $this->forward('common/login');
		}
	}

	protected function validate() {
    	if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}  

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>