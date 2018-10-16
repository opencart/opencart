<?php

class ControllerMailGdpr extends Controller {
	// admin/model/customer/gdpr/approveGdpr
	public function approve(&$route, &$args, &$output) {
		$this->load->language('mail/gdpr_approve');

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_cuistomer->getCustomer($args[0]);

		if ($customer_info) {





			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->load->view('mail/gdpr_approve', $data));
			$mail->send();
		}
	}

	// admin/model/customer/gdpr/denyGdpr
	public function deny(&$route, &$args, &$output) {

		$this->load->language('mail/gdpr_deny');

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_cuistomer->getCustomer($args[0]);

		if ($customer_info) {
			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->load->view('mail/gdpr_deny', $data));
			$mail->send();
		}
	}

	// admin/model/customer/gdpr/deleteGdpr
	public function delete(&$route, &$args, &$output) {
		$this->load->language('mail/gdpr_delete');

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_cuistomer->getCustomer($args[0]);

		if ($customer_info) {
			$this->load->language('mail/gdpr_delete');

			$mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($customer_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
			$mail->setText($this->load->view('mail/gdpr_delete', $data));
			$mail->send();
		}
	}
}
