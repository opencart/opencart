<?php
namespace Opencart\Admin\Controller\Mail;
class Reward extends \Opencart\System\Engine\Controller {
	public function index(string $route, array $args, mixed $output): void {
		if (isset($args[0])) {
			$customer_id = (int)$args[0];
		} else {
			$customer_id = 0;
		}

		if (isset($args[1])) {
			$description = (string)$args[1];
		} else {
			$description = '';
		}

		if (isset($args[2])) {
			$points = (int)$args[2];
		} else {
			$points = 0;
		}

		if (isset($args[3])) {
			$order_id = (int)$args[3];
		} else {
			$order_id = 0;
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if ($customer_info) {
			$this->load->language('mail/reward');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

			if ($store_info) {
				$store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');
				$store_url = $store_info['url'];
			} else {
				$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
				$store_url = HTTP_CATALOG;
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($customer_info['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			$this->language->load($language_code, 'mail', $language_code);
			$this->language->load('mail/reward', 'mail', $language_code);

			$subject = sprintf($this->language->get('mail_text_subject'), $store_name);

			$data['text_received'] = sprintf($this->language->get('mail_text_received'), $points);
			$data['text_total'] = sprintf($this->language->get('mail_text_total'), $this->model_customer_customer->getRewardTotal($customer_id));

			$data['store'] = $store_name;
			$data['store_url'] = $store_url;

			if ($this->config->get('config_mail_engine')) {
				$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($customer_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($store_name);
				$mail->setSubject($subject);
				$mail->setHtml($this->load->view('mail/reward', $data));
				$mail->send();
			}
		}
	}
}
