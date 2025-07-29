<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Task\System
 */
class Mail extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 *
	 */
	public function index(array $arg = []): array {
		if (!$this->config->get('config_mail_engine')) {
			return;
		}

		$email = trim($arg['email']);

		if (!oc_validate_email($email)) {
			return;
		}

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
		$mail->setFrom($arg['from']);
		$mail->setSender($arg['sender']);
		$mail->setSubject($arg['subject']);
		$mail->setHtml($arg['content']);
		$mail->send();
	}
}