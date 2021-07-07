<?php
namespace Opencart\Admin\Controller\Mail;
class Forgotten extends \Opencart\System\Engine\Controller {
	// admin/model/user/user/editCode/after
	public function index(string &$route, array &$args, mixed &$output): void {
		if (isset($args[0]) && isset($args[1]) && $args[0] && $args[1]) {
			$this->load->language('mail/forgotten');

			$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$subject = sprintf($this->language->get('text_subject'), $store_name);

			$data['text_greeting'] = sprintf($this->language->get('text_greeting'), $store_name);

			$data['reset'] = $this->url->link('common/reset', 'email=' . urlencode($args[0]) . '&code=' . $args[1], true);
			$data['ip'] = $this->request->server['REMOTE_ADDR'];

			$data['store'] = $store_name;
			$data['store_url'] = $this->config->get('config_store_url');

			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($args[0]);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject($subject);
			$mail->setHtml($this->load->view('mail/forgotten', $data));
			$mail->send();
		}
	}
}
