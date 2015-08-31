<?php
class ModelCheckoutRecurring extends Model {
	public function create($recurring, $order_id, $description) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring` SET `order_id` = '" . (int)$order_id . "', `date_added` = NOW(), `status` = 6, `product_id` = '" . (int)$recurring['product_id'] . "', `product_name` = '" . $this->db->escape($recurring['name']) . "', `product_quantity` = '" . $this->db->escape($recurring['quantity']) . "', `recurring_id` = '" . (int)$recurring['recurring']['recurring_id'] . "', `recurring_name` = '" . $this->db->escape($recurring['recurring']['name']) . "', `recurring_description` = '" . $this->db->escape($description) . "', `recurring_frequency` = '" . $this->db->escape($recurring['recurring']['frequency']) . "', `recurring_cycle` = '" . (int)$recurring['recurring']['cycle'] . "', `recurring_duration` = '" . (int)$recurring['recurring']['duration'] . "', `recurring_price` = '" . (float)$recurring['recurring']['price'] . "', `trial` = '" . (int)$recurring['recurring']['trial'] . "', `trial_frequency` = '" . $this->db->escape($recurring['recurring']['trial_frequency']) . "', `trial_cycle` = '" . (int)$recurring['recurring']['trial_cycle'] . "', `trial_duration` = '" . (int)$recurring['recurring']['trial_duration'] . "', `trial_price` = '" . (float)$recurring['recurring']['trial_price'] . "', `reference` = ''");

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
