<?php
class ModelCheckoutRecurring extends Model {
	public function addRecurring($order_id, $description, $data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring` SET `order_id` = '" . (int)$order_id . "', `date_added` = NOW(), `status` = 6, `product_id` = '" . (int)$data['product_id'] . "', `product_name` = '" . $this->db->escape($data['name']) . "', `product_quantity` = '" . $this->db->escape($data['quantity']) . "', `recurring_id` = '" . (int)$data['recurring_id'] . "', `recurring_name` = '" . $this->db->escape($data['name']) . "', `recurring_description` = '" . $this->db->escape($description) . "', `recurring_frequency` = '" . $this->db->escape($data['frequency']) . "', `recurring_cycle` = '" . (int)$data['cycle'] . "', `recurring_duration` = '" . (int)$data['duration'] . "', `recurring_price` = '" . (float)$data['price'] . "', `trial` = '" . (int)$data['trial'] . "', `trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `reference` = ''");

		return $this->db->getLastId();
	}

	public function editReference($order_recurring_id, $reference) {
		$this->db->query("UPDATE " . DB_PREFIX . "order_recurring SET reference = '" . $this->db->escape($reference) . "' WHERE order_recurring_id = '" . (int)$order_recurring_id . "'");

		if ($this->db->countAffected() > 0) {
			return true;
		} else {
			return false;
		}
	}
}
