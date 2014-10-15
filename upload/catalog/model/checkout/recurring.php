<?php
class ModelCheckoutRecurring extends Model {
	public function create($item, $order_id, $description) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring` SET `order_id` = '" . (int)$order_id . "', `date_added` = NOW(), `status` = 6, `product_id` = '" . (int)$item['product_id'] . "', `product_name` = '" . $this->db->escape($item['name']) . "', `product_quantity` = '" . $this->db->escape($item['quantity']) . "', `recurring_id` = '" . (int)$item['recurring_id'] . "', `recurring_name` = '" . $this->db->escape($item['recurring_name']) . "', `recurring_description` = '" . $this->db->escape($description) . "', `recurring_frequency` = '" . $this->db->escape($item['recurring_frequency']) . "', `recurring_cycle` = '" . (int)$item['recurring_cycle'] . "', `recurring_duration` = '" . (int)$item['recurring_duration'] . "', `recurring_price` = '" . (float)$item['recurring_price'] . "', `trial` = '" . (int)$item['recurring_trial'] . "', `trial_frequency` = '" . $this->db->escape($item['recurring_trial_frequency']) . "', `trial_cycle` = '" . (int)$item['recurring_trial_cycle'] . "', `trial_duration` = '" . (int)$item['recurring_trial_duration'] . "', `trial_price` = '" . (float)$item['recurring_trial_price'] . "', `reference` = ''");

		return $this->db->getLastId();
	}

	public function addReference($recurring_id, $ref) {
		$this->db->query("UPDATE " . DB_PREFIX . "order_recurring SET reference = '" . $this->db->escape($ref) . "' WHERE order_recurring_id = '" . (int)$recurring_id . "'");

		if ($this->db->countAffected() > 0) {
			return true;
		} else {
			return false;

		}
	}
}
