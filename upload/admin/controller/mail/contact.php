<?php
class ControllerMailContact extends Controller {
	public function index(&$route, &$args, &$output) {
		$json = json_decode($this->response->getOutput(), true);
		
		if (isset($json['success']) && $json['success']) {
			$message  = '<html dir="ltr" lang="' . $this->language->get('code') . '">' . "\n";
			$message .= '  <head>' . "\n";
			$message .= '    <title>' . $this->request->post['subject'] . '</title>' . "\n";
			$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
			$message .= '  </head>' . "\n";
			$message .= '  <body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
			$message .= '</html>' . "\n";
						
			$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			foreach ($json['emails'] as $email) {
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {							
					$mail->setTo($email);
					$mail->setFrom($json['store_email']);
					$mail->setSender(html_entity_decode($json['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($message);
					$mail->send();
				}
			}
			
			unset ($json['emails']);
		}
		
		$this->response->setOutput(json_encode($json));
	}
}
