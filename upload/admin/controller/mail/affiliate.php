<?php
class ControllerMailAffiliate extends Controller {
	public function approve(&$route, &$args, &$output) {
		$this->load->model('customer/customer');
		
		$customer_info = $this->model_customer_customer->getCustomer($args[0]);

		if ($customer_info) {
			$this->load->model('setting/store');
	
			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);
	
			if ($store_info) {
				$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
				$store_url = $store_info['url'] . 'index.php?route=account/login';
			} else {
				$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
				$store_url = HTTP_CATALOG . 'index.php?route=account/login';
			}

			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($customer_info['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}
			
			$language = new Language($language_code);
			$language->load($language_code);
			$language->load('mail/affiliate_approve');
			
			$data['text_welcome'] = sprintf($language->get('text_welcome'), $store_name);

			$data['login'] = $store_url . 'index.php?route=account/login';
			$data['store'] = $store_name;
			
			$this->load->model('tool/mail');
			
			$mail_data = array(
				'to'		=> $customer_info['email'],
				'sender'	=> $store_name,
				'subject'	=> sprintf($language->get('text_subject'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')),
				'text'		=> $this->load->view('mail/affiliate_approve', $data)
			);
			
			$this->model_tool_mail->sendMail($mail_data);
		}
	}
	
	public function deny(&$route, &$args, &$output) {
		$this->load->model('customer/customer');
		
		$customer_info = $this->model_customer_customer->getCustomer($args[0]);

		if ($customer_info) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
				$store_url = $store_info['url'] . 'index.php?route=account/login';
			} else {
				$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
				$store_url = HTTP_CATALOG . 'index.php?route=account/login';
			}

			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($customer_info['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			$language = new Language($language_code);
			$language->load($language_code);
			$language->load('mail/affiliate_deny');

			$data['text_welcome'] = sprintf($language->get('text_welcome'), $store_name);
			
			$data['contact'] = $store_url . 'index.php?route=information/contact';	
			$data['store'] = $store_name;
			
			$this->load->model('tool/mail');
			
			$mail_data = array(
				'to'		=> $customer_info['email'],
				'sender'	=> $store_name,
				'subject'	=> sprintf($language->get('text_subject'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')),
				'text'		=> $this->load->view('mail/affiliate_deny', $data)
			);
			
			$this->model_tool_mail->sendMail($mail_data);
		}
	}
}	