<?php
class ModelSaleFraud extends \Engine\Model {
	public function getFraud($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_fraud` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}
}