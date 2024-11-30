<?php
namespace Opencart\Admin\Model\Sale;
/**
 * Class Subscription
 *
 * @package Opencart\Admin\Model\Sale
 */
class Subscription extends \Opencart\System\Engine\Model {

	/**
	 *	Add Subscription
	 *
	 *	Create a new subscription record in the database.
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int returns the primary key of the new subscription record
	 */
	public function addSubscription(array $data): int {
		if ($data['trial_status'] && $data['trial_duration']) {
			$trial_remaining = $data['trial_duration'] - 1;
			$remaining = $data['duration'];
		} elseif ($data['duration']) {
			$trial_remaining = $data['trial_duration'];
			$remaining = $data['duration'] - 1;
		} else {
			$trial_remaining = $data['trial_duration'];
			$remaining = $data['duration'];
		}

		if ($data['trial_status'] && $data['trial_duration']) {
			$date_next = date('Y-m-d', strtotime('+' . $data['trial_cycle'] . ' ' . $data['trial_frequency']));
		} else {
			$date_next = date('Y-m-d', strtotime('+' . $data['cycle'] . ' ' . $data['frequency']));
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription` SET `order_id` = '" . (int)$data['order_id'] . "', `store_id` = '" . (int)$data['store_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `payment_address_id` = '" . (int)$data['payment_address_id'] . "', `payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', `shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', `shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) : '') . "', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_remaining` = '" . (int)$trial_remaining . "', `trial_status` = '" . (int)$data['trial_status'] . "', `price` = '" . (float)$data['price'] . "', `frequency` = '" . $this->db->escape($data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `remaining` = '" . (int)$remaining . "', `date_next` = '" . $this->db->escape($date_next) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `affiliate_id` = '" . (int)$data['affiliate_id'] . "', `marketing_id` = '" . (int)$data['marketing_id'] . "', `tracking` = '" . $this->db->escape($data['tracking']) . "', `language_id` = '" . (int)$data['language_id'] . "', `currency_id` = '" . (int)$data['currency_id'] . "', `date_added` = NOW(), `date_modified` = NOW()");

		$subscription_id = $this->db->getLastId();

		foreach ($data['subscription_product'] as $product) {
			$this->model_sale_subscription->addProduct($subscription_id, $product);
		}

		return $subscription_id;
	}

