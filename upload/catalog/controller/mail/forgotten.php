<?php
namespace Opencart\Catalog\Controller\Mail;
/**
 * Class Forgotten
 *
 * @package Opencart\Catalog\Controller\Mail
 */
class Forgotten extends \Opencart\System\Engine\Controller {
	// catalog/model/account/customer/editCode/after
	/**
	 * Index
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		if (isset($args[0])) {
			$email = urldecode((string)$args[0]);
		} else {
			$email = '';
		}

		if (isset($args[1])) {
			$code = (string)$args[1];
		} else {
			$code = '';
		}

		if ($email && $code && ($route == 'account/forgotten.confirm') && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomerByEmail($args[0]);

			if ($customer_info) {
				$this->load->language('mail/forgotten');

				$store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

				$subject = sprintf($this->language->get('text_subject'), $store_name);

				$data['text_greeting'] = sprintf($this->language->get('text_greeting'), $store_name);

				$data['reset'] = $this->url->link('account/forgotten.reset', 'language=' . $this->config->get('config_language') . '&email=' . urlencode($args[0]) . '&code=' . $args[1], true);
				$data['ip'] = oc_get_ip();

				$data['store'] = $store_name;
				$data['store_url'] = $this->config->get('config_url');

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
					$mail->setTo($args[0]);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($store_name);
					$mail->setSubject($subject);
					$mail->setHtml($this->load->view('mail/forgotten', $data));
					$mail->send();
				}
			}
		}
	}
}
