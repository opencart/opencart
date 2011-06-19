<?php
class ModelAccountReturn extends Model {
	public function addReturn($data) {			      	
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return` SET customer_id = '" . (int)$this->customer->getId() . "', order_id = '" . (int)$data['order_id'] . "', date_ordered = '" . $this->db->escape($data['date_ordered']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', return_status_id = '" . (int)$this->config->get('config_return_status_id') . "', comment = '" . $this->db->escape($data['comment']) . "', date_added = NOW(), date_modified = NOW()");
      	
		$return_id = $this->db->getLastId();
		
		foreach ($data['return_product'] as $return_product) {
      		$this->db->query("INSERT INTO " . DB_PREFIX . "return_product SET return_id = '" . (int)$return_id . "', name = '" . $this->db->escape($return_product['name']) . "', model = '" . $this->db->escape($return_product['model']) . "', quantity = '" . (int)$return_product['quantity'] . "', return_reason_id = '" . (int)$return_product['return_reason_id'] . "', opened = '" . (int)$return_product['opened'] . "', comment = '" . $this->db->escape($return_product['comment']) . "'");
		}
	}
	
	public function getReturn($return_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return`WHERE return_id = '" . (int)$return_id . "' AND customer_id = '" . $this->customer->getId() . "'");
		
		return $query->row;
	}
	
	public function getReturns($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
				
		$query = $this->db->query("SELECT r.return_id, r.order_id, r.firstname, r.lastname, rs.name as status, r.date_added FROM `" . DB_PREFIX . "return` r LEFT JOIN " . DB_PREFIX . "return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.customer_id = '" . $this->customer->getId() . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.return_id DESC LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
			
	public function getTotalReturns() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "return`WHERE customer_id = '" . $this->customer->getId() . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalReturnProductsByReturnId($return_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "return_product WHERE return_id = '" . (int)$return_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getReturnProducts($return_id) {
		$query = $this->db->query("SELECT *, (SELECT rr.name FROM " . DB_PREFIX . "return_reason rr WHERE rr.return_reason_id = rp.return_reason_id AND rr.language_id = '" . (int)$this->config->get('config_language_id') . "') AS reason, (SELECT ra.name FROM " . DB_PREFIX . "return_action ra WHERE ra.return_action_id = rp.return_action_id AND ra.language_id = '" . (int)$this->config->get('config_language_id') . "') AS action FROM " . DB_PREFIX . "return_product rp WHERE rp.return_id = '" . $return_id . "'");
		
		return $query->rows;	
	}	
	
	public function getReturnHistories($return_id) {
		$query = $this->db->query("SELECT rh.date_added, rs.name AS status, rh.comment, rh.notify FROM " . DB_PREFIX . "return_history rh LEFT JOIN " . DB_PREFIX . "return_status rs ON rh.return_status_id = rs.return_status_id WHERE rh.return_id = '" . (int)$return_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY rh.date_added ASC");

		return $query->rows;
	}			
}
?>