<?php
class ModelAccountRecurring extends Model {
	public function getOrderRecurring($order_recurring_id) {
		$query = $this->db->query("SELECT `or`.*,`o`.`payment_method`,`o`.`payment_code`,`o`.`currency_code` FROM `" . DB_PREFIX . "order_recurring` `or` LEFT JOIN `" . DB_PREFIX . "order` `o` ON `or`.`order_id` = `o`.`order_id` WHERE `or`.`order_recurring_id` = '" . (int)$order_recurring_id . "' AND `o`.`customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->row;
	}

	public function getOrderRecurrings($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT `or`.*,`o`.`payment_method`,`o`.`currency_id`,`o`.`currency_value` FROM `" . DB_PREFIX . "order_recurring` `or` LEFT JOIN `" . DB_PREFIX . "order` `o` ON `or`.`order_id` = `o`.`order_id` WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "' ORDER BY `o`.`order_id` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getOrderRecurringByReference($reference) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_recurring` WHERE `reference` = '" . $this->db->escape($reference) . "'");

		return $query->row;
	}

	public function getOrderRecurringTransactions($order_recurring_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_recurring_transaction` WHERE `order_recurring_id` = '" . (int)$order_recurring_id . "'");

		return $query->rows;
	}

	public function getTotalOrderRecurrings() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_recurring` `or` LEFT JOIN `" . DB_PREFIX . "order` `o` ON `or`.`order_id` = `o`.`order_id` WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
	
	public function addOrderRecurringTransaction($order_recurring_id, $type) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$order_recurring_id . "', `date_added` = NOW(), `type` = '" . (int)$type . "'");
	}	
	
	public function editOrderRecurringStatus($order_recurring_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = '" . (int)$status . "' WHERE `order_recurring_id` = '" . (int)$order_recurring_id . "'");
	}	
}