<?php
namespace Opencart\Application\Controller\Mail;
class Forgotten extends \Opencart\System\Engine\Controller {
	//catalog/model/account/customer/editCode/after
	public function index(&$route, &$args, &$output) {
		if ($args[0] && $args[1]) {
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomerByEmail($args[0]);

			if ($customer_info) {
				$this->load->language('mail/forgotten');

				$this->load->model('tool/image');

				if (is_file(DIR_IMAGE . html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8'))) {
					$data['logo'] = $this->model_tool_image->resize(html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8'), $this->config->get('theme_default_image_location_width'), $this->config->get('theme_default_image_cart_height'));
				} else {
					$data['logo'] = '';
				}

				$data['text_greeting'] = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$data['text_change'] = $this->language->get('text_change');
				$data['text_ip'] = $this->language->get('text_ip');
				$data['button_reset'] = $this->language->get('button_reset');

				$data['reset'] = str_replace('&amp;', '&', $this->url->link('account/reset', 'language=' . $this->config->get('config_language') . '&email=' . urlencode($args[0]) . '&code=' . $args[1]));
				$data['ip'] = $this->request->server['REMOTE_ADDR'];
				$data['store_url'] = $this->config->get('config_url');
				$data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

				$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($args[0]);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));
				$mail->setText($this->load->view('mail/forgotten', $data));
				$mail->send();
			}
		}
	}
}
