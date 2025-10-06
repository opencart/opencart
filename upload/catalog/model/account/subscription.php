<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Subscription
 *
 * Can be called using $this->load->model('account/subscription');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Subscription extends \Opencart\System\Engine\Model {
	/**
	 * Get Subscription
	 *
	 * Get the record of the subscription record in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 *
	 * @return array<string, mixed> subscription record that has subscription ID
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $subscription_info = $this->model_account_subscription->getSubscription($subscription_id);
	 */
	public function getSubscription(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

		if ($query->num_rows) {
			return [
				'payment_method'  => $query->row['payment_method'] ? json_decode($query->row['payment_method'], true) : [],
				'shipping_method' => $query->row['shipping_method'] ? json_decode($query->row['shipping_method'], true) : []
			] + $query->row;
		}

		return [];
	}

	/**
	 * Get Subscriptions
	 *
	 * Get the record of the subscription records in the database.
	 *
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> subscription records
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $results = $this->model_account_subscription->getSubscriptions();
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
	 * Get the total number of total subscription records in the database.
	 *
	 * @return int total number of subscription records
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $subscription_total = $this->model_account_subscription->getTotalSubscriptions();
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
	 * Get the total number of total subscriptions by shipping address records in the database.
	 *
	 * @param int $address_id primary key of the address record
	 *
	 * @return int total number of subscription records that have address ID
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $subscription_total = $this->model_account_subscription->getTotalSubscriptionByShippingAddressId($address_id);
	 */
	public function getTotalSubscriptionByShippingAddressId(int $address_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `shipping_address_id` = '" . (int)$address_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Subscription By Payment Address ID
	 *
	 * Get the total number of total subscriptions by payment address records in the database.
	 *
	 * @param int $address_id primary key of the address record
	 *
	 * @return int total number of subscription records that have address ID
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $subscription_total = $this->model_account_subscription->getTotalSubscriptionByPaymentAddressId($address_id);
	 */
	public function getTotalSubscriptionByPaymentAddressId(int $address_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `payment_address_id` = '" . (int)$address_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Subscription By Order Product ID
	 *
	 * Get the total number of total order products by order records in the database.
	 *
	 * @param int $order_id         primary key of the order record
	 * @param int $order_product_id primary key of the order product record
	 *
	 * @return array<string, mixed> subscription records that have order ID, order product ID
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $subscription_product_info = $this->model_account_subscription->getProductByOrderProductId($order_id, $order_product_id);
	 */
	public function getProductByOrderProductId(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_product` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	/**
	 * Get Subscription Products
	 *
	 * Get the record of the subscription products records in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 *
	 * @return array<int, array<string, mixed>> subscription records that have subscription ID
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $results = $this->model_account_subscription->getProducts($subscription_id);
	 */
	public function getProducts(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Products
	 *
	 * Get the total number of total subscription product records in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 *
	 * @return int total number of product records that have subscription ID
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $subscription_product_total = $this->model_account_subscription->getTotalProducts($subscription_id);
	 */
	public function getTotalProducts(int $subscription_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Options
	 *
	 * Get the record of the subscription option records in the database.
	 *
	 * @param int $subscription_id         primary key of the subscription record
	 * @param int $subscription_product_id primary key of the subscription product record
	 *
	 * @return array<string, mixed> option records that have subscription ID, subscription product ID
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $options = $this->model_account_subscription->getOptions($subscription_id, $subscription_product_id);
	 */
	public function getOptions(int $subscription_id, int $subscription_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_option` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `subscription_product_id` = '" . (int)$subscription_product_id . "'");

		return $query->rows;
	}

	/**
	 * Get Histories
	 *
	 * Get the record of the subscription history records in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> history records that have subscription ID
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $results = $this->model_account_subscription->getHistories($subscription_id);
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
	 * Get the total number of total subscription history records in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 *
	 * @return int total number of history records that have subscription ID
	 *
	 * @example
	 *
	 * $this->load->model('account/subscription');
	 *
	 * $history_total = $this->model_account_subscription->getTotalHistories($subscription_id);
	 */
	public function getTotalHistories(int $subscription_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_history` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return (int)$query->row['total'];
	}
}
