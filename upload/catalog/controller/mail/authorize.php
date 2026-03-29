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
	 * catalog/controller/account/authorize.send/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param array<mixed>      $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, mixed &$output): void {
		if (isset($this->session->data['code'])) {
			$code = (string)$this->session->data['code'];
		} else {
			$code = '';
		}

		// Customer
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

		if ($code && $customer_info) {
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
				$mail->setTo($this->customer->getEmail());
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($this->language->get('text_subject'));
				$mail->setHtml($this->load->view('mail/authorize', $data));
				$mail->send();
			}
		}
	}

	/**
	 * Reset
	 *
	 * catalog/model/account/customer.addToken/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param array<mixed>      $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function reset(string &$route, array &$args, mixed &$output): void {
		if (isset($args[0])) {
			$customer_id = (int)$args[0];
		} else {
			$customer_id = 0;
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

		// Customer
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomer($customer_id);

		if ($type == 'authorize' && $customer_info) {
			$this->load->language('mail/authorize_reset');

			$data['reset'] = $this->url->link('account/authorize.unlock', 'email=' . $customer_info['email'] . '&code=' . $code, true);
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
				$mail->setTo($customer_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($this->language->get('text_subject'));
				$mail->setHtml($this->load->view('mail/authorize_reset', $data));
				$mail->send();
			}
		}
	}
}
