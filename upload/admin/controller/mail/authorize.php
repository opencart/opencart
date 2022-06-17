<?php
namespace Opencart\Admin\Controller\Mail;
class Authorize extends Controller {
	public function index(&$route, &$args, &$output) {
		if (isset($args[0])) {
			$email = urldecode((string)$args[0]);
		} else {
			$email = '';
		}

		if (isset($args[1])) {
			$code = $args[1];
		} else {
			$code = '';
		}

		if ($code && (isset($this->request->get['route']) && $this->request->get['route'] == 'account/security/reset') && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->load->model('user/user');

			$data['username'] = $this->user->getUsername();
			$data['reset'] = $this->url->link('common/authorize|confirm', 'email=' . urlencode($email) . '&code=' . $code, true);
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			$data['store'] = $this->config->get('config_store');

			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($email);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($this->language->get('text_subject'));
			$mail->setText($this->load->view('mail/authorize', $data));
			$mail->send();
		}
	}
}
