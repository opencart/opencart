<?php
namespace Opencart\Catalog\Controller\Mail;
class Gdpr extends \Opencart\System\Engine\Controller {
	// catalog/model/account/gdpr/addGdpr
	public function index(string &$route, array &$args, mixed &$output): void {
		// $args[0] $code
		// $args[1] $email
		// $args[2] $action

		$this->load->language('mail/gdpr');

		$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		if ($this->config->get('config_logo')) {
			$data['logo'] = $this->config->get('config_url') . 'image/' . html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
		} else {
			$data['logo'] = '';
		}

		$data['text_request'] = $this->language->get('text_' . $args[2]);

		$data['button_confirm'] = $this->language->get('button_' . $args[2]);

		$data['confirm'] = $this->url->link('information/gdpr|success', 'language=' . $this->config->get('config_language') . '&code=' . $args[0], true);

		$data['ip'] = $this->request->server['REMOTE_ADDR'];

		$data['store_name'] = $store_name;
		$data['store_url'] = $this->config->get('config_url');

		if ($this->config->get('config_mail_engine')) {
			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($args[1]);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(sprintf($this->language->get('text_subject'), $store_name));
			$mail->setHtml($this->load->view('mail/gdpr', $data));
			$mail->send();
		}
	}
}
