<?php
class ModelSaleReturn extends Model {
	public function addReturn($data) {
      	$this->db->query("INSERT INTO `" . DB_PREFIX . "return` SET order_id = '" . (int)$data['order_id'] . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', return_status_id = '" . (int)$data['return_status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_added = NOW(), date_modified = NOW()");
      	
      	$return_id = $this->db->getLastId();
      	
      	if (isset($data['return_product'])) {		
      		foreach ($data['return_product'] as $return_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "return_product SET return_id = '" . (int)$return_id . "', product_id = '" . (int)$return_product['product_id'] . "', name = '" . $this->db->escape($return_product['name']) . "', model = '" . $this->db->escape($return_product['model']) . "', quantity = '" . (int)$return_product['quantity'] . "', return_reason_id = '" . (int)$return_product['return_reason_id'] . "', opened = '" . (int)$return_product['opened'] . "', comment = '" . $this->db->escape($return_product['comment']) . "', return_action_id = '" . (int)$return_product['return_action_id'] . "'");
			}
		}
	}
	
	public function editReturn($return_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "return`SET order_id = '" . (int)$data['order_id'] . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', return_status_id = '" . (int)$data['return_status_id'] . "', comment = '" . $this->db->escape($data['comment']) . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "return_product WHERE return_id = '" . (int)$return_id . "'");
      	
		if (isset($data['return_product'])) {		
      		foreach ($data['return_product'] as $return_product) {	
      			$this->db->query("INSERT INTO " . DB_PREFIX . "return_product SET return_id = '" . (int)$return_id . "', product_id = '" . (int)$return_product['product_id'] . "', name = '" . $this->db->escape($return_product['name']) . "', model = '" . $this->db->escape($return_product['model']) . "', quantity = '" . (int)$return_product['quantity'] . "', return_reason_id = '" . (int)$return_product['return_reason_id'] . "', opened = '" . (int)$return_product['opened'] . "', comment = '" . $this->db->escape($return_product['comment']) . "', return_action_id = '" . (int)$return_product['return_action_id'] . "'");
			}
		}
	}
	
	public function deleteReturn($return_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return` WHERE return_id = '" . (int)$return_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "return_product WHERE return_id = '" . (int)$return_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "return_history WHERE return_id = '" . (int)$return_id . "'");
	}
	
	public function getReturn($return_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = r.customer_id) AS customer FROM `" . DB_PREFIX . "return`r WHERE r.return_id = '" . (int)$return_id . "'");
	
		return $query->row;
	}
		
	public function getReturns($data = array()) {
		$sql = "SELECT *, CONCAT(r.firstname, ' ', r.lastname) AS customer, (SELECT SUM(rp.quantity) FROM " . DB_PREFIX . "return_product rp WHERE rp.return_id = r.return_id GROUP BY rp.return_id) AS quantity, (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM `" . DB_PREFIX . "return`r";

		$implode = array();
		
		if (isset($data['filter_return_id']) && !is_null($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}
		
		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
		}
						
		if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
			$implode[] = "LCASE(CONCAT(r.firstname, ' ', r.lastname)) LIKE '" . $this->db->escape(strtolower($data['filter_customer'])) . "%'";
		}
		
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$implode[] = "(SELECT SUM(rp.quantity) FROM " . DB_PREFIX . "return_product rp WHERE rp.return_id = r.return_id GROUP BY rp.return_id) = '" . (int)$data['filter_quantity'] . "'";
		}	
				
		if (isset($data['filter_return_status_id']) && !is_null($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}	
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (isset($data['filter_date_modified']) && !is_null($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
				
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'r.return_id',
			'r.order_id',
			'customer',
			'quantity',
			'status',
			'r.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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
						
	public function getTotalReturns($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return`r";
		
		$implode = array();
		
		if (isset($data['filter_return_id']) && !is_null($data['filter_return_id'])) {
			$implode[] = "r.return_id = '" . (int)$data['filter_return_id'] . "'";
		}
				
		if (isset($data['filter_customer']) && !is_null($data['filter_customer'])) {
			$implode[] = "LCASE(CONCAT(r.firstname, ' ', r.lastname)) LIKE '" . $this->db->escape(strtolower($data['filter_customer'])) . "%'";
		}
		
		if (isset($data['filter_order_id']) && !is_null($data['filter_order_id'])) {
			$implode[] = "r.order_id = '" . $this->db->escape($data['filter_order_id']) . "'";
		}
		
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$implode[] = "(SELECT SUM(rp.quantity) FROM " . DB_PREFIX . "return_product rp WHERE rp.return_id = r.return_id GROUP BY rp.return_id) = '" . (int)$data['filter_quantity'] . "'";
		}	
				
		if (isset($data['filter_return_status_id']) && !is_null($data['filter_return_status_id'])) {
			$implode[] = "r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		}	
		
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if (isset($data['filter_date_modified']) && !is_null($data['filter_date_modified'])) {
			$implode[] = "DATE(r.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}
				
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
		
	public function getTotalReturnsByReturnStatusId($return_status_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return`WHERE return_status_id = '" . (int)$return_status_id . "'");
				
		return $query->row['total'];
	}	

	public function editReturnProduct($return_id, $return_product_id, $return_action_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "return_product SET return_action_id = '" . (int)$return_action_id . "' WHERE return_id = '" . (int)$return_id . "' AND return_product_id = '" . (int)$return_product_id . "'");
	}
	
	public function getReturnProducts($return_id) {
		$query = $this->db->query("SELECT *, (SELECT rr.name FROM " . DB_PREFIX . "return_reason rr WHERE rr.return_reason_id = rp.return_reason_id AND rr.language_id = '" . (int)$this->config->get('config_language_id') . "') AS reason, (SELECT ra.name FROM " . DB_PREFIX . "return_action ra WHERE ra.return_action_id = rp.return_action_id AND ra.language_id = '" . (int)$this->config->get('config_language_id') . "') AS action FROM " . DB_PREFIX . "return_product rp WHERE rp.return_id = '" . $return_id . "'");
		
		return $query->rows;	
	}	
	
	public function getTotalReturnProducts($return_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return`WHERE return_id = '" . (int)$return_id . "'");
		
		return $query->rows;	
	}
			
	public function getTotalReturnProductsByReturnReasonId($return_reason_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_product WHERE return_reason_id = '" . (int)$return_reason_id . "'");
				
		return $query->row['total'];
	}	
	
	public function getTotalReturnProductsByReturnActionId($return_action_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_product WHERE return_action_id = '" . (int)$return_action_id . "'");
				
		return $query->row['total'];
	}	
	
	public function addReturnHistory($return_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET return_status_id = '" . (int)$data['return_status_id'] . "', date_modified = NOW() WHERE return_id = '" . (int)$return_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "return_history SET return_id = '" . (int)$return_id . "', return_status_id = '" . (int)$data['return_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', date_added = NOW()");

      	if ($data['notify']) {
        	$return_query = $this->db->query("SELECT *, rs.name AS status FROM `" . DB_PREFIX . "return` r LEFT JOIN " . DB_PREFIX . "return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
			if ($return_query->num_rows) {
				$this->language->load('mail/return');

				$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $return_id);

				$message  = $this->language->get('text_return_id') . ' ' . $return_id . "\n";
				$message .= $this->language->get('text_date_added') . ' ' . date($this->language->get('date_format_short'), strtotime($return_query->row['date_added'])) . "\n\n";
				$message .= $this->language->get('text_return_status') . "\n";
				$message .= $return_query->row['status'] . "\n\n";

				if ($data['comment']) {
					$message .= $this->language->get('text_comment') . "\n\n";
					$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				}

				$message .= $this->language->get('text_footer');

				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');
				$mail->setTo($return_query->row['email']);
				$mail->setFrom($this->config->get('config_email'));
	    		$mail->setSender($this->config->get('config_name'));
	    		$mail->setSubject($subject);
	    		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
	    		$mail->send();
			}
		}
	}
		
	public function getReturnHistories($return_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM " . DB_PREFIX . "return_history rh LEFT JOIN " . DB_PREFIX . "return_status rs ON rh.return_status_id = rs.return_status_id WHERE rh.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}	
	
	public function getTotalReturnHistories($return_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_id = '" . (int)$return_id . "'");

		return $query->row['total'];
	}	
		
	public function getTotalReturnHistoriesByReturnStatusId($return_status_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_history WHERE return_status_id = '" . (int)$return_status_id . "' GROUP BY order_id");

		return $query->row['total'];
	}			
}
?>