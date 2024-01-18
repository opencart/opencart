<?php
namespace Opencart\Admin\Model\Extension\Opencart\Total;
/**
 * Class Subscription
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Total
 */
class Subscription extends \Opencart\System\Engine\Model {
	/**
	 * Add Subscription Discount
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addSubscriptionDiscount(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_discount` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `discount` = '" . (float)$data['discount'] . "', `total` = '" . (float)$data['total'] . "', `date_start` = '" . $this->db->escape((string)$data['date_start']) . "', `date_end` = '" . $this->db->escape((string)$data['date_end']) . "', `uses_total` = '" . (int)$data['uses_total'] . "', `uses_customer` = '" . (int)$data['uses_customer'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_added` = NOW()");

		$subscription_discount_id = $this->db->getLastId();

		if (isset($data['subscription_discount_product'])) {
			foreach ($data['subscription_discount_product'] as $product_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_discount_product` SET `subscription_discount_id` = '" . (int)$subscription_discount_id . "', `product_id` = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['subscription_discount_recurring'])) {
			foreach ($data['subscription_discount_recurring'] as $recurring_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_discount_recurring` SET `subscription_discount_id` = '" . (int)$subscription_discount_id . "', `recurring_id` = '" . (int)$recurring_id . "'");
			}
		}

		return $subscription_discount_id;
	}

	/**
	 * Edit Subscription Discount
	 *
	 * @param int                  $subscription_discount_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editSubscriptionDiscount(int $subscription_discount_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription_discount` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `discount` = '" . (float)$data['discount'] . "', `total` = '" . (float)$data['total'] . "', `date_start` = '" . $this->db->escape((string)$data['date_start']) . "', `date_end` = '" . $this->db->escape((string)$data['date_end']) . "', `uses_total` = '" . (int)$data['uses_total'] . "', `uses_customer` = '" . (int)$data['uses_customer'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_discount_product` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");

		if (isset($data['subscription_discount_product'])) {
			foreach ($data['subscription_discount_product'] as $product_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_discount_product` SET `subscription_discount_id` = '" . (int)$subscription_discount_id . "', `product_id` = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_discount_recurring` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");

		if (isset($data['subscription_discount_recurring'])) {
			foreach ($data['subscription_discount_recurring'] as $recurring_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_discount_recurring` SET `subscription_discount_id` = '" . (int)$subscription_discount_id . "', `recurring_id` = '" . (int)$recurring_id . "'");
			}
		}
	}

	/**
	 * Delete subscription Discount
	 *
	 * @param int $subscription_discount_id
	 *
	 * @return void
	 */
	public function deleteSubscriptionDiscount(int $subscription_discount_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_discount` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_discount_product` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_discount_recurring` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_discount_history` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");
	}

	/**
	 * Get Subscription Discount
	 *
	 * @param int $subscription_discount_id
	 *
	 * @return array<string, mixed>
	 */
	public function getSubscriptionDiscount(int $subscription_discount_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "subscription_discount` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");

		return $query->row;
	}

	/**
	 * Get Subscription Discount By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getSubscriptionDiscountByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "subscription_discount` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	/**
	 * Get Subscription Discounts
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSubscriptionDiscounts(array $data = []): array {
		$sql = "SELECT `subscription_discount_id`, `name`, `code`, `discount`, `date_start`, `date_end`, `status` FROM `" . DB_PREFIX . "subscription_discount`";

		$sort_data = [
			'name',
			'code',
			'discount',
			'date_start',
			'date_end',
			'status'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `name`";
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
	 * Get Products
	 *
	 * @param int $subscription_discount_id
	 *
	 * @return array<int, int>
	 */
	public function getProducts(int $subscription_discount_id): array {
		$subscription_discount_product_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_discount_product` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");

		foreach ($query->rows as $result) {
			$subscription_discount_product_data[] = $result['product_id'];
		}

		return $subscription_discount_product_data;
	}

	/**
	 * Get Recurring Renewals
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, int>
	 */
	public function getRecurringRenewals(array $data): array {
		$subscription_discount_recurring_data = [];

		$sql = "SELECT * FROM `" . DB_PREFIX . "subscription_discount_recurring` `sdr` LEFT JOIN `" . DB_PREFIX . "recurring` `r` ON (`r`.`recurring_id` = `sdr`.`recurring_id`)";

		$implode = [];

		if (!empty($data['subscription_discount_id'])) {
			$implode[] = "`sdr`.`subscription_discount_id` = '" . (int)$subscription_discount_id . "'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(`r`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		$implode[] = "`r`.`status` = '1'";

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
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

		foreach ($query->rows as $result) {
			$subscription_discount_recurring_data[] = $result['recurring_id'];
		}

		return $subscription_discount_recurring_data;
	}

	/**
	 * Get Recurring
	 *
	 * @param int $subscription_discount_id
	 *
	 * @return array<int, int>
	 */
	public function getRecurring(int $subscription_discount_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_discount` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");

		return $query->row;
	}

	/**
	 * @return int
	 */
	public function getTotalSubscriptionDiscounts(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_discount`");

		return (int)$query->row['total'];
	}

	/**
	 * Get Histories
	 *
	 * @param int $subscription_discount_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getHistories(int $subscription_discount_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `sdh`.`order_id`, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `sdh`.`amount`, `sdh`.`date_added` FROM `" . DB_PREFIX . "subscription_discount_history` `sdh` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`sdh`.`customer_id` = `c`.`customer_id`) WHERE `sdh`.`subscription_discount_id` = '" . (int)$subscription_discount_id . "' ORDER BY `sdh`.`date_added` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Histories
	 *
	 * @param int $subscription_discount_id
	 *
	 * @return int
	 */
	public function getTotalHistories(int $subscription_discount_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_discount_history` WHERE `subscription_discount_id` = '" . (int)$subscription_discount_id . "'");

		return (int)$query->row['total'];
	}
}
