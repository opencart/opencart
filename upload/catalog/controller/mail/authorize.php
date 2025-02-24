<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Authorize
 *
 * @package Opencart\Catalog\Controller\Mail
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
	 * catalog/model/account/customer/editCode/after
	 */
	public function index(string &$route, array &$args, &$output): void {
		$email = $this->customer->getEmail();

		if (isset($this->session->data['code'])) {
			$code = $this->session->data['code'];
		} else {
			$code = '';
		}

		if ($email && $code && ((string)$this->request->get['route'] == 'account/authorize.send') && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->load->language('mail/authorize');

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
				$mail->setTo($email);
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
	 * catalog/model/account/customer/editCode/after
	 */
	public function reset(&$route, &$args, &$output): void {
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

		if ($email && $code && ($this->request->get['route'] == 'account/authorize.confirm') && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->load->language('mail/authorize_reset');

			$data['reset'] = $this->url->link('account/authorize.reset', 'email=' . $email . '&code=' . $code, true);
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
