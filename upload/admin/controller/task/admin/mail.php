<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Mail
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
	public function index(array $args = []): array {
		if (!$this->config->get('config_mail_engine')) {
			return;
		}

		$email = trim($args['email']);

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
		$mail->setFrom($args['from']);
		$mail->setSender($args['sender']);
		
		if (isset($args['reply_to'])) {
			$mail->setReplyTo($args['reply_to']);
		}
		
		$mail->setSubject($args['subject']);
		$mail->setHtml($args['content']);
		$mail->send();
	}
}