	/**
	 * Edit Subscription
	 *
	 * @param int                  $subscription_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editSubscription(int $subscription_id, array $data): void {
		if ($data['trial_status'] && $data['trial_duration']) {
			$trial_remaining = $data['trial_duration'] - 1;
			$remaining = $data['duration'];
		} elseif ($data['duration']) {
			$trial_remaining = $data['trial_duration'];
			$remaining = $data['duration'] - 1;
		} else {
			$trial_remaining = $data['trial_duration'];
			$remaining = $data['duration'];
		}

		if ($data['trial_status'] && $data['trial_duration']) {
			$date_next = date('Y-m-d', strtotime('+' . $data['trial_cycle'] . ' ' . $data['trial_frequency']));
		} else {
			$date_next = date('Y-m-d', strtotime('+' . $data['cycle'] . ' ' . $data['frequency']));
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `order_id` = '" . (int)$data['order_id'] . "', `store_id` = '" . (int)$data['store_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `payment_address_id` = '" . (int)$data['payment_address_id'] . "', `payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', `shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', `shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) : '') . "', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_remaining` = '" . (int)$trial_remaining . "', `trial_status` = '" . (int)$data['trial_status'] . "', `price` = '" . (float)$data['price'] . "', `frequency` = '" . $this->db->escape($data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `remaining` = '" . (int)$remaining . "', `date_next` = '" . $this->db->escape($date_next) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `affiliate_id` = '" . (int)$data['affiliate_id'] . "', `marketing_id` = '" . (int)$data['marketing_id'] . "', `tracking` = '" . $this->db->escape($data['tracking']) . "', `language_id` = '" . (int)$data['language_id'] . "', `currency_id` = '" . (int)$data['currency_id'] . "', `date_modified` = NOW() WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->model_sale_subscription->deleteProducts($subscription_id);

		foreach ($data['subscription_product'] as $product) {
			$this->model_sale_subscription->addProduct($subscription_id, $product);
		}
	}

	/**
	 * Delete Subscription
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteSubscription(int $subscription_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->deleteProducts($subscription_id);
		$this->deleteHistories($subscription_id);
		$this->deleteLogs($subscription_id);
	}

	/**
	 * Edit Remaining
	 *
	 * @param int $subscription_id
	 * @param int $remaining
	 *
	 * @return void
	 */
	public function editRemaining(int $subscription_id, int $remaining): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `remaining` = '" . (int)$remaining . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Edit Trial Remaining
	 *
	 * @param int $subscription_id
	 * @param int $trial_remaining
	 *
	 * @return void
	 */
	public function editTrialRemaining(int $subscription_id, int $trial_remaining): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `trial_remaining` = '" . (int)$trial_remaining . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Edit Date Next
	 *
	 * @param int    $subscription_id
	 * @param string $date_next
	 *
	 * @return void
	 */
	public function editDateNext(int $subscription_id, string $date_next): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `date_next` = '" . $this->db->escape($date_next) . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Delete Subscription By Customer Payment ID
	 *
	 * @param int $customer_payment_id
	 *
	 * @return void
	 */
	public function deleteSubscriptionByCustomerPaymentId(int $customer_payment_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription` WHERE `customer_payment_id` = '" . (int)$customer_payment_id . "'");
	}

	/**
	 * Get Subscription
	 *
	 * @param int $subscription_id
	 *
	 * @return array<string, mixed>
	 */
	public function getSubscription(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		if ($query->num_rows) {
			return [
				'payment_method'  => $query->row['payment_method'] ? json_decode($query->row['payment_method'], true) : '',
				'shipping_method' => $query->row['shipping_method'] ? json_decode($query->row['shipping_method'], true) : ''
			] + $query->row;
		}

		return [];
	}

	/**
	 * Get Subscription By Order Product ID
	 *
	 * @param int $order_id
	 * @param int $order_product_id
	 *
	 * @return array<string, mixed>
	 */
	public function getSubscriptionByOrderProductId(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "subscription_product` `sp` LEFT JOIN `" . DB_PREFIX . "subscription` `s` ON (`sp`.`subscription_id` = `s`.`subscription_id`) WHERE `sp`.`order_id` = '" . (int)$order_id . "' AND `sp`.`order_product_id` = '" . (int)$order_product_id . "'");

		if ($query->num_rows) {
			return [
				'payment_method'  => $query->row['payment_method'] ? json_decode($query->row['payment_method'], true) : '',
				'shipping_method' => $query->row['shipping_method'] ? json_decode($query->row['shipping_method'], true) : ''
			] + $query->row;
		}

		return [];
	}

