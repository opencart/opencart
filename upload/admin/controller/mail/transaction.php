<?php
class ControllerMailTransaction extends Controller {
	public function index($route, $args, $output) {
		if (isset($args[0])) {
			$customer_id = $args[0];
		} else {
			$customer_id = '';
		}
		
		if (isset($args[1])) {
			$description = $args[1];
		} else {
			$description = '';
		}		
		
		if (isset($args[2])) {
			$amount = $args[2];
		} else {
			$amount = '';
		}
		
		if (isset($args[3])) {
			$order_id = $args[3];
		} else {
			$order_id = '';
		}
			
		$this->load->model('customer/customer');
						
		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if ($customer_info) {
			$this->load->language('mail/transaction');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
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
			$language->load('mail/transaction');

			$data['text_received'] = sprintf($language->get('text_received'), $this->currency->format($amount, $this->config->get('config_currency')));
			$data['text_total'] = sprintf($language->get('text_total'), $this->currency->format($this->model_customer_customer->getTransactionTotal($customer_id), $this->config->get('config_currency')));
			
			$this->load->model('tool/mail');
			
			$mail_data = array(
				'to'		=> $customer_info['email'],
				'sender'	=> $store_name,
				'subject'	=> sprintf($language->get('text_subject'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')),
				'text'		=> $this->load->view('mail/transaction', $data)
			);
			
			$this->model_tool_mail->sendMail($mail_data);
		}
	}		
}	
