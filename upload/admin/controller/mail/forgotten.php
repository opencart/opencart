<?php
class ControllerMailForgotten extends Controller {
	public function index(&$route, &$args, &$output) {
		if ($args[0] && $args[1]) {
			$this->load->language('mail/forgotten');

			$data['text_greeting'] = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

			$data['reset'] = str_replace('&amp;', '&', $this->url->link('common/reset', 'email=' . urlencode($args[0]) . '&code=' . $args[1]));
			$data['ip'] = $this->request->server['REMOTE_ADDR'];

			$this->load->model('tool/mail');
			
			$mail_data = array(
				'to'		=> $args[0],
				'subject'	=> sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')),
				'text'		=> $this->load->view('mail/forgotten', $data)
			);
			
			$this->model_tool_mail->sendMail($mail_data);
		}
	}
}
