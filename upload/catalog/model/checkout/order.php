<?php
class ModelCheckoutOrder extends Model {
	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "'");
	
		return $query->row;
	}	
	
	public function create($data) {
		$query = $this->db->query("SELECT order_id FROM `order` WHERE date_added < '" . date('Y-m-d', strtotime('-1 month')) . "' AND confirm = '0'");
		
		foreach ($query->rows as $result) {
			$this->db->query("DELETE FROM `order` WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM order_history WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM order_product WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM order_option WHERE order_id = '" . (int)$result['order_id'] . "'");
	  		$this->db->query("DELETE FROM order_download WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM order_total WHERE order_id = '" . (int)$result['order_id'] . "'");
		}		
		
		$this->db->query("INSERT INTO `order` SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "',  order_status_id = '" . (int)$data['order_status_id'] . "', total = '" . (float)$data['total'] . "', language_id = '" . $this->db->escape($data['language_id']) . "', currency = '" . $this->db->escape($data['currency']) . "', currency_id = '" . $this->db->escape($data['currency_id']) . "', value = '" . (float)$data['value'] . "', ip = '" . $this->db->escape($data['ip']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', date_modified = NOW(), date_added = NOW()");

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
		
		$this->db->query("INSERT INTO order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', date_added = NOW(), notify = '1', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "'");

		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO order_total SET order_id = '" . (int)$order_id . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}	

		return $order_id;
	}

	public function confirm($order_id, $order_status_id) {
		$query = $this->db->query("SELECT *, os.name AS status, l.code AS language FROM `order` o LEFT JOIN order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = o.language_id) LEFT JOIN language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.confirm = '0'");
		 
		if ($query->num_rows) {
			$this->db->query("UPDATE `order` SET confirm = '1' WHERE order_id = '" . (int)$order_id . "' AND order_status_id = '" . (int)$order_status_id . "'");
			
			$this->language->load($query->row['filename'], $query->row['language']);
			
			$find = array(
				'{store}',
				'{order_id}',
				'{date_added}',
				'{status}', 
				'{invoice}'
			);
						
			$replace = array(
				'store'      => $this->config->get('config_store'),
				'order_id'   => $order_id,
				'date_added' => date($this->language->get('date_format_short'), strtotime($query->row['date_added'])),
				'status'     => $query->row['status'],
				'invoice'    => html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id))
			);
			
			$subject = str_replace($find, $replace, $this->config->get('config_order_subject_' . $query->row['language_id']));
			$message = str_replace($find, $replace, $this->config->get('config_order_message_' . $query->row['language_id']));

			$mail = new Mail(); 
			$mail->setTo($query->row['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_store'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
		}
	}
	
	public function update($order_id, $order_status_id, $comment = '', $notifiy = FALSE) {
		$this->db->query("UPDATE `order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "' AND confirm = '1'");
		
		$this->db->query("INSERT INTO order_history SET order_status_id = '" . (int)$order_status_id . "', comment = '" . $this->db->escape($comment) . "' WHERE order_id = '" . (int)$order_id . "'");
	
		if ($notifiy) {
			$query = $this->db->query("SELECT *, os.name AS status, l.code AS language FROM `order` o LEFT JOIN order_status os ON (o.order_status_id = os.order_status_id AND os.language_id = o.language_id) LEFT JOIN language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "'");
			
			if ($query->num_rows) {
				$this->language->load($query->row['filename'], $query->row['language']);
				
				$find = array(
					'{store}',
					'{order_id}',
					'{date_added}',
					'{status}', 
					'{comment}',
					'{invoice}'
				);
								
				$replace = array(
					'store'      => $this->config->get('config_store'),
					'order_id'   => $order_id,
					'date_added' => date($this->language->get('date_format_short'), strtotime($query->row['date_added'])),
					'status'     => $query->row['status'],
					'comment'    => $query->row['comment'],
					'invoice'    => html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id))
				);
				
				$subject = str_replace($find, $replace, $this->config->get('config_update_subject_' . $query->row['language_id']));				
			
				$message = str_replace($find, $replace, $this->config->get('config_update_message_' . $query->row['language_id']));

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
	
	public function complete($order_id) {
		if ($this->config->get('config_stock_subtract')) {
			$query = $this->db->query("SELECT * FROM `order` WHERE order_id = '" . (int)$order_id . "' AND confirm = '1'");

			if ($query->num_rows) {
				$query = $this->db->query("SELECT * FROM order_product WHERE order_id = '" . (int)$order_id . "'");
			
				foreach ($query->rows as $result) {
					$this->db->query("UPDATE product SET quantity = (quantity - " . (int)$result['quantity'] . ") WHERE product_id = '" . (int)$result['product_id'] . "'");
				}
			}
		}
	}
}
?>