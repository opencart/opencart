<?php
class ControllerModuleEventDemo extends Controller {
	public function pointsOnRegister($data) {
		$customer_id = $data['customer_id'];

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '0', points = '10', description = '10 points for registering', date_added = NOW()");
	}
}