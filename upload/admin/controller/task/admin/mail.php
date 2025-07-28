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
	public function index(array $argv = []): array {
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
			$mail->setTo($argv['email']);
			$mail->setFrom($argv['from']);
			$mail->setSender($argv['sender']);
			$mail->setSubject($argv['subject']);
			$mail->setHtml($argv['content']);
			$mail->send();

			// Send to additional alert emails if new affiliate email is enabled
			$emails = explode(',', (string)$this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if (oc_strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo(trim($email));
					$mail->send();
				}
			}
		}

	}
}