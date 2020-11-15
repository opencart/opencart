<?php
namespace Opencart\Application\Controller\Mail;
class Voucher extends \Opencart\System\Engine\Controller {
	public function index(&$route, &$args, &$output) {
		$this->load->language('mail/voucher');
		
		$json = json_decode($this->response->getOutput(), true);
		
		if (!$json) {
			$vouchers = [];

			if (isset($this->request->post['selected'])) {
				$vouchers = $this->request->post['selected'];
			} elseif (isset($this->request->post['voucher_id'])) {
				$vouchers[] = $this->request->post['voucher_id'];
			}

			if ($vouchers) {
				$this->load->model('sale/order');
				$this->load->model('sale/voucher');
				$this->load->model('sale/voucher_theme');
				
				// Mail
				$mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				foreach ($vouchers as $voucher_id) {
					$voucher_info = $this->model_sale_voucher->getVoucher($voucher_id);
			
					if ($voucher_info) {
						if ($voucher_info['order_id']) {
							$order_id = $voucher_info['order_id'];
						} else {
							$order_id = 0;
						}
			
						$order_info = $this->model_sale_order->getOrder($order_id);
			
						// If voucher belongs to an order
						if ($order_info) {
							$language = new \Opencart\Engine\Library\Language($order_info['language_code']);
							$language->load($order_info['language_code']);
							$language->load('mail/voucher');
			
							// HTML Mail
							$data['title'] = sprintf($language->get('text_subject'), $voucher_info['from_name']);
			
							$data['text_greeting'] = sprintf($language->get('text_greeting'), $this->currency->format($voucher_info['amount'], (!empty($order_info['currency_code']) ? $order_info['currency_code'] : $this->config->get('config_currency')), (!empty($order_info['currency_value']) ? $order_info['currency_value'] : $this->currency->getValue($this->config->get('config_currency')))));
							$data['text_from'] = sprintf($language->get('text_from'), $voucher_info['from_name']);
							$data['text_message'] = $language->get('text_message');
							$data['text_redeem'] = sprintf($language->get('text_redeem'), $voucher_info['code']);
							$data['text_footer'] = $language->get('text_footer');
			
							$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);
			
							if ($voucher_theme_info && is_file(DIR_IMAGE . $voucher_theme_info['image'])) {
								$data['image'] = HTTP_CATALOG . 'image/' . $voucher_theme_info['image'];
							} else {
								$data['image'] = '';
							}
			
							$data['store_name'] = $order_info['store_name'];
							$data['store_url'] = $order_info['store_url'];
							$data['message'] = nl2br($voucher_info['message']);
			
							$mail->setTo($voucher_info['to_email']);
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(sprintf($language->get('text_subject'), html_entity_decode($voucher_info['from_name'], ENT_QUOTES, 'UTF-8')));
							$mail->setHtml($this->load->view('mail/voucher', $data));
							$mail->send();
			
						// If voucher does not belong to an order
						} else {
							$this->language->load('mail/voucher');

							$data['title'] = sprintf($this->language->get('text_subject'), $voucher_info['from_name']);
			
							$data['text_greeting'] = sprintf($this->language->get('text_greeting'), $this->currency->format($voucher_info['amount'], $this->config->get('config_currency')));
							$data['text_from'] = sprintf($this->language->get('text_from'), $voucher_info['from_name']);
							$data['text_message'] = $this->language->get('text_message');
							$data['text_redeem'] = sprintf($this->language->get('text_redeem'), $voucher_info['code']);
							$data['text_footer'] = $this->language->get('text_footer');
			
							$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);
			
							if ($voucher_theme_info && is_file(DIR_IMAGE . $voucher_theme_info['image'])) {
								$data['image'] = HTTP_CATALOG . 'image/' . $voucher_theme_info['image'];
							} else {
								$data['image'] = '';
							}
			
							$data['store_name'] = $this->config->get('config_name');
							$data['store_url'] = HTTP_CATALOG;
							$data['message'] = nl2br($voucher_info['message']);
			
							$mail->setTo($voucher_info['to_email']);
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), $voucher_info['from_name']), ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($this->load->view('mail/voucher', $data));
							$mail->send();
						}
					}
				}

				$json['success'] = $this->language->get('text_sent');
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
}
