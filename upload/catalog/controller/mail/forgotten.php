<?php
class ControllerMailForgotten extends Controller {
	public function index(&$route, &$args, &$output) {
		if ($args[0] && $args[1]) {
			$this->load->language('mail/forgotten');

			$data['text_greeting'] = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$data['text_change'] = $this->language->get('text_change');
			$data['text_ip'] = $this->language->get('text_ip');
			$data['button_reset'] = $this->language->get('button_reset');

			$data['reset'] = str_replace('&amp;', '&', $this->url->link('account/reset', 'language=' . $this->config->get('config_language') . '&email=' . urlencode($args[0]) . '&code=' . $args[1]));
			$data['ip'] = $this->request->server['REMOTE_ADDR'];
			$data['store_url'] = HTTP_SERVER;
			$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			$this->load->model('tool/image');

			if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
				$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), $this->config->get('theme_default_image_location_width'), $this->config->get('theme_default_image_cart_height'));
			} else {
				$data['logo'] = '';
			}
			
			$this->load->model('tool/mail');
			
			$mail_data = array(
				'to'		=> $args[0],
				'subject'	=> sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')),
				'text'		=> $this->load->view('mail/forgotten', $data)
			);
			
			$this->model_tool_mail->sendMail($mail_data);
		}
	}
}
