<?php
class ModelCheckoutOrder extends Model {	
	public function create($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', reward = '" . (float)$data['reward'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', date_added = NOW(), date_modified = NOW()");

		$order_id = $this->db->getLastId();

		foreach ($data['products'] as $product) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "', price = '" . (float)$product['price'] . "', total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "'");
 
			$order_product_id = $this->db->getLastId();

			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
			}
				
			foreach ($product['download'] as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}	
		}
		
		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}	

		return $order_id;
	}

	public function confirm($order_id, $order_status_id, $comment = '') {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id = '0'");
		 
		if ($order_query->num_rows) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "'");
	
			if ($query->row['invoice_no']) {
				$invoice_no = (int)$query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}
			
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "', order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			
			foreach ($order_product_query->rows as $order_product) {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");
			
				foreach ($order_option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
			
			$this->cache->delete('product');
			
			$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "'");
			
			foreach ($order_total_query->rows as $order_total) {
				$this->load->model('total/' . $order_total['code']);
				
				if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
					$this->{'model_total_' . $order_total['code']}->confirm($order_query->row, $order_total);
				}
			}

			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($query->row['language_id']);
			
			if ($language_info) {
				$language = new Language($language_info['directory']);
				$language->load($language_info['filename']);
				$language->load('checkout/checkout');
			} else {
				$language_info = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
				
				$language = new Language($language_info['directory']);
				$language->load($language_info['filename']);	
				$language->load('checkout/checkout');				
			}
		 
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			$order_total_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
			
			$subject = sprintf($language->get('mail_new_subject'), $order_query->row['store_name'], $order_id);
		
			// HTML Mail
			$template = new Template();
			
			$template->data['title'] = sprintf($language->get('mail_new_subject'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
			
			$template->data['mail_greeting'] = sprintf($language->get('mail_new_greeting'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'));
			$template->data['mail_order_detail'] = $language->get('mail_new_order_detail');
			$template->data['mail_order_id'] = $language->get('mail_new_order_id');
			$template->data['mail_invoice'] = $language->get('mail_new_invoice');
			$template->data['mail_date_added'] = $language->get('mail_new_date_added');
			$template->data['mail_telephone'] = $language->get('mail_new_telephone');
			$template->data['mail_email'] = $language->get('mail_new_email');
			$template->data['mail_ip'] = $language->get('mail_new_ip');
			$template->data['mail_fax'] = $language->get('mail_new_fax');		
			$template->data['mail_shipping_address'] = $language->get('mail_new_shipping_address');
			$template->data['mail_payment_address'] = $language->get('mail_new_payment_address');
			$template->data['mail_shipping_method'] = $language->get('mail_new_shipping_method');
			$template->data['mail_payment_method'] = $language->get('mail_new_payment_method');
			$template->data['mail_comment'] = $language->get('mail_new_comment');
			$template->data['mail_powered_by'] = $language->get('mail_new_powered_by');
			$template->data['mail_product'] = $language->get('mail_new_product');
			$template->data['mail_model'] = $language->get('mail_new_model');
			$template->data['mail_quantity'] = $language->get('mail_new_quantity');
			$template->data['mail_price'] = $language->get('mail_new_price');
			$template->data['mail_total'] = $language->get('mail_new_total');
					
			$template->data['order_id'] = $order_id;
			$template->data['customer_id'] = $order_query->row['customer_id'];	
			$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_query->row['date_added']));    	
			$template->data['logo'] = 'cid:' . basename($this->config->get('config_logo'));
			$template->data['store_name'] = $order_query->row['store_name'];
			$template->data['address'] = nl2br($this->config->get('config_address'));
			$template->data['telephone'] = $this->config->get('config_telephone');
			$template->data['fax'] = $this->config->get('config_fax');
			$template->data['email'] = $this->config->get('config_email');
			$template->data['store_url'] = $order_query->row['store_url'];
			$template->data['invoice'] = $order_query->row['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
			$template->data['firstname'] = $order_query->row['firstname'];
			$template->data['lastname'] = $order_query->row['lastname'];
			$template->data['shipping_method'] = $order_query->row['shipping_method'];
			$template->data['payment_method'] = $order_query->row['payment_method'];
			$template->data['email'] = $order_query->row['email'];
			$template->data['telephone'] = $order_query->row['telephone'];
			$template->data['ip'] = $order_query->row['ip'];
			$template->data['comment'] = nl2br($order_query->row['comment']);
			
			if ($comment) {
				$template->data['comment'] .= ('<br /><br />' . nl2br($comment)); 
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$zone_code = $zone_query->row['code'];
			} else {
				$zone_code = '';
			}
			
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
				'{zone_code}',
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
				'zone_code' => $zone_code,
				'country'   => $order_query->row['shipping_country']  
			);
		
			$template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$zone_code = $zone_query->row['code'];
			} else {
				$zone_code = '';
			}
			
			if ($order_query->row['payment_address_format']) {
				$format = $order_query->row['payment_address_format'];
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
				'{zone_code}',
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
				'zone_code' => $zone_code,
				'country'   => $order_query->row['payment_country']  
			);
		
			$template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			
			$template->data['products'] = array();
				
			foreach ($order_product_query->rows as $product) {
				$option_data = array();
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value'])
						);
					} else {
						$filename = substr($option['value'], 0, strrpos($option['value'], '.'));
						
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => (strlen($filename) > 20 ? substr($filename, 0, 20) . '..' : $filename)
						);	
					}
				}
			  
				$template->data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'], $order_query->row['currency_code'], $order_query->row['currency_value']),
					'total'    => $this->currency->format($product['total'], $order_query->row['currency_code'], $order_query->row['currency_value'])
				);
			}
	
			$template->data['totals'] = $order_total_query->rows;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/mail_confirm.tpl')) {
				$html = $template->fetch($this->config->get('config_template') . '/template/checkout/mail_confirm.tpl');
			} else {
				$html = $template->fetch('default/template/checkout/mail_confirm.tpl');
			}
			
			// Text Mail
			$text  = sprintf($language->get('mail_new_greeting'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			$text .= $language->get('mail_new_order_id') . ' ' . $order_id . "\n";
			$text .= $language->get('mail_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
			$text .= $language->get('mail_new_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
			$text .= $language->get('mail_new_products') . "\n";
			
			foreach ($order_product_query->rows as $result) {
				$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_query->row['currency_code'], $order_query->row['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					$text .= chr(9) . '-' . $option['name'] . ' ' . (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
				}
			}
			
			$text .= "\n";
			
			$text .= $language->get('mail_new_order_total') . "\n";
			
			foreach ($order_total_query->rows as $result) {
				$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
			}			
			
			$order_total = $result['text'];
			
			$text .= "\n";
			
			if ($order_query->row['customer_id']) {
				$text .= $language->get('mail_new_invoice') . "\n";
				$text .= $order_query->row['store_url'] . 'index.php?route=account/invoice&order_id=' . $order_id . "\n\n";
			}
		
			if ($order_download_query->num_rows) {
				$text .= $language->get('mail_new_download') . "\n";
				$text .= $order_query->row['store_url'] . 'index.php?route=account/download' . "\n\n";
			}
			
			if ($order_query->row['comment'] != '') {
				$comment = ($order_query->row['comment'] .  "\n\n" . $comment);
			}
			
			if ($comment) {
				$text .= $language->get('mail_new_comment') . "\n\n";
				$text .= $comment . "\n\n";
			}
			
			$text .= $language->get('mail_new_footer') . "\n\n";
		
			$mail = new Mail(); 
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');			
			$mail->setTo($order_query->row['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($order_query->row['store_name']);
			$mail->setSubject($subject);
			$mail->setHtml($html);
			$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
			$mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'));
			$mail->send();

			// Admin Alert Mail
			if ($this->config->get('config_alert_mail')) {
				$subject = sprintf($language->get('mail_new_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'), $order_id);
				
				// HTML
				$template->data['mail_greeting'] = $language->get('mail_new_received') . "\n\n";
				$template->data['invoice'] = '';
				$template->data['mail_invoice'] = '';
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/mail_confirm.tpl')) {
					$html = $template->fetch($this->config->get('config_template') . '/template/checkout/mail_confirm.tpl');
				} else {
					$html = $template->fetch('default/template/checkout/mail_confirm.tpl');
				}
				
				// Text 
				$text  = $language->get('mail_new_received') . "\n\n";
				$text .= $language->get('mail_new_order_id') . ' ' . $order_id . "\n";
				$text .= $language->get('mail_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n";
				$text .= $language->get('mail_new_order_status') . ' ' . $order_status_query->row['name'] . "\n\n";
				$text .= $language->get('mail_new_products') . "\n";
				
				foreach ($order_product_query->rows as $result) {
					$text .= $result['quantity'] . 'x ' . $result['name'] . ' (' . $result['model'] . ') ' . html_entity_decode($this->currency->format($result['total'], $order_query->row['currency_code'], $order_query->row['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
					
					$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $result['order_product_id'] . "'");
					
					foreach ($order_option_query->rows as $option) {
						$text .= chr(9) . '-' . $option['name'] . (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
					}
				}
				
				$text .= "\n";

				$text.= $language->get('mail_new_order_total') . "\n";
				
				foreach ($order_total_query->rows as $result) {
					$text .= $result['title'] . ' ' . html_entity_decode($result['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
				}			
				
				$text .= "\n";
				
				if ($order_query->row['comment'] != '') {
					$comment = ($order_query->row['comment'] .  "\n\n" . $comment);
				}
				
				if ($comment) {
					$text .= $language->get('mail_new_comment') . "\n\n";
					$text .= $comment . "\n\n";
				}
			
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
				$mail->setSender($order_query->row['store_name']);
				$mail->setSubject($subject);
				$mail->setHtml($html);
				$mail->setText($text);
				$mail->addAttachment(DIR_IMAGE . $this->config->get('config_logo'));
				$mail->send();
				
				// Send to additional alert emails
				$emails = explode(',', $this->config->get('config_alert_emails'));
				
				foreach ($emails as $email) {
					if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$mail->setTo($email);
						$mail->send();
					}
				}				
			}		
		}
	}
	
	public function update($order_id, $order_status_id, $comment = '', $notify = false) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "language l ON (o.language_id = l.language_id) WHERE o.order_id = '" . (int)$order_id . "' AND o.order_status_id > '0'");
		
		if ($order_query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
	
			if ($notify) {
				$language = new Language($order_query->row['directory']);
				$language->load($order_query->row['filename']);
				$language->load('checkout/checkout');
			
				$subject = sprintf($language->get('mail_update_subject'), html_entity_decode($order_query->row['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
	
				$message  = $language->get('mail_update_order') . ' ' . $order_id . "\n";
				$message .= $language->get('mail_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_query->row['date_added'])) . "\n\n";
				
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_query->row['language_id'] . "'");
				
				if ($order_status_query->num_rows) {
					$message .= $language->get('mail_update_order_status') . "\n\n";
					$message .= $order_status_query->row['name'] . "\n\n";
				}
				
				$message .= $language->get('mail_update_invoice') . "\n";
				$message .= $order_query->row['store_url'] . 'index.php?route=account/invoice&order_id=' . $order_id . "\n\n";
					
				if ($comment) { 
					$message .= $language->get('mail_update_comment') . "\n\n";
					$message .= $comment . "\n\n";
				}
					
				$message .= $language->get('mail_update_footer');

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');				
				$mail->setTo($order_query->row['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($order_query->row['store_name']);
				$mail->setSubject($subject);
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();
			}
		}
	}
	
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");
			
		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}
			
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],				
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],	
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],				
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],	
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip']
			);
		} else {
			return false;	
		}
	}	
}
?>