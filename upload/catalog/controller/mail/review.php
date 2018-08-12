<?php
class ControllerMailReview extends Controller {
	public function index(&$route, &$args, &$output) {
		if (in_array('review', (array)$this->config->get('config_mail_alert'))) {
			$this->load->language('mail/review');

			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct((int)$args[0]);

			if ($product_info) {
				$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));

				$data['text_waiting'] = $this->language->get('text_waiting');

				$data['text_product'] = $this->language->get('text_product');
				$data['text_reviewer'] = $this->language->get('text_reviewer');
				$data['text_rating'] = $this->language->get('text_rating');
				$data['text_review'] = $this->language->get('text_review');

				$data['product'] = html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8');
				$data['reviewer'] = html_entity_decode($args[1]['name'], ENT_QUOTES, 'UTF-8');
				$data['rating'] = (int)$args[1]['rating'];
				$data['text'] = $args[1]['text'];

				$mail = new Mail($this->config->get('config_mail_engine'));
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setText($this->load->view('mail/review', $data));
				$mail->send();

				// Send to additional alert emails
				$emails = explode(',', (string)$this->config->get('config_mail_alert_email'));

				foreach ($emails as $email) {
					if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$mail->setTo($email);
						$mail->send();
					}
				}
			}
		}
	}
}
