<?php
namespace Opencart\Application\Controller\Mail;
class Forgotten extends \Opencart\System\Engine\Controller {
	public function index(&$route, &$args, &$output) {
		if (isset($args[0]) && isset($args[1]) && $args[0] && $args[1]) {
			$this->load->language('mail/forgotten');

			$subject = html_entity_decode(sprintf($this->language->get('text_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8');

			$data['text_greeting'] = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$data['reset'] = $this->url->link('common/reset', 'email=' . urlencode($args[0]) . '&code=' . $args[1], true);
			$data['ip'] = $this->request->server['REMOTE_ADDR'];

			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($args[0]);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($this->load->view('mail/forgotten', $data));
			$mail->send();
		}
	}
}
