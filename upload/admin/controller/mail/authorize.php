<?php
namespace Opencart\Admin\Controller\Mail;
/**
 * Class Authorize
 *
 * @package Opencart\Admin\Controller\Mail
 */
class Authorize extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param array<mixed>      $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 *
	 * admin/controller/common/authorize.send/after
	 */
	public function index(string &$route, array &$args, &$output): void {
		$email = $this->user->getEmail();

		if (isset($this->session->data['code'])) {
			$code = $this->session->data['code'];
		} else {
			$code = '';
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUser($this->user->getId());

		if ($code && $user_info) {
			$this->load->language('mail/authorize');

			$data['username'] = $this->user->getUsername();
			$data['code'] = $code;
			$data['ip'] = oc_get_ip();
			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			if ($this->config->get('config_mail_engine')) {
				$mail_option = [
					'parameter'     => $this->config->get('config_mail_parameter'),
					'smtp_hostname' => $this->config->get('config_mail_smtp_hostname'),
					'smtp_username' => $this->config->get('config_mail_smtp_username'),
					'smtp_password' => html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
					'smtp_port'     => $this->config->get('config_mail_smtp_port'),
					'smtp_timeout'  => $this->config->get('config_mail_smtp_timeout')
				];

				$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'), $mail_option);
				$mail->setTo($this->user->getEmail());
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($this->language->get('text_subject'));
				$mail->setText($this->load->view('mail/authorize', $data));
				$mail->send();
			}
		}
	}

	/**
	 * Reset
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param array<mixed>      $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 *
	 * admin/model/user/user.addToken/after
	 */
	public function reset(&$route, &$args, &$output): void {
		if (isset($args[0])) {
			$user_id = (int)$args[0];
		} else {
			$user_id = 0;
		}

		if (isset($args[1])) {
			$type = (string)$args[1];
		} else {
			$type = '';
		}

		if (isset($args[2])) {
			$code = (string)$args[2];
		} else {
			$code = '';
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUser($user_id);

		if ($type == 'authorize' && $user_info) {
			$this->load->language('mail/authorize_reset');

			$data['username'] = $this->user->getUsername();
			$data['reset'] = $this->url->link('common/authorize.reset', 'email=' . $email . '&code=' . $code, true);
			$data['ip'] = oc_get_ip();
			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			if ($this->config->get('config_mail_engine')) {
				$mail_option = [
					'parameter'     => $this->config->get('config_mail_parameter'),
					'smtp_hostname' => $this->config->get('config_mail_smtp_hostname'),
					'smtp_username' => $this->config->get('config_mail_smtp_username'),
					'smtp_password' => html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
					'smtp_port'     => $this->config->get('config_mail_smtp_port'),
					'smtp_timeout'  => $this->config->get('config_mail_smtp_timeout')
				];

				$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'), $mail_option);
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
