<?php
class ModelSaleVoucher extends Model {
	public function addVoucher($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "voucher SET code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

      	$voucher_id = $this->db->getLastId();

      	foreach ($data['voucher_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_description SET voucher_id = '" . (int)$voucher_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
      	}
		
		if (isset($data['voucher_product'])) {
      		foreach ($data['voucher_product'] as $product_id) {
        		$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_product SET voucher_id = '" . (int)$voucher_id . "', product_id = '" . (int)$product_id . "'");
      		}			
		}
	}
	
	public function editVoucher($voucher_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "voucher SET code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "' WHERE voucher_id = '" . (int)$voucher_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_description WHERE voucher_id = '" . (int)$voucher_id . "'");

      	foreach ($data['voucher_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_description SET voucher_id = '" . (int)$voucher_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
      	}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_product WHERE voucher_id = '" . (int)$voucher_id . "'");
		
		if (isset($data['voucher_product'])) {
      		foreach ($data['voucher_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_product SET voucher_id = '" . (int)$voucher_id . "', product_id = '" . (int)$product_id . "'");
      		}
		}		
	}
	
	public function deleteVoucher($voucher_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "voucher WHERE voucher_id = '" . (int)$voucher_id . "'");
	}
	
	public function getVoucher($voucher_id) {
      	$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "voucher WHERE voucher_id = '" . (int)$voucher_id . "'");
		
		return $query->row;
	}
	
	public function getVouchers($data = array()) {
		$sql = "SELECT v.voucher_id, v.code, v.from_name, v.from_email, v.to_name, v.to_email, v.amount, v.status, v.date_added, v.date_redeemed FROM " . DB_PREFIX . "voucher v";
		
		$sort_data = array(
			'v.code',
			'v.from_name',
			'v.from_email',
			'v.to_name',
			'v.to_email',
			'v.status',
			'v.date_added',
			'v.date_redeemed'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cd.name";	
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
	
	public function getTotalVouchers() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher");
		
		return $query->row['total'];
	}	
	
	public function getTotalVouchersByVoucherThemeId($voucher_theme_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher WHERE voucher_theme_id = '" . (int)$voucher_theme_id . "'");
		
		return $query->row['total'];
	}		
}
?>