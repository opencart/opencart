<?php
class ControllerSettingMail extends Controller {
	private $error = array();
 
	public function index() { 
		$this->load->language('setting/mail'); 

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('mail', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('setting/mail'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_account_subject'] = $this->language->get('entry_account_subject');
		$this->data['entry_account_message'] = $this->language->get('entry_account_message');
		$this->data['entry_forgotten_subject'] = $this->language->get('entry_forgotten_subject');
		$this->data['entry_forgotten_message'] = $this->language->get('entry_forgotten_message');
		$this->data['entry_order_subject'] = $this->language->get('entry_order_subject');
		$this->data['entry_order_message'] = $this->language->get('entry_order_message');
		$this->data['entry_update_subject'] = $this->language->get('entry_update_subject');
		$this->data['entry_update_message'] = $this->language->get('entry_update_message');
		
		$this->data['help_account'] = $this->language->get('help_account');
		$this->data['help_forgotten'] = $this->language->get('help_forgotten');
		$this->data['help_order'] = $this->language->get('help_order');
		$this->data['help_update'] = $this->language->get('help_update');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_account'] = $this->language->get('tab_account');
		$this->data['tab_forgotten'] = $this->language->get('tab_forgotten');
		$this->data['tab_order'] = $this->language->get('tab_order');
		$this->data['tab_update'] = $this->language->get('tab_update');

		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		 
		foreach ($languages as $language) {
			$this->data['error_account_subject_' . $language['language_id']] = @$this->error['account_subject_' . $language['language_id']];
			$this->data['error_account_message_' . $language['language_id']] = @$this->error['account_message_' . $language['language_id']];
			$this->data['error_forgotten_subject_' . $language['language_id']] = @$this->error['forgotten_subject_' . $language['language_id']];
			$this->data['error_forgotten_message_' . $language['language_id']] = @$this->error['forgotten_message_' . $language['language_id']];
			$this->data['error_order_subject_' . $language['language_id']] = @$this->error['order_subject_' . $language['language_id']];
			$this->data['error_order_message_' . $language['language_id']] = @$this->error['order_message_' . $language['language_id']];
			$this->data['error_update_subject_' . $language['language_id']] = @$this->error['update_subject_' . $language['language_id']];
			$this->data['error_update_message_' . $language['language_id']] = @$this->error['update_message_' . $language['language_id']];
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('setting/mail'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);
		
		$this->data['action'] = $this->url->https('setting/mail');
		
		$this->data['cancel'] = $this->url->https('setting/mail');

		foreach ($languages as $language) {
			if (isset($this->request->post['mail_account_subject_' . $language['language_id']])) {
				$this->data['mail_account_subject_' . $language['language_id']] = $this->request->post['mail_account_subject_' . $language['language_id']];
			} else {
				$this->data['mail_account_subject_' . $language['language_id']] = $this->config->get('mail_account_subject_' . $language['language_id']);
			}
			
			if (isset($this->request->post['mail_account_message_' . $language['language_id']])) {
				$this->data['mail_account_message_' . $language['language_id']] = $this->request->post['mail_account_message_' . $language['language_id']];
			} else {
				$this->data['mail_account_message_' . $language['language_id']] = $this->config->get('mail_account_message_' . $language['language_id']);
			}	
			
			if (isset($this->request->post['mail_forgotten_subject_' . $language['language_id']])) {
				$this->data['mail_forgotten_subject_' . $language['language_id']] = $this->request->post['mail_forgotten_subject_' . $language['language_id']];
			} else {
				$this->data['mail_forgotten_subject_' . $language['language_id']] = $this->config->get('mail_forgotten_subject_' . $language['language_id']);
			}
			
			if (isset($this->request->post['mail_forgotten_message_' . $language['language_id']])) {
				$this->data['mail_forgotten_message_' . $language['language_id']] = $this->request->post['mail_forgotten_message_' . $language['language_id']];
			} else {
				$this->data['mail_forgotten_message_' . $language['language_id']] = $this->config->get('mail_forgotten_message_' . $language['language_id']);
			}				

			if (isset($this->request->post['mail_order_subject_' . $language['language_id']])) {
				$this->data['mail_order_subject_' . $language['language_id']] = $this->request->post['mail_order_subject_' . $language['language_id']];
			} else {
				$this->data['mail_order_subject_' . $language['language_id']] = $this->config->get('mail_order_subject_' . $language['language_id']);
			}
			
			if (isset($this->request->post['mail_order_message_' . $language['language_id']])) {
				$this->data['mail_order_message_' . $language['language_id']] = $this->request->post['mail_order_message_' . $language['language_id']];
			} else {
				$this->data['mail_order_message_' . $language['language_id']] = $this->config->get('mail_order_message_' . $language['language_id']);
			}
			
			if (isset($this->request->post['mail_update_subject_' . $language['language_id']])) {
				$this->data['mail_update_subject_' . $language['language_id']] = $this->request->post['mail_update_subject_' . $language['language_id']];
			} else {
				$this->data['mail_update_subject_' . $language['language_id']] = $this->config->get('mail_update_subject_' . $language['language_id']);
			}
			
			if (isset($this->request->post['mail_update_message_' . $language['language_id']])) {
				$this->data['mail_update_message_' . $language['language_id']] = $this->request->post['mail_update_message_' . $language['language_id']];
			} else {
				$this->data['mail_update_message_' . $language['language_id']] = $this->config->get('mail_update_message_' . $language['language_id']);
			}
		}
		
		$this->data['languages'] = $languages;
		
		$this->id       = 'content'; 
		$this->template = 'setting/mail.tpl';
		$this->layout   = 'module/layout';
				
		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'setting/mail')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
			if (!$this->request->post['mail_account_subject_' . $language['language_id']]) {
				$this->error['account_subject_' . $language['language_id']] = $this->language->get('error_account_subject');
			}
			
			if (!$this->request->post['mail_account_message_' . $language['language_id']]) {
				$this->error['account_message_' . $language['language_id']] = $this->language->get('error_account_message');
			}	
			
			if (!$this->request->post['mail_forgotten_subject_' . $language['language_id']]) {
				$this->error['forgotten_subject_' . $language['language_id']] = $this->language->get('error_forgotten_subject');
			}
			
			if (!$this->request->post['mail_forgotten_message_' . $language['language_id']]) {
				$this->error['forgotten_message_' . $language['language_id']] = $this->language->get('error_forgotten_message');
			}				

			if (!$this->request->post['mail_order_subject_' . $language['language_id']]) {
				$this->error['order_subject_' . $language['language_id']] = $this->language->get('error_order_subject');
			}
			
			if (!$this->request->post['mail_order_message_' . $language['language_id']]) {
				$this->error['order_message_' . $language['language_id']] = $this->language->get('error_order_message');
			}
			
			if (!$this->request->post['mail_update_subject_' . $language['language_id']]) {
				$this->error['update_subject_' . $language['language_id']] = $this->language->get('error_update_subject');
			}
			
			if (!$this->request->post['mail_update_message_' . $language['language_id']]) {
				$this->error['update_message_' . $language['language_id']] = $this->language->get('error_update_message');
			}		
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>