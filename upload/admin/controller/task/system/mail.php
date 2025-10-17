<?php
namespace Opencart\Admin\Controller\Task\System;
/**
 * Class Mail
 *
 * @package Opencart\Admin\Controller\Task\System
 */
class Mail extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/system/mail');

		if (!$this->config->get('config_mail_engine')) {
			return ['error' => $this->language->get('error_engine')];
		}

		if (empty($args['to'])) {
			return ['error' => $this->language->get('error_to')];
		}

		$recipients = [];

		if (!is_array($args['to'])) {
			$recipients[] = $args['to'];
		} else {
			$recipients = $args['to'];
		}

		foreach ($recipients as $recipient) {
			if (!oc_validate_email($recipient)) {
				return ['error' => $this->language->get('error_to')];
			}
		}

		if (empty($args['from']) || !oc_validate_email($args['from'])) {
			return ['error' => $this->language->get('error_from')];
		}

		if (empty($args['sender'])) {
			return ['error' => $this->language->get('error_sender')];
		}

		if (isset($args['reply_to']) && !oc_validate_email($args['reply_to'])) {
			return ['error' => $this->language->get('error_reply_to')];
		}

		if (empty($args['subject'])) {
			return ['error' => $this->language->get('error_subject')];
		}

		if (empty($args['content'])) {
			return ['error' => $this->language->get('error_content')];
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
		$mail->setTo($recipients);
		$mail->setFrom($args['from']);
		$mail->setSender($args['sender']);
		
		if (isset($args['reply_to'])) {
			$mail->setReplyTo($args['reply_to']);
		}
		
		$mail->setSubject($args['subject']);
		$mail->setHtml($args['content']);
		$mail->send();

		return ['success' => $this->language->get('text_success')];
	}
}