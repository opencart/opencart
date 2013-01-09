<?php
class ModelTotalCredit extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->config->get('credit_status')) {
			$this->load->language('total/credit');
		 
			$balance = $this->customer->getBalance();

			if ((float)$balance) {
				if ($balance > $total) {
					$credit = $total;	
				} else {
					$credit = $balance;	
				}
				
				if ($credit > 0) {
					$total_data[] = array(
						'code'       => 'credit',
						'title'      => $this->language->get('text_credit'),
						'text'       => $this->currency->format(-$credit),
						'value'      => -$credit,
						'sort_order' => $this->config->get('credit_sort_order')
					);
					
					$total -= $credit;
				}
			}
		}
	}
	
	public function confirm($order_info, $order_total) {
		$this->load->language('total/credit');

		if ($order_info['customer_id']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$order_info['customer_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', description = '" . $this->db->escape(sprintf($this->language->get('text_order_id'), (int)$order_info['order_id'])) . "', amount = '" . (float)$order_total['value'] . "', date_added = NOW()");
			if(($balance=(int)$this->customer->getBalance()) < 0) {
				if ($this->config->get('config_alert_mail')) {
					$language = new Language($order_info['language_directory']);
					$language->load($order_info['language_filename']);
					$language->load('mail/order');

					$subject = sprintf($language->get('text_new_subject')." ".$language->get('text_tricky'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_info['order_id']);

					// Text
					$text  = $language->get('text_new_received') . "\n\n";
					$text .= $language->get('text_new_order_id') . ' ' . $order_info['order_id'] . "\n";
					$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
					$text .= $language->get('text_new_order_status') . ' ' . $order_info['order_status_id'] . "\n\n";
					$text .= $language->get('text_tricky_order') . "\n";
					$text .= $language->get('text_user_balance')." ".$balance. "\n";

					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->hostname = $this->config->get('config_smtp_host');
					$mail->username = $this->config->get('config_smtp_username');
					$mail->password = $this->config->get('config_smtp_password');
					$mail->port = $this->config->get('config_smtp_port');
					$mail->timeout = $this->config->get('config_smtp_timeout');
					$mail->setTo($this->config->get('config_email'));
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($order_info['store_name']);
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
					$mail->send();

					// Send to additional alert emails
					$emails = explode(',', $this->config->get('config_alert_emails'));

					foreach ($emails as $email) {
						if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
							$mail->setTo($email);
							$mail->send();
						}
					}
				}
			}
		}
	}
}
?>