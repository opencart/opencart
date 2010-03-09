<?php
class ModelCheckoutOrder extends Model {
	public function getOrder($order_id) {
		$query = $this->db->query("SELECT *, c1.iso_code_2 AS shipping_iso_code_2, c1.iso_code_3 AS shipping_iso_code_3, c2.iso_code_2 AS payment_iso_code_2, c2.iso_code_3 AS shipping_iso_code_3, z1.code AS shipping_zone_code, z2.code AS payment_zone_code FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "country c1 ON (o.shipping_country_id = c1.country_id) LEFT JOIN " . DB_PREFIX . "country c2 ON (o.payment_country_id = c2.country_id) LEFT JOIN " . DB_PREFIX . "zone z1 ON (o.payment_zone_id = z1.zone_id) LEFT JOIN " . DB_PREFIX . "zone z2 ON (o.payment_zone_id = z2.zone_id) WHERE o.order_id = '" . (int)$order_id . "'");
	
		return $query->row;
	}	
	
	public function create($data) {
		$query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE date_added < '" . date('Y-m-d', strtotime('-1 month')) . "' AND order_status_id = '0'");
		
		foreach ($query->rows as $result) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$result['order_id'] . "'");
	  		$this->db->query("DELETE FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$result['order_id'] . "'");
      		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$result['order_id'] . "'");
		}		
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', total = '" . (float)$data['total'] . "', language_id = '" . (int)$data['language_id'] . "', currency = '" . $this->db->escape($data['currency']) . "', currency_id = '" . (int)$data['currency_id'] . "', value = '" . (float)$data['value'] . "', coupon_id = '" . (int)$data['coupon_id'] . "', ip = '" . $this->db->escape($data['ip']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW(), date_added = NOW()");

		$order_id = $this->db->getLastId();

		foreach ($data['products'] as $product) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', quantity = '" . (int)$product['quantity'] . "'");
 
			$order_product_id = $this->db->getLastId();

			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', price = '" . (float)$product['price'] . "', prefix = '" . $this->db->escape($option['prefix']) . "'");
			}
				
			foreach ($product['download'] as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}	
		}
		
		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}	

		return $order_id;
	}

	public function confirm($order_id, $order_status_id, $comment = '') {
		$order_query = $this->db->query("SELECT *, l.filename AS filename, l.directory AS directory FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '0'");
		 
		if ($order_query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "' WHERE order_id = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

			if ($this->config->get('config_stock_subtract')) {
				$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			
				foreach ($order_product_query->rows as $product) {
					$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$product['quantity'] . ") WHERE product_id = '" . (int)$product['product_id'] . "'");
				
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
				
					foreach ($order_option_query->rows as $option) {
						$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
					}
				}
			}
			
			$language = new Language($order_query->row['directory']);
			$language->load($order_query->row['filename']);
			$language->load('mail/order_confirm');
			
			$this->load->model('localisation/currency');
			
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
			
			$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'), $order_id);
			
			// HTML Mail
			$template = new Template();
			
			$template->data['title'] = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'), $order_id);
			
			$template->data['text_greeting'] = sprintf($language->get('text_greeting'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
			$template->data['text_order_detail'] = $language->get('text_order_detail');
			$template->data['text_order_id'] = $language->get('text_order_id');
			$template->data['text_invoice'] = $language->get('text_invoice');
			$template->data['text_date_added'] = $language->get('text_date_added');
			$template->data['text_telephone'] = $language->get('text_telephone');
			$template->data['text_fax'] = $language->get('text_fax');		
			$template->data['text_shipping_address'] = $language->get('text_shipping_address');
			$template->data['text_payment_address'] = $language->get('text_payment_address');
			$template->data['text_shipping_method'] = $language->get('text_shipping_method');
			$template->data['text_payment_method'] = $language->get('text_payment_method');
			$template->data['text_comment'] = $language->get('text_comment');
			$template->data['text_powered_by'] = $language->get('text_powered_by');
			
			$template->data['column_product'] = $language->get('column_product');
			$template->data['column_model'] = $language->get('column_model');
			$template->data['column_quantity'] = $language->get('column_quantity');
			$template->data['column_price'] = $language->get('column_price');
			$template->data['column_total'] = $language->get('column_total');
					
			$template->data['order_id'] = $order_id;
			$template->data['customer_id'] = $order_query->row['customer_id'];	
			$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_query->row['date_added']));    	
			$template->data['logo'] = 'cid:' . basename($this->config->get('config_logo'));
			$template->data['store'] = $this->config->get('config_store');
			$template->data['address'] = nl2br($this->config->get('config_address'));
			$template->data['telephone'] = $this->config->get('config_telephone');
			$template->data['fax'] = $this->config->get('config_fax');
			$template->data['email'] = $this->config->get('config_email');
			$template->data['website'] = trim(HTTP_SERVER, '/');
			$template->data['invoice'] = html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id), ENT_QUOTES, 'UTF-8');
			$template->data['firstname'] = $order_query->row['firstname'];
			$template->data['lastname'] = $order_query->row['lastname'];
			$template->data['shipping_method'] = $order_query->row['shipping_method'];
			$template->data['payment_method'] = $order_query->row['payment_method'];
			$template->data['comment'] = $order_query->row['comment'];
			
			if ($order_query->row['shipping_address_format']) {
				$format = $order_query->row['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{country}'
			);
		
			$replace = array(
				'firstname' => $order_query->row['shipping_firstname'],
				'lastname'  => $order_query->row['shipping_lastname'],
				'company'   => $order_query->row['shipping_company'],
				'address_1' => $order_query->row['shipping_address_1'],
				'address_2' => $order_query->row['shipping_address_2'],
				'city'      => $order_query->row['shipping_city'],
				'postcode'  => $order_query->row['shipping_postcode'],
				'zone'      => $order_query->row['shipping_zone'],
				'country'   => $order_query->row['shipping_country']  
			);
		
			$template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	  
			if ($order_query->row['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{country}'
			);
		
			$replace = array(
				'firstname' => $order_query->row['payment_firstname'],
				'lastname'  => $order_query->row['payment_lastname'],
				'company'   => $order_query->row['payment_company'],
				'address_1' => $order_query->row['payment_address_1'],
				'address_2' => $order_query->row['payment_address_2'],
				'city'      => $order_query->row['payment_city'],
				'postcode'  => $order_query->row['payment_postcode'],
				'zone'      => $order_query->row['payment_zone'],
				'country'   => $order_query->row['payment_country']  
			);
		
			$template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			
			$template->data['products'] = array();
				
			foreach ($order_product_query->rows as $product) {
				$option_data = array();
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $option['value']
					);
				}
			  
				$template->data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'], $order_query->row['currency'], $order_query->row['value']),
					'total'    => $this->currency->format($product['total'], $order_query->row['currency'], $order_query->row['value'])
				);
			}
	
			$template->data['totals'] = $order_total_query->rows;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order_confirm.tpl')) {
				$html = $template->fetch($this->config->get('config_template') . '/template/mail/order_confirm.tpl');
			} else {
				$html = $template->fetch('default/template/mail/order_confirm.tpl');
			}

			// Text Mail
			$text  = sprintf($language->get('text_greeting'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8')) . "\n\n";
			$text .= $language->get('text_order_id') . ' ' . $order_id . "\n";
			$text .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
			$text .= $language->get('text_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
			$text .= $language->get('text_product') . "\n";
			
			foreach ($order_product_query->rows as $result) {
				$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']), ENT_NOQUOTES, 'UTF-8') . "\n";
			}
			
			$text .= "\n";
			
			$text .= $language->get('text_total') . "\n";
			
			foreach ($order_total_query->rows as $result) {
				$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
			}			
			
			$text .= "\n";
			
			if ($order_query->row['customer_id']) {
				$text .= $language->get('text_invoice') . "\n";
				$text .= html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id), ENT_QUOTES, 'UTF-8') . "\n\n";
			}
		
			if ($order_download_query->num_rows) {
				$text .= $language->get('text_download') . "\n";
				$text .= $this->url->http('account/download') . "\n\n";
			}
			
			if ($comment) {
				$text .= $language->get('text_comment') . "\n\n";
				$text .= $comment . "\n\n";
			}
			
			$text .= $language->get('text_footer');
						
			$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password'), ENT_QUOTES, 'UTF-8'), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 
			$mail->setTo($order_query->row['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setHtml($html);
			$mail->setText($text);
			$mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'));
			$mail->send();
			
			if ($this->config->get('config_alert_mail')) {
				$text  = $language->get('text_received') . "\n\n";
				$text .= $language->get('text_order_id') . ' ' . $order_id . "\n";
				$text .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
				$text .= $language->get('text_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
				$text .= $language->get('text_product') . "\n";
				
				foreach ($order_product_query->rows as $result) {
					$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_query->row['currency'], $order_query->row['value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				}
				
				$text .= "\n";

				$text.= $language->get('text_total') . "\n";
				
				foreach ($order_total_query->rows as $result) {
					$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
				}			
				
				$text .= "\n";
				
				if ($comment) {
					$text .= $language->get('text_comment') . "\n\n";
					$text .= $comment . "\n\n";
				}
			
				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), $this->config->get('config_smtp_password'), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout')); 
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setText($text);
				$mail->send();	
			}		
		}
	}
	
	public function update($order_id, $order_status_id, $comment = '', $notifiy = FALSE) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id > '0'");
		
		if ($order_query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notifiy . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
	
			if ($notifiy) {
				$language = new Language($order_query->row['directory']);
				$language->load($order_query->row['filename']);
				$language->load('mail/order_update');
			
				$subject = sprintf($language->get('text_subject'), html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'), $order_id);
	
				$message  = $language->get('text_order') . ' ' . $order_id . "\n";
				$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n\n";
				
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
				
				if ($order_status_query->num_rows) {
					$message .= $language->get('text_order_status') . "\n\n";
					$message .= $order_status_query->row['name'] . "\n\n";
				}
				
				$message .= $language->get('text_invoice') . "\n";
				$message .= html_entity_decode($this->url->http('account/invoice&order_id=' . $order_id), ENT_QUOTES, 'UTF-8') . "\n\n";
					
				if ($comment) { 
					$message .= $language->get('text_comment') . "\n\n";
					$message .= $comment . "\n\n";
				}
					
				$message .= $language->get('text_footer');

				$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password'), ENT_QUOTES, 'UTF-8'), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
				$mail->setTo($order_query->row['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject($subject);
				$mail->setText($message);
				$mail->send();
			}
		}
	}
}
?>