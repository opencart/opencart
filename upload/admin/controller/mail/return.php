<?php
class ControllerMailReturn extends Controller {
	public function index($route, $args, $output) {
		if (isset($args[0])) {
			$return_id = $args[0];
		} else {
			$return_id = '';
		}
		
		if (isset($args[1])) {
			$return_status_id = $args[1];
		} else {
			$return_status_id = '';
		}		
		
		if (isset($args[2])) {
			$comment = $args[2];
		} else {
			$comment = '';
		}
		
		if (isset($args[3])) {
			$notify = $args[3];
		} else {
			$notify = '';
		}		
		
		if ($notify) {
			$this->load->model('sale/return');
			
			$return_info = $this->model_sale_return->getReturn($return_id);
			
			if ($return_info) {                
				$language_info = $this->model_localisation_language->getLanguage($return_info['language_id']);
                
				if ($language_info) {
					$language_code = $language_info['code'];
				} else {
					$language_code = $this->config->get('config_language');
				}
                
				$language = new Language($language_code);
				$language->load($language_code);
				$language->load('mail/return');

				$data['return_id'] = $return_id;
				$data['date_added'] = date($language->get('date_format_short'), strtotime($return_info['date_modified']));
				$data['return_status'] = $return_info['return_status'];
				$data['comment'] = strip_tags(html_entity_decode($comment, ENT_QUOTES, 'UTF-8'));

				$this->load->model('tool/mail');
				
				$mail_data = array(
					'to'		=> $return_info['email'],
					'subject'	=> sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')),
					'text'		=> $this->load->view('mail/return', $data)
				);
				
				$this->model_tool_mail->sendMail($mail_data);
			}
		}
	}
}	