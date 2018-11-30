<?php
class ControllerMailGdpr extends Controller {
	// catalog/model/account/gdpr/addGdpr
	public function index(&$route, &$args, &$output) {
		// $args[0] $code
		// $args[1] $email
		// $args[2] $action

		$this->load->language('mail/gdpr_' . $args[2]);

		if (is_file(DIR_IMAGE . html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8'))) {
			$data['logo'] = $this->model_tool_image->resize(html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8'), $this->config->get('theme_default_image_location_width'), $this->config->get('theme_default_image_cart_height'));
		} else {
			$data['logo'] = '';
		}

		$data['confirm'] = $this->url->link('information/gdpr/success', 'language=' . $this->config->get('config_language') . '&code=' . $args[0]);

		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		$data['store_url'] = $this->config->get('config_url');
		$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($args[1]);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')));
		$mail->setHtml($this->load->view('mail/gdpr', $data));
		$mail->send();
	}
}
