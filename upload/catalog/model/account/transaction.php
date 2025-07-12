<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Transaction
 *
 * Can be called using $this->load->model('account/transaction');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Transaction extends \Opencart\System\Engine\Model {
	/**
	 * Add Transaction
	 *
	 * Create a new transaction record in the database.
	 *
	 * @param int    $customer_id primary key of the customer record
	 * @param int    $order_id    primary key of the order record
	 * @param string $description
	 * @param float  $amount
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/transaction');
	 *
	 * $this->model_account_transaction->addTransaction($customer_id, $order_id, $description, $amount);
	 */
	public function addTransaction(int $customer_id, int $order_id, string $description, float $amount): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_transaction` SET `customer_id` = '" . (int)$customer_id . "', `order_id` = '" . (int)$order_id . "', `description` = '" . $this->db->escape($description) . "', `amount` = '" . (float)$amount . "', `date_added` = NOW()");
	}

	/**
	 * Delete Transactions
	 *
	 * Delete customer transaction records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $order_id    primary key of the order record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/transaction');
	 *
	 * $this->model_account_transaction->deleteTransactions($customer_id, $order_id);
	 */
	public function deleteTransactions(int $customer_id, int $order_id = 0): void {
		$sql = "DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'";

		if ($order_id) {
			$sql .= " AND `order_id` = '" . (int)$order_id . "'";
		}

		$this->db->query($sql);
	}

	/**
	 * Delete Transactions By Order ID
	 *
	 * Delete customer transactions by order record in the database.
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/transaction');
	 *
	 * $this->model_account_transaction->deleteTransactionsByOrderId($order_id);
	 */
	public function deleteTransactionsByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "' AND `amount` < 0");
	}

	/**
	 * Get Transactions
	 *
	 * Get the record of the customer transaction records in the database.
	 *
	 * @param int                  $customer_id primary key of the customer record
	 * @param array<string, mixed> $data        array of filters
	 *
	 * @return array<int, array<string, mixed>> transaction records that have customer ID
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'date_added',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('account/transaction');
	 *
	 * $results = $this->model_account_transaction->getTransactions($customer_id, $filter_data);
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
	 * Get the total number of total customer transaction records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of transaction records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('account/transaction');
	 *
	 * $transaction_total = $this->model_account_transaction->getTotalTransactions($customer_id);
	 */
	public function getTotalTransactions(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Transactions By Order ID
	 *
	 * Get the total number of total customer transactions by order records in the database.
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return int total number of transaction records that have order ID
	 *
	 * @example
	 *
	 * $this->load->model('account/transaction');
	 *
	 * $transaction_total = $this->model_account_transaction->getTotalTransactionsByOrderId($order_id);
	 */
	public function getTotalTransactionsByOrderId(int $order_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Transaction Total
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $this->load->model('account/transaction');
	 *
	 * $transaction_total = $this->model_account_transaction->getTransactionTotal($customer_id);
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
