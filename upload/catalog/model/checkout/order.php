<?php
class ModelCheckoutOrder extends Model {
	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "'");
	
		return $query->row;
	}	
	
	public function create($data) {
		$query = $this->db->query("SELECT order_id FROM `order` WHERE date_added < '" . date('Y-m-d', strtotime('-1 month')) . "' AND order_status_id = '0'");
		
		foreach ($query->rows as $result) {
			$this->db->query("DELETE FROM `order` WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM order_history WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM order_product WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM order_option WHERE order_id = '" . (int)$result['order_id'] . "'");
	  		$this->db->query("DELETE FROM order_download WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM order_total WHERE order_id = '" . (int)$result['order_id'] . "'");
		}		
		
		$this->db->query("INSERT INTO `order` SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', total = '" . (float)$data['total'] . "', language_id = '" . $this->db->escape($data['language_id']) . "', currency = '" . $this->db->escape($data['currency']) . "', currency_id = '" . $this->db->escape($data['currency_id']) . "', value = '" . (float)$data['value'] . "', ip = '" . $this->db->escape($data['ip']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW(), date_added = NOW()");

		$order_id = $this->db->getLastId();

		foreach ($data['products'] as $product) { 
			$this->db->query("INSERT INTO order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', price = '" . (float)$product['price'] . "', discount = '" . (float)$product['discount'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', quantity = '" . (int)$product['quantity'] . "'");
 
			$order_product_id = $this->db->getLastId();

			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', price = '" . (float)$product['price'] . "', prefix = '" . $this->db->escape($option['prefix']) . "'");
			}
				
			foreach ($product['download'] as $download) {
				$this->db->query("INSERT INTO order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}	
		}

		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO order_total SET order_id = '" . (int)$order_id . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}	

		return $order_id;
	}

	public function confirm($order_id, $order_status_id, $comment = '') {
		$order_query = $this->db->query("SELECT *, l.code AS language FROM `order` o LEFT JOIN language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '0'");
		 
		if ($order_query->num_rows) {
			$this->db->query("UPDATE `order` SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			
			$this->language->load($order_query->row['filename'], $order_query->row['language']);
			$this->language->load('checkout/confirm', $order_query->row['language']);
			
			$subject = sprintf($this->language->get('mail_new_order_subject'), $this->config->get('config_store'), $order_id);
			
			$message  = $this->language->get('mail_new_order_greeting') . "\n\n";
			$message .= $this->language->get('mail_new_order_order') . ' ' . $order_id . "\n";
			$message .= $this->language->get('mail_new_order_date_added') . ' ' . date($this->language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
			
			$order_status_query = $this->db->query("SELECT * FROM order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");

			$message .= $this->language->get('mail_new_order_order_status') . ' ' . @$order_status_query->row['name'] . "\n\n";
			$message .= $this->language->get('mail_new_order_product') . "\n";
			
			$order_product_query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");
			
			foreach ($order_product_query->rows as $result) {
				$message .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . $this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']) . "\n";
			}
			
			$message .= "\n";
			
			$message .= $this->language->get('mail_new_order_total') . "\n";
			
			$order_total_query = $this->db->query("SELECT * FROM order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			
			foreach ($order_total_query->rows as $result) {
				$message .= $result['title'] . ' ' . $result['text'] . "\n";
			}			
			
			$message .= "\n";
			
			$message .= $this->language->get('mail_new_order_invoice') . "\n";
			$message .= html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id)) . "\n\n";
			
			$order_download_query = $this->db->query("SELECT * FROM order_download WHERE order_id = '" . (int)$order_id . "'");
			
			if ($order_download_query->num_rows) {
				$message .= $this->language->get('mail_new_order_download') . "\n";
				$message .= $this->url->http('account/download') . "\n\n";
			}
			
			if ($comment) {
				$message .= $this->language->get('mail_new_order_comment') . "\n\n";
				$message .= $comment . "\n\n";
			}
			
			$message .= $this->language->get('mail_new_order_footer');

			$mail = new Mail(); 
			$mail->setTo($order_query->row['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_store'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
		}
	}
	
	public function update($order_id, $order_status_id, $comment = '', $notifiy = FALSE) {
		$query = $this->db->query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "' AND order_status_id > '0'");
		
		if ($query->num_rows) {
			$this->db->query("UPDATE `order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		
			$this->db->query("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
	
			if ($notifiy) {
				$query = $this->db->query("SELECT *, os.name AS status, l.code AS language FROM `order` o LEFT JOIN order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = o.language_id) LEFT JOIN language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "'");
			
				if ($query->num_rows) {
					$this->language->load($query->row['filename'], $query->row['language']);
					$this->language->load('checkout/confirm', $order_query->row['language']);
	
					$subject = sprintf($this->language->get('mail_update_order_subject'), $this->config->get('config_store'), $order_id);
	
					$message  = $this->language->get('mail_update_order_order') . ' ' . $order_id . "\n";
					$message .= $this->language->get('mail_update_order_date_added') . ' ' . date($this->language->get('date_format_short'), strtotime($query->row['date_added'])) . "\n\n";
					$message .= $this->language->get('mail_update_order_order_status') . "\n\n";
					$message .= $query->row['status'] . "\n\n";
					
					$message .= $this->language->get('mail_update_order_invoice') . "\n";
					$message .= html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id));
					
					if ($comment) { 
						$message .= $this->language->get('mail_update_order_comment') . "\n\n";
						$message .= $comment . "\n\n";
					}
					
					$message .= $this->language->get('mail_update_order_footer');

					$mail = new Mail();
					$mail->setTo($query->row['email']);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($this->config->get('config_store'));
					$mail->setSubject($subject);
					$mail->setText($message);
					$mail->send();
				}
			}
		}
	}
	
	public function complete($order_id) {
		if ($this->config->get('config_stock_subtract')) {
			$order_query = $this->db->query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "' AND order_status_id > '1'");

			if ($order_query->num_rows) {
				$order_product_query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");
			
				foreach ($order_product_query->rows as $result) {
					$this->db->query("UPDATE product SET quantity = (quantity - " . (int)$result['quantity'] . ") WHERE product_id = '" . (int)$result['product_id'] . "'");
				}
			}
		}
	}
}
?>