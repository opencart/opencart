<?php
class ControllerTotalVoucher extends Controller {
	public function index() {
		if ($this->config->get('voucher_status')) {
			$this->load->language('total/voucher');

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_voucher'] = $this->language->get('entry_voucher');

			$data['button_voucher'] = $this->language->get('button_voucher');

			if (isset($this->session->data['voucher'])) {
				$data['voucher'] = $this->session->data['voucher'];
			} else {
				$data['voucher'] = '';
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/voucher.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/total/voucher.tpl', $data);
			} else {
				return $this->load->view('default/template/total/voucher.tpl', $data);
			}
		}
	}

	public function voucher() {
		$this->load->language('total/voucher');

		$json = array();

		$this->load->model('total/voucher');

		if (isset($this->request->post['voucher'])) {
			$voucher = $this->request->post['voucher'];
		} else {
			$voucher = '';
		}

		$voucher_info = $this->model_total_voucher->getVoucher($voucher);

		if (empty($this->request->post['voucher'])) {
			$json['error'] = $this->language->get('error_empty');
		} elseif ($voucher_info) {
			$this->session->data['voucher'] = $this->request->post['voucher'];

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('checkout/cart');
		} else {
			$json['error'] = $this->language->get('error_voucher');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function send($order_id) {
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		// If order status in the complete range create any vouchers that where in the order need to be made available.
		if (in_array($order_info['order_status_id'], $this->config->get('config_complete_status'))) {
			$voucher_query = $this->db->query("SELECT *, vtd.name AS theme FROM `" . DB_PREFIX . "voucher` v LEFT JOIN " . DB_PREFIX . "voucher_theme vt ON (v.voucher_theme_id = vt.voucher_theme_id) LEFT JOIN " . DB_PREFIX . "voucher_theme_description vtd ON (vt.voucher_theme_id = vtd.voucher_theme_id) WHERE v.order_id = '" . (int)$order_info['order_id'] . "' AND vtd.language_id = '" . (int)$order_info['language_id'] . "'");

			if ($voucher_query->num_rows) {
				// Send out any gift voucher mails
				$language = new Language($order_info['language_directory']);
				$language->load($order_info['language_directory']);
				$language->load('mail/voucher');

				foreach ($voucher_query->rows as $voucher) {
					// HTML Mail
					$data = array();

					$data['title'] = sprintf($language->get('text_subject'), $voucher['from_name']);

					$data['text_greeting'] = sprintf($language->get('text_greeting'), $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']));
					$data['text_from'] = sprintf($language->get('text_from'), $voucher['from_name']);
					$data['text_message'] = $language->get('text_message');
					$data['text_redeem'] = sprintf($language->get('text_redeem'), $voucher['code']);
					$data['text_footer'] = $language->get('text_footer');

					if (is_file(DIR_IMAGE . $voucher['image'])) {
						$data['image'] = $this->config->get('config_url') . 'image/' . $voucher['image'];
					} else {
						$data['image'] = '';
					}

					$data['store_name'] = $order_info['store_name'];
					$data['store_url'] = $order_info['store_url'];
					$data['message'] = nl2br($voucher['message']);

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/voucher.tpl')) {
						$html = $this->load->view($this->config->get('config_template') . '/template/mail/voucher.tpl', $data);
					} else {
						$html = $this->load->view('default/template/mail/voucher.tpl', $data);
					}

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

					$mail->setTo($voucher['to_email']);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
					$mail->setSubject(html_entity_decode(sprintf($language->get('text_subject'), $voucher['from_name']), ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($html);
					$mail->send();
				}
			}
		}
	}
}
