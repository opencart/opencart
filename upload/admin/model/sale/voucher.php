<?php
class ModelSaleVoucher extends Model {
	public function addVoucher($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "voucher SET code = '" . $this->db->escape($data['code']) . "', from_name = '" . $this->db->escape($data['from_name']) . "', from_email = '" . $this->db->escape($data['from_email']) . "', to_name = '" . $this->db->escape($data['to_name']) . "', to_email = '" . $this->db->escape($data['to_email']) . "', voucher_theme_id = '" . (int)$data['voucher_theme_id'] . "', message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

		return $this->db->getLastId();
	}

	public function editVoucher($voucher_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "voucher SET code = '" . $this->db->escape($data['code']) . "', from_name = '" . $this->db->escape($data['from_name']) . "', from_email = '" . $this->db->escape($data['from_email']) . "', to_name = '" . $this->db->escape($data['to_name']) . "', to_email = '" . $this->db->escape($data['to_email']) . "', voucher_theme_id = '" . (int)$data['voucher_theme_id'] . "', message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', status = '" . (int)$data['status'] . "' WHERE voucher_id = '" . (int)$voucher_id . "'");
	}

	public function deleteVoucher($voucher_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher WHERE voucher_id = '" . (int)$voucher_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_history WHERE voucher_id = '" . (int)$voucher_id . "'");
	}

	public function getVoucher($voucher_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "voucher WHERE voucher_id = '" . (int)$voucher_id . "'");

		return $query->row;
	}

	public function getVoucherByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "voucher WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getVouchers($data = array()) {
		$sql = "SELECT v.voucher_id, v.order_id, v.code, v.from_name, v.from_email, v.to_name, v.to_email, (SELECT vtd.name FROM " . DB_PREFIX . "voucher_theme_description vtd WHERE vtd.voucher_theme_id = v.voucher_theme_id AND vtd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS theme, v.amount, v.status, v.date_added FROM " . DB_PREFIX . "voucher v";

		$sort_data = array(
			'v.code',
			'v.from_name',
			'v.to_name',
			'theme',
			'v.amount',
			'v.status',
			'v.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY v.date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function sendVoucher($voucher_id) {
		$voucher_info = $this->getVoucher($voucher_id);

		if ($voucher_info) {
			if ($voucher_info['order_id']) {
				$order_id = $voucher_info['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			// If voucher belongs to an order
			if ($order_info) {
				$this->load->model('localisation/language');

				$language = new Language($order_info['language_code']);
				$language->load($order_info['language_code']);
				$language->load('mail/voucher');

				// HTML Mail
				$data = array();

				$data['title'] = sprintf($language->get('text_subject'), $voucher_info['from_name']);

				$data['text_greeting'] = sprintf($language->get('text_greeting'), $this->currency->format($voucher_info['amount'], (!empty($order_info['currency_code']) ? $order_info['currency_code'] : $this->config->get('config_currency')), (!empty($order_info['currency_value']) ? $order_info['currency_value'] : $this->currency->getValue($this->config->get('config_currency')))));
				$data['text_from'] = sprintf($language->get('text_from'), $voucher_info['from_name']);
				$data['text_message'] = $language->get('text_message');
				$data['text_redeem'] = sprintf($language->get('text_redeem'), $voucher_info['code']);
				$data['text_footer'] = $language->get('text_footer');

				$this->load->model('sale/voucher_theme');

				$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);

				if ($voucher_theme_info && is_file(DIR_IMAGE . $voucher_theme_info['image'])) {
					$data['image'] = HTTP_CATALOG . 'image/' . $voucher_theme_info['image'];
				} else {
					$data['image'] = '';
				}

				$data['store_name'] = $order_info['store_name'];
				$data['store_url'] = $order_info['store_url'];
				$data['message'] = nl2br($voucher_info['message']);

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($voucher_info['to_email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(sprintf($language->get('text_subject'), html_entity_decode($voucher_info['from_name'], ENT_QUOTES, 'UTF-8')));
				$mail->setHtml($this->load->view('mail/voucher', $data));
				$mail->send();

			// If voucher does not belong to an order
			}  else {
				$this->load->language('mail/voucher');

				$data = array();

				$data['title'] = sprintf($this->language->get('text_subject'), $voucher_info['from_name']);

				$data['text_greeting'] = sprintf($this->language->get('text_greeting'), $this->currency->format($voucher_info['amount'], $order_info['currency_code'], $order_info['currency_value']));
				$data['text_from'] = sprintf($this->language->get('text_from'), $voucher_info['from_name']);
				$data['text_message'] = $this->language->get('text_message');
				$data['text_redeem'] = sprintf($this->language->get('text_redeem'), $voucher_info['code']);
				$data['text_footer'] = $this->language->get('text_footer');

				$this->load->model('sale/voucher_theme');

				$voucher_theme_info = $this->model_sale_voucher_theme->getVoucherTheme($voucher_info['voucher_theme_id']);

				if ($voucher_theme_info && is_file(DIR_IMAGE . $voucher_theme_info['image'])) {
					$data['image'] = HTTP_CATALOG . 'image/' . $voucher_theme_info['image'];
				} else {
					$data['image'] = '';
				}

				$data['store_name'] = $this->config->get('config_name');
				$data['store_url'] = HTTP_CATALOG;
				$data['message'] = nl2br($voucher_info['message']);

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($voucher_info['to_email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), $voucher_info['from_name']), ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($this->load->view('mail/voucher', $data));
				$mail->send();
			}
		}
	}

	public function getTotalVouchers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher");

		return $query->row['total'];
	}

	public function getTotalVouchersByVoucherThemeId($voucher_theme_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher WHERE voucher_theme_id = '" . (int)$voucher_theme_id . "'");

		return $query->row['total'];
	}

	public function getVoucherHistories($voucher_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT vh.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, vh.amount, vh.date_added FROM " . DB_PREFIX . "voucher_history vh LEFT JOIN `" . DB_PREFIX . "order` o ON (vh.order_id = o.order_id) WHERE vh.voucher_id = '" . (int)$voucher_id . "' ORDER BY vh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalVoucherHistories($voucher_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher_history WHERE voucher_id = '" . (int)$voucher_id . "'");

		return $query->row['total'];
	}
}
