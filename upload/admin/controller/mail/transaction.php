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
						
		$customer_info = $this->getCustomer($customer_id);

		if ($customer_info) {
			$this->load->language('mail/customer');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}

			$data['text_received'] = sprintf($this->language->get('text_received'), $this->currency->format($amount, $this->config->get('config_currency')));
			$data['text_total'] = sprintf($this->language->get('text_total'), $this->currency->format($this->getTransactionTotal($customer_id), $this->session->data['currency']));

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
			$mail->setText($message);
			$mail->send();
		}
	}		
}	