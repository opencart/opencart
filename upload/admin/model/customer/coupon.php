<?php
class ModelCustomerCoupon extends Model {
	public function addCoupon($data) {
      	$this->db->query("INSERT INTO coupon SET code = '" . $this->db->escape(@$data['code']) . "', discount = '" . (float)@$data['discount'] . "', prefix = '" . $this->db->escape(@$data['prefix']) . "', shipping = '" . $this->db->escape(@$data['shipping']) . "', date_start = '" . $this->db->escape(@$data['date_start']) . "', date_end = '" . $this->db->escape(@$data['date_end']) . "', uses_total = '" . (int)@$data['uses_total'] . "', uses_customer = '" . (int)@$data['uses_customer'] . "', status = '" . (int)@$data['status'] . "', date_added = NOW()");

      	$coupon_id = $this->db->getLastId();

      	foreach ($data['coupon_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO coupon_description SET coupon_id = '" . (int)$coupon_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
      	}
	}
	
	public function editCoupon($coupon_id, $data) {
		$this->db->query("UPDATE coupon SET code = '" . $this->db->escape(@$data['code']) . "', discount = '" . (float)@$data['discount'] . "', prefix = '" . $this->db->escape(@$data['prefix']) . "', shipping = '" . $this->db->escape(@$data['shipping']) . "', date_start = '" . $this->db->escape(@$data['date_start']) . "', date_end = '" . $this->db->escape(@$data['date_end']) . "', uses_total = '" . (int)@$data['uses_total'] . "', uses_customer = '" . (int)@$data['uses_customer'] . "', status = '" . (int)@$data['status'] . "' WHERE coupon_id = '" . (int)$coupon_id . "'");

		$this->db->query("DELETE FROM coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");

      	foreach ($data['coupon_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO coupon_description SET coupon_id = '" . (int)$coupon_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
      	}
	}
	
	public function deleteCoupon($coupon_id) {
      	$this->db->query("DELETE FROM coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
      	$this->db->query("DELETE FROM coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
	}
	
	public function getCoupon($coupon_id) {
      	$query = $this->db->query("SELECT DISTINCT * FROM coupon WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		return $query->row;
	}
	
	public function getCoupons($data = array()) {
		$sql = "SELECT c.coupon_id, cd.name, c.code, c.discount, c.prefix, c.date_start, c.date_end, c.status FROM coupon c LEFT JOIN coupon_description cd ON (c.coupon_id = cd.coupon_id) WHERE cd.language_id = '" . (int)$this->language->getId() . "'";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);	
		} else {
			$sql .= " ORDER BY cd.name";
		}
			
		if (isset($data['order'])) {
			$sql .= " " . $this->db->escape($data['order']);
		} else {
			$sql .= " ASC";
		}
			
		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getCouponDescriptions($coupon_id) {
		$coupon_description_data = array();
		
		$query = $this->db->query("SELECT * FROM coupon_description WHERE coupon_id = '" . (int)$coupon_id . "'");
		
		foreach ($query->rows as $result) {
			$coupon_description_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}
		
		return $coupon_description_data;
	}
		
	public function getTotalCoupons() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM coupon");
		
		return $query->row['total'];
	}		
}
?>