<?php
class ModelCheckoutVoucher extends Model {
	public function addVoucher($order_id, $data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "voucher SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape(rand()) . "', from_name = '" . $this->db->escape($data['from_name']) . "', from_email = '" . $this->db->escape($data['from_email']) . "', to_name = '" . $this->db->escape($data['to_name']) . "', to_email = '" . $this->db->escape($data['to_email']) . "', message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', voucher_theme_id = '" . (int)$data['voucher_theme_id'] . "', status = '1', date_added = NOW()");
	}
	
	public function getVoucher($code) {
		$status = true;
		
		$voucher_query = $this->db->query("SELECT *, vtd.name AS theme FROM " . DB_PREFIX . "voucher v LEFT JOIN " . DB_PREFIX . "voucher_theme vt ON (v.voucher_theme_id = vt.voucher_theme_id) LEFT JOIN " . DB_PREFIX . "voucher_theme_description vtd ON (vt.voucher_theme_id = vtd.voucher_theme_id) WHERE v.code = '" . $this->db->escape($code) . "' AND vtd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND v.status = '1'");
		
		if ($voucher_query->num_rows) {
			if ($voucher_query->row['order_id']) {
				$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$voucher_query->row['order_id'] . "' AND order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "'");
			
				if (!$order_query->num_rows) {
					$status = false;
				}
			}
			
			$voucher_history_query = $this->db->query("SELECT SUM(amount) AS total FROM `" . DB_PREFIX . "voucher_history` vh WHERE vh.voucher_id = '" . (int)$voucher_query->row['voucher_id'] . "' GROUP BY vh.voucher_id");
	
			if ($voucher_history_query->num_rows) {
				$amount = $voucher_query->row['amount'] - $voucher_history_query->row['total'];
			} else {
				$amount = $voucher_query->row['amount'];
			}
			
			if ($amount <= 0) {
				$status = false;
			}		
		} else {
			$status = false;
		}
		
		if ($status) {
			return array(
				'voucher_id'       => $voucher_query->row['voucher_id'],
				'code'             => $voucher_query->row['code'],
				'from_name'        => $voucher_query->row['from_name'],
				'from_email'       => $voucher_query->row['from_email'],
				'to_name'          => $voucher_query->row['to_name'],
				'to_email'         => $voucher_query->row['to_email'],
				'message'          => $voucher_query->row['message'],
				'voucher_theme_id' => $voucher_query->row['voucher_theme_id'],
				'theme'            => $voucher_query->row['theme'],
				'image'            => $voucher_query->row['image'],
				'amount'           => $amount,
				'status'           => $voucher_query->row['status'],
				'date_added'       => $voucher_query->row['date_added']
			);
		}
	}
	
	public function confirm($order_id) {
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			$this->load->model('localisation/language');
			
			$language = new Language($order_info['directory']);
			$language->load($order_info['filename']);	
			$language->load('checkout/voucher');
			
			$query = $this->db->query("SELECT * FROM voucher WHERE order_id = '" . (int)$order_id . "'");
			
			foreach ($query->rows as $voucher) {
				// HTML Mail
				$template = new Template();
				
				$template->data['title'] = sprintf($this->language->get('mail_subject'), $voucher['from_name']);
				
				$template->data['mail_greeting'] = sprintf($language->get('mail_greeting'), $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']));
				$template->data['mail_from'] = sprintf($language->get('mail_from'), $voucher['from_name']);
				$template->data['mail_message'] = $language->get('mail_message');
				$template->data['mail_redeem'] = sprintf($language->get('mail_redeem'), $voucher['code']);
				$template->data['mail_greeting'] = $language->get('mail_message');
				$template->data['mail_greeting'] = $language->get('mail_problem');
				$template->data['mail_greeting'] = $language->get('mail_footer');
		
				$template->data['message'] = $voucher['message'];
		
				$mail = new Mail(); 
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');			
				$mail->setTo($voucher['to_email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($order_query->row['store_name']);
				$mail->setSubject(sprintf($this->language->get('mail_subject'), $voucher['from_name']));
				$mail->setHtml($html);
				$mail->addAttachment(DIR_IMAGE . $voucher['image']);
				$mail->send();		
			}
		}
	}
	
	public function redeem($voucher_id, $order_id, $amount) {
		$this->data->query("INSERT INTO voucher_history SET voucher_id = '" . (int)$voucher_id . "', order_id = '" . (int)$order_id . "', amount = '" . (float)$amount . "', date_added = NOW()");
	}
}
?>