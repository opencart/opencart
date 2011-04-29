<?php
class ModelAccountVoucher extends Model {
	public function addVoucher($order_id, $data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "voucher SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape(rand()) . "', from_name = '" . $this->db->escape($data['from_name']) . "', from_email = '" . $this->db->escape($data['from_email']) . "', to_name = '" . $this->db->escape($data['to_name']) . "', to_email = '" . $this->db->escape($data['to_email']) . "', message = '" . $this->db->escape($data['message']) . "', amount = '" . (float)$data['amount'] . "', voucher_theme_id = '" . (int)$data['voucher_theme_id'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}
	
	public function getVoucher($voucher_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "voucher WHERE voucher_id = '" . (int)$voucher_id . "'");
		
		return $query->row;
	}
	
	public function getVouchers($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		
		return $query->rows;
	}	
	
	public function getTotalVouchers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	
		return $query->row['total'];
	}
}
?>