<?php
class ModelCheckoutRecurring extends Model {
	public function addRecurring($order_id, $description, $item) {

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring` SET 
		`order_id` = '" . (int)$order_id . "', 
		`date_added` = NOW(), 
		`status` = 6, 
		`product_id` = '" . (int)$item['product_id'] . "', 
		`product_name` = '" . $this->db->escape((string)$item['name']) . "', 
		`product_quantity` = '" . (int)$item['quantity'] . "', 
		`recurring_id` = '" . (int)$item['recurring']['recurring_id'] . "', 
		`recurring_name` = '" . $this->db->escape((string)$item['recurring']['name']) . "', 
		`recurring_description` = '" . $this->db->escape($description) . "', 
		`recurring_frequency` = '" . $this->db->escape((string)$item['recurring']['frequency']) . "', 
		`recurring_cycle` = '" . (int)$item['recurring']['cycle'] . "', 
		`recurring_duration` = '" . (int)$item['recurring']['duration'] . "', 
		`recurring_price` = '" . (float)$item['recurring']['price'] . "', 
		`trial` = '" . (int)$item['recurring']['trial'] . "', 
		`trial_frequency` = '" . $this->db->escape((string)$item['recurring']['trial_frequency']) . "', 
		`trial_cycle` = '" . (int)$item['recurring']['trial_cycle'] . "', 
		`trial_duration` = '" . (int)$item['recurring']['trial_duration'] . "', 
		`trial_price` = '" . (float)$item['recurring']['trial_price'] . "', 
		`reference` = ''");

		return $this->db->getLastId();
	}

	public function addReference($order_recurring_id, $reference) {
		$this->db->query("REPLACE INTO `" . DB_PREFIX . "order_recurring` SET `reference` = '" . $this->db->escape($reference) . "', `order_recurring_id` = '" . (int)$order_recurring_id . "', `date_added` = NOW()");
	}

	public function editReference($order_recurring_id, $reference) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `reference` = '" . $this->db->escape($reference) . "' WHERE `order_recurring_id` = '" . (int)$order_recurring_id . "' LIMIT 1");

		if ($this->db->countAffected() > 0) {
			return true;
		} else {
			return false;
		}
	}
}
