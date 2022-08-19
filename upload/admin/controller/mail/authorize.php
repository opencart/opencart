<?php
namespace Opencart\Admin\Controller\Mail;
class Authorize extends \Opencart\System\Engine\Controller {
	// admin/model/user/user/editCode/after
	public function index(&$route, &$args, &$output) {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		$email = $this->user->getEmail();

		if (isset($this->session->data['code'])) {
			$code = $this->session->data['code'];
		} else {
			$code = '';
		}

		if ($email && $code && ($route == 'common/authorize|send') && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->load->language('mail/authorize');

			$data['username'] = $this->user->getUsername();
			$data['code'] = $code;
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			if ($this->config->get('config_mail_engine')) {
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

	// admin/model/user/user/editCode/after
	public function reset(&$route, &$args, &$output) {
		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		} else {
			$route = '';
		}

		if (isset($args[0])) {
			$email = (string)$args[0];
		} else {
			$email = '';
		}

		if (isset($args[1])) {
			$code = (string)$args[1];
		} else {
			$code = '';
		}

		if ($email && $code && ($route == 'common/authorize|confirm') && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->load->language('mail/authorize_reset');

			$data['username'] = $this->user->getUsername();
			$data['reset'] = $this->url->link('common/authorize|reset', 'email=' . $email . '&code=' . $code, true);
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			if ($this->config->get('config_mail_engine')) {
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
				$mail->setText($this->load->view('mail/authorize_reset', $data));
				$mail->send();
			}
		}
	}
}
