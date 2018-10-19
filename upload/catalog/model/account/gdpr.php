<?php
class ModelAccountGdpr extends Model {
	public function addGdpr($customer_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_gdpr` SET `customer_id` = '" . (int)$customer_id . "', `date_added` = NOW()");
	}
}