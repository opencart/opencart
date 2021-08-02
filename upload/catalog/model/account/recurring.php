<?php
namespace Opencart\Catalog\Model\Account;
class Recurring extends \Opencart\System\Engine\Model {
	public function getRecurring(int $order_recurring_id): array {
		$query = $this->db->query("SELECT `or`.*, `o`.`payment_method`, `o`.`payment_code`, `o`.`currency_code` FROM `" . DB_PREFIX . "order_recurring` `or` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`or`.`order_id` = `o`.`order_id`) WHERE `or`.`order_recurring_id` = '" . (int)$order_recurring_id . "' AND `o`.`customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->row;
	}

	public function getRecurrings(int $start = 0, int $limit = 20): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT `o`.*, `o`.`payment_method`, `o`.`currency_id`, `o`.`currency_value` FROM `" . DB_PREFIX . "order_recurring` `or` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`or`.`order_id` = o.`order_id`) WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "' ORDER BY `o`.`order_id` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getRecurringByReference(string $reference): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_recurring` WHERE `reference` = '" . $this->db->escape($reference) . "'");

		return $query->row;
	}

	public function getRecurringTransactions(int $order_recurring_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_recurring_transaction` WHERE `order_recurring_id` = '" . (int)$order_recurring_id . "'");

		return $query->rows;
	}

	public function getTotalRecurrings(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_recurring` `or` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`or`.`order_id` = `o`.`order_id`) WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
	
	public function addRecurringTransaction(int $order_recurring_id, string $type): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$order_recurring_id . "', `type` = '" . (int)$type . "', `date_added` = NOW()");
	}	
	
	public function editRecurringStatus(int $order_recurring_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = '" . (bool)$status . "' WHERE `order_recurring_id` = '" . (int)$order_recurring_id . "'");
	}	
}
