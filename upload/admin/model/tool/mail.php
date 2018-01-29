<?php
class ModelToolMail extends Model {
	public function sendMail($data) {
		
		$defaults = array(
			'config_mail_engine'		=> $this->config->get('config_mail_engine'),
			'config_mail_parameter'		=> $this->config->get('config_mail_parameter'),
			'config_mail_smtp_hostname'	=> $this->config->get('config_mail_smtp_hostname'),
			'config_mail_smtp_username'	=> $this->config->get('config_mail_smtp_username'),
			'config_mail_smtp_password'	=> html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8'),
			'config_mail_smtp_port'		=> $this->config->get('config_mail_smtp_port'),
			'config_mail_smtp_timeout'	=> $this->config->get('config_mail_smtp_timeout'),
			'to'						=> $this->config->get('config_email'),
			'from'						=> $this->config->get('config_email'),
			'alerts'					=> false,
			'reply_to'					=> false,
			'sender'					=> $this->config->get('config_name'),
			'subject'					=> false,
			'text'						=> false,
			'html'						=> false,
			'attachments'				=> array(),
		);
		
		foreach($defaults as $k => $v) {
			if (!isset($data[$k])) {
				$data[$k] = $v;
			}
		}
		
		if (strstr($this->config->get('config_mail_smtp_hostname'),'@') && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$data['from'] = $this->config->get('config_mail_smtp_hostname');
			if (!$data['reply_to']) {
				$data['reply_to'] = $data['from'];
			}
		}
		
		$mail = new Mail($data['config_mail_engine']);
		$mail->parameter = $data['config_mail_parameter'];
		$mail->smtp_hostname = $data['config_mail_smtp_hostname'];
		$mail->smtp_username = $data['config_mail_smtp_username'];
		$mail->smtp_password = html_entity_decode($data['config_mail_smtp_password'], ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $data['config_mail_smtp_port'];
		$mail->smtp_timeout = $data['config_mail_smtp_timeout'];

		$mail->setTo($data['config_email']);
		$mail->setFrom($data['config_email']);
		
		if ($data['reply_to']) {
			$mail->setReplyTo($data['reply_to']);
		}
		
		$mail->setSender(html_entity_decode($data['sender'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($data['subject'], ENT_QUOTES, 'UTF-8'));
		
		if ($data['text']) {
			$mail->setText($data['text']);
		}
		
		if ($data['html']) {
			$mail->setHtml($data['html']);
		}
		
		if ($data['attachments']) {
			foreach($data['attachments'] as $attachment) {
				$mail->addAttachment($attachment);
			}
		}
		
		$mail->send();
		
		if ($data['alerts']) {
			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}
}
