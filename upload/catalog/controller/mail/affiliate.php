<?php
class ControllerMailAffiliate extends Controller {
	public function index(&$route, &$args, &$output) {
		$this->load->language('mail/affiliate');

		$data['text_welcome'] = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$data['text_login'] = $this->language->get('text_login');
		$data['text_approval'] = $this->language->get('text_approval');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_thanks'] = $this->language->get('text_thanks');
		$data['button_login'] = $this->language->get('button_login');

		$this->load->model('account/customer_group');

		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $args[1]['customer_group_id'];
		}

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		if ($customer_group_info) {
			$data['approval'] = ($this->config->get('config_affiliate_approval') || $customer_group_info['approval']);
		} else {
			$data['approval'] = '';
		}

		$data['login'] = $this->url->link('affiliate/login', 'language=' . $this->config->get('config_language'));
		$data['store_url'] = HTTP_SERVER;
		$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$this->load->model('tool/image');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), $this->config->get('theme_default_image_location_width'), $this->config->get('theme_default_image_cart_height'));
		} else {
			$data['logo'] = '';
		}

		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		if ($this->customer->isLogged()) {
			$mail->setTo($this->customer->getEmail());
		} else {
			$mail->setTo($args[1]['email']);
		}

		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
		$mail->setHtml($this->load->view('mail/affiliate', $data));
		$mail->send();
 	}

	public function alert(&$route, &$args, &$output) {
		// Send to main admin email if new affiliate email is enabled
		if (in_array('affiliate', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/affiliate');

			$data['text_signup'] = $this->language->get('text_signup');
			$data['text_website'] = $this->language->get('text_website');
			$data['text_firstname'] = $this->language->get('text_firstname');
			$data['text_lastname'] = $this->language->get('text_lastname');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_telephone'] = $this->language->get('text_telephone');

			$data['login'] = $this->url->link('affiliate/login', 'language=' . $this->config->get('config_language'));
			$data['store_url'] = HTTP_SERVER;
			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$this->load->model('tool/image');

			if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
				$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), $this->config->get('theme_default_image_location_width'), $this->config->get('theme_default_image_cart_height'));
			} else {
				$data['logo'] = '';
			}
			
			if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getGroupId();

				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();
			} else {
				$customer_group_id = $args[1]['customer_group_id'];

				$data['firstname'] = $args[1]['firstname'];
				$data['lastname'] = $args[1]['lastname'];
				$data['email'] = $args[1]['email'];
				$data['telephone'] = $args[1]['telephone'];
			}

			$data['website'] = html_entity_decode($args[1]['website'], ENT_QUOTES, 'UTF-8');
			$data['company'] = $args[1]['company'];

			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_affiliate'), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($this->load->view('mail/affiliate_alert', $data));
			$mail->send();

			// Send to additional alert emails if new affiliate email is enabled
			$emails = explode(',', (array)$this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}
}
