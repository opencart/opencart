<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Transaction
 *
 * @package Opencart\Catalog\Model\Account
 */
class Transaction extends \Opencart\System\Engine\Model {
	/**
	 * Add Transaction
	 *
	 * @param int    $customer_id
	 * @param int    $order_id
	 * @param string $description
	 * @param float  $amount
	 *
	 * @return void
	 */
	public function addTransaction(int $customer_id, int $order_id, string $description, float $amount): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_transaction` SET `customer_id` = '" . (int)$customer_id . "', `order_id` = '" . (int)$order_id . "', `description` = '" . $this->db->escape($description) . "', `amount` = '" . (float)$amount . "', `date_added` = NOW()");
	}

	/**
	 * Delete Transaction
	 *
	 * @param int $customer_id
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteTransaction(int $customer_id, int $order_id = 0): void {
		$sql = "DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'";

		if ($order_id) {
			$sql .= " AND `order_id` = '" . (int)$order_id . "'";
		}

		$this->db->query($sql);
	}

	/**
	 * Delete Transaction By Order ID
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteTransactionByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "' AND `amount` < 0");
	}

	/**
	 * Get Transactions
	 *
	 * @param int                  $customer_id
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getTransactions(int $customer_id, array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'";

		$sort_data = [
			'amount',
			'description',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `date_added`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Transactions
	 *
	 * @param int $customer_id
	 *
	 * @return int
	 */
	public function getTotalTransactions(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Transactions By Order ID
	 *
	 * @param int $order_id
	 *
	 * @return int
	 */
	public function getTotalTransactionsByOrderId(int $order_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Transaction Total
	 *
	 * @param int $customer_id
	 *
	 * @return float
	 */
	public function getTransactionTotal(int $customer_id): float {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "' GROUP BY `customer_id`");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}
}
