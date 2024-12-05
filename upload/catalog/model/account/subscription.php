<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Subscription
 *
 * @package Opencart\Catalog\Model\Account
 */
class Subscription extends \Opencart\System\Engine\Model {
	/**
	 * Get Subscription
	 *
	 * @param int $subscription_id
	 *
	 * @return array<string, mixed>
	 */
	public function getSubscription(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

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
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSubscriptions(int $start = 0, int $limit = 20): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `subscription_status_id` > '0' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' ORDER BY `subscription_id` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Subscriptions
	 *
	 * @return int
	 */
	public function getTotalSubscriptions(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `subscription_status_id` > '0' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * Get Total Subscription By Shipping Address ID
	 *
	 * @param int $address_id
	 *
	 * @return int
	 */
	public function getTotalSubscriptionByShippingAddressId(int $address_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `shipping_address_id` = '" . (int)$address_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Subscription By Payment Address ID
	 *
	 * @param int $address_id
	 *
	 * @return int
	 */
	public function getTotalSubscriptionByPaymentAddressId(int $address_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `payment_address_id` = '" . (int)$address_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Subscription By Order Product ID
	 *
	 * @param int $order_id
	 * @param int $order_product_id
	 *
	 * @return array<string, mixed>
	 */
	public function getProductByOrderProductId(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "subscription_product` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	/**
     * Get Subscription Products
	 *
	 * @param int $address_id
	 *
	 * @return int
	 */
	public function getProducts(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Products
	 *
	 * @param int $subscription_id
	 *
	 * @return int
	 */
	public function getTotalProducts(int $subscription_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Options
	 *
	 * @param int $subscription_id
	 * @param int $subscription_product_id
	 *
	 * @return array<string, mixed>
	 */
	public function getOptions(int $subscription_id, int $subscription_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_option` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `subscription_product_id` = '" . (int)$subscription_product_id . "'");

		return $query->rows;
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

		$query = $this->db->query("SELECT `sh`.`date_added`, `ss`.`name` AS `status`, `sh`.`comment`, `sh`.`notify` FROM `" . DB_PREFIX . "subscription_history` `sh` LEFT JOIN `" . DB_PREFIX . "subscription_status` `ss` ON `sh`.`subscription_status_id` = `ss`.`subscription_status_id` WHERE `sh`.`subscription_id` = '" . (int)$subscription_id . "' AND `ss`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `sh`.`date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

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
}
