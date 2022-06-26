<?php
namespace Opencart\Catalog\Model\Account;
class Subscription extends \Opencart\System\Engine\Model {
	public function editStatus(int $subscription_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `status` = '" . (bool)$status . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	public function getSubscription(int $subscription_id): array {
		$query = $this->db->query("SELECT `s`.*, `o`.`payment_method`, `o`.`payment_code`, `o`.`currency_code` FROM `" . DB_PREFIX . "subscription` `s` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`s`.`order_id` = `o`.`order_id`) WHERE `s`.`subscription_id` = '" . (int)$subscription_id . "' AND `o`.`customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->row;
	}

	public function getSubscriptions(int $start = 0, int $limit = 20): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT `o`.*, `o`.`payment_method`, `o`.`currency_id`, `o`.`currency_value` FROM `" . DB_PREFIX . "subscription` `s` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`s`.`order_id` = o.`order_id`) WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "' ORDER BY `o`.`order_id` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getSubscriptionByReference(string $reference): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` WHERE `reference` = '" . $this->db->escape($reference) . "'");

		return $query->row;
	}

	public function getTotalSubscriptions(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` `s` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`s`.`order_id` = `o`.`order_id`) WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "'");

		return (int)$query->row['total'];
	}

	public function getTransactions(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_transaction` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return $query->rows;
	}

	public function addTransaction(int $subscription_id, int $order_id, int $transaction_id, string $description, float $amount, string $type, string $payment_method, string $payment_code): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_transaction` SET `subscription_id` = '" . (int)$subscription_id . "', `order_id` = '" . (int)$order_id . "', `transaction_id` = '" . (int)$transaction_id . "', `description` = '" . $this->db->escape($description) . "', `amount` = '" . (float)$amount . "', `type` = '" . (int)$type . "', `payment_method` = '" . $this->db->escape($payment_method) . "', `payment_code` = '" . $this->db->escape($payment_code) . "', `date_added` = NOW()");
	}
}