	/**
	 * Get Subscriptions
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSubscriptions(array $data): array {
		$sql = "SELECT `s`.`subscription_id`, `s`.*, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS customer, (SELECT `ss`.`name` FROM `" . DB_PREFIX . "subscription_status` `ss` WHERE `ss`.`subscription_status_id` = `s`.`subscription_status_id` AND `ss`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `subscription_status` FROM `" . DB_PREFIX . "subscription` `s` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`s`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_subscription_id'])) {
			$implode[] = "`s`.`subscription_id` = '" . (int)$data['filter_subscription_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "`s`.`order_id` = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_order_product_id'])) {
			$implode[] = "`s`.`order_product_id` = '" . (int)$data['filter_order_product_id'] . "'";
		}

		if (!empty($data['filter_customer_payment_id'])) {
			$implode[] = "`s`.`customer_payment_id` = " . (int)$data['filter_customer_payment_id'];
		}

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "`s`.`customer_id` = " . (int)$data['filter_customer_id'];
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "LCASE(CONCAT(`o`.`firstname`, ' ', `o`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_customer']) . '%') . "'";
		}

		if (!empty($data['filter_date_next'])) {
			$implode[] = "DATE(`s`.`date_next`) = DATE('" . $this->db->escape((string)$data['filter_date_next']) . "')";
		}

		if (!empty($data['filter_subscription_status_id'])) {
			$implode[] = "`s`.`subscription_status_id` = '" . (int)$data['filter_subscription_status_id'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`s`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`s`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			's.subscription_id',
			's.order_id',
			's.reference',
			'customer',
			's.subscription_status',
			's.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `s`.`subscription_id`";
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
	 * Get Total Subscriptions
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function getTotalSubscriptions(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` `s` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`s`.`order_id` = `o`.`order_id`)";

		$implode = [];

		if (!empty($data['filter_subscription_id'])) {
			$implode[] = "`s`.`subscription_id` = '" . (int)$data['filter_subscription_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$implode[] = "`s`.`order_id` = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer_id'])) {
			$implode[] = "`s`.`customer_id` = " . (int)$data['filter_customer_id'];
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "LCASE(CONCAT(`o`.`firstname`, ' ', `o`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_customer']) . '%') . "'";
		}

		if (!empty($data['filter_subscription_status_id'])) {
			$implode[] = "`s`.`subscription_status_id` = '" . (int)$data['filter_subscription_status_id'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`s`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`s`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Subscriptions By Store ID
	 *
	 * @param int $store_id
	 *
	 * @return int
	 */
	public function getTotalSubscriptionsByStoreId(int $store_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` WHERE `store_id` = '" . (int)$store_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Subscriptions By Subscription Status ID
	 *
	 * @param int $subscription_status_id
	 *
	 * @return int
	 */
	public function getTotalSubscriptionsBySubscriptionStatusId(int $subscription_status_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` WHERE `subscription_status_id` = '" . (int)$subscription_status_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Product
	 *
	 * @param int   $subscription_id
	 * @param array $product
	 *
	 * @return void
	 */
	public function addProduct(int $subscription_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_product` SET `subscription_id` = '" . (int)$subscription_id . "', `order_product_id` = '" . (int)$data['order_product_id'] . "', `order_id` = '" . (int)$data['order_id'] . "', `product_id` =  '" . (int)$data['product_id'] . "', `option` = '" . $this->db->escape($data['option'] ? json_encode($data['option']) : '') . "', `quantity` = '" . (int)$data['quantity'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `price` = '" . (float)$data['price'] . "'");
	}

	/**
	 * Delete Product
	 *
	 * @param int $subscription_id
	 *
	 * @return void
	 */
	public function deleteProducts(int $subscription_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/*
	 * Get Subscribed Products
	 *
	 * @param int $subscription_id
	 *
	 * @return array
	 */
	public function getProducts(int $subscription_id): array {
		$subscription_product_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		foreach ($query->rows as $subscription_product) {
			$subscription_product_data[] = ['option' => $subscription_product['option'] ? json_decode($subscription_product['option'], true) : ''] + $subscription_product;
		}

		return $subscription_product_data;
	}

	/**
	 * Add History
	 *
	 * @param int    $subscription_id
	 * @param int    $subscription_status_id
	 * @param string $comment
	 * @param bool   $notify
	 *
	 * @return void
	 */
	public function addHistory(int $subscription_id, int $subscription_status_id, string $comment = '', bool $notify = false): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_history` SET `subscription_id` = '" . (int)$subscription_id . "', `subscription_status_id` = '" . (int)$subscription_status_id . "', `comment` = '" . $this->db->escape($comment) . "', `notify` = '" . (int)$notify . "', `date_added` = NOW()");

		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Delete Histories
	 *
	 * @param int $subscription_id
	 *
	 * @return void
	 */
	public function deleteHistories(int $subscription_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_history` WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Get Histories
	 *
	 * @param int $subscription_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getHistories(int $subscription_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT *, (SELECT `ss`.`name` FROM `" . DB_PREFIX . "subscription_status` `ss` WHERE `ss`.`subscription_status_id` = `sh`.`subscription_status_id` AND `ss`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `subscription_status` FROM `" . DB_PREFIX . "subscription_history` `sh` WHERE `sh`.`subscription_id` = '" . (int)$subscription_id . "' ORDER BY `sh`.`date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Histories
	 *
	 * @param int $subscription_id
	 *
	 * @return int
	 */
	public function getTotalHistories(int $subscription_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_history` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Histories By Subscription Status ID
	 *
	 * @param int $subscription_status_id
	 *
	 * @return int
	 */
	public function getTotalHistoriesBySubscriptionStatusId(int $subscription_status_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_history` WHERE `subscription_status_id` = '" . (int)$subscription_status_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Delete Logs
	 *
	 * @param int $subscription_id
	 *
	 * @return void
	 */
	public function deleteLogs(int $subscription_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_log` WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Get Logs
	 *
	 * @param int $subscription_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getLogs(int $subscription_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_log` WHERE `subscription_id` = '" . (int)$subscription_id . "' ORDER BY `date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Logs
	 *
	 * @param int $subscription_id
	 *
	 * @return int
	 */
	public function getTotalLogs(int $subscription_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_log` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return (int)$query->row['total'];
	}
}
