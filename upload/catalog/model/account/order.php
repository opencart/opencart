<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Order
 *
 * Can be called using $this->load->model('account/order');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Order extends \Opencart\System\Engine\Model {
	/**
	 * Get Order
	 *
	 * Get the record of the order record in the database.
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return array<string, mixed> order record that has order ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $order_info = $this->model_account_order->getOrder($order_id);
	 */
	public function getOrder(int $order_id): array {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `customer_id` != '0' AND `order_status_id` > '0'");

		if ($order_query->num_rows) {
			// Country
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($order_query->row['payment_country_id']);

			if ($country_info) {
				$payment_iso_code_2 = $country_info['iso_code_2'];
				$payment_iso_code_3 = $country_info['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			// Zone
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($order_query->row['payment_zone_id']);

			if ($zone_info) {
				$payment_zone_code = $zone_info['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_info = $this->model_localisation_country->getCountry($order_query->row['shipping_country_id']);

			if ($country_info) {
				$shipping_iso_code_2 = $country_info['iso_code_2'];
				$shipping_iso_code_3 = $country_info['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			// Zone
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($order_query->row['shipping_zone_id']);

			if ($zone_info) {
				$shipping_zone_code = $zone_info['code'];
			} else {
				$shipping_zone_code = '';
			}

			return [
				'payment_zone_code'   => $payment_zone_code,
				'payment_iso_code_2'  => $payment_iso_code_2,
				'payment_iso_code_3'  => $payment_iso_code_3,
				'payment_method'      => $order_query->row['payment_method'] ? json_decode($order_query->row['payment_method'], true) : [],
				'shipping_zone_code'  => $shipping_zone_code,
				'shipping_iso_code_2' => $shipping_iso_code_2,
				'shipping_iso_code_3' => $shipping_iso_code_3,
				'shipping_method'     => $order_query->row['shipping_method'] ? json_decode($order_query->row['shipping_method'], true) : []
			] + $order_query->row;
		} else {
			return [];
		}
	}

	/**
	 * Get Orders
	 *
	 * Get the record of the order records in the database.
	 *
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> order records
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $results = $this->model_account_order->getOrders();
	 */
	public function getOrders(int $start = 0, int $limit = 20): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `order_status_id` > '0' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' ORDER BY `order_id` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Orders By Subscription ID
	 *
	 * Get the record of the orders by subscription records in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> order records that have subscription ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $results = $this->model_account_order->getOrdersBySubscriptionId($subscription_id, $start, $limit);
	 */
	public function getOrdersBySubscriptionId(int $subscription_id, int $start = 0, int $limit = 20): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `order_status_id` > '0' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' ORDER BY `order_id` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Product
	 *
	 * Get the record of the order product record in the database.
	 *
	 * @param int $order_id         primary key of the order record
	 * @param int $order_product_id primary key of the order product record
	 *
	 * @return array<string, mixed> product record that have order ID, order product ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $order_product = $this->model_account_order->getProduct($order_id, $order_product_id);
	 */
	public function getProduct(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	/**
	 * Get Products
	 *
	 * Get the record of the order product records in the database.
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return array<int, array<string, mixed>> product records that have order ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $order_products = $this->model_account_order->getProducts($order_id);
	 */
	public function getProducts(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

		return $query->rows;
	}

	/**
	 * Get Options
	 *
	 * Get the record of the order option records in the database.
	 *
	 * @param int $order_id         primary key of the order record
	 * @param int $order_product_id primary key of the order product record
	 *
	 * @return array<int, array<string, mixed>> option records that have order ID, order product ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $options = $this->model_account_order->getOptions($order_id, $order_product_id);
	 */
	public function getOptions(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_option` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	/**
	 * Get Subscription
	 *
	 * Get the record of the order subscription record in the database.
	 *
	 * @param int $order_id         primary key of the order record
	 * @param int $order_product_id primary key of the order product record
	 *
	 * @return array<string, mixed> order subscription record that has order ID, order product ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $subscription_info = $this->model_account_order->getSubscription($order_id, $order_product_id);
	 */
	public function getSubscription(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_subscription` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	/**
	 * Get Totals
	 *
	 * Get the record of the order total records in the database.
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return array<int, array<string, mixed>> total records that have order ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $totals = $this->model_account_order->getTotals($order_id);
	 */
	public function getTotals(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY `sort_order`");

		return $query->rows;
	}

	/**
	 * Get Histories
	 *
	 * Get the record of the order history records in the database.
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return array<int, array<string, mixed>> history records that have order ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $results = $this->model_account_order->getHistories($order_id);
	 */
	public function getHistories(int $order_id): array {
		$query = $this->db->query("SELECT `date_added`, `os`.`name` AS `status`, `oh`.`comment`, `oh`.`notify` FROM `" . DB_PREFIX . "order_history` `oh` LEFT JOIN `" . DB_PREFIX . "order_status` `os` ON `oh`.`order_status_id` = `os`.`order_status_id` WHERE `oh`.`order_id` = '" . (int)$order_id . "' AND `os`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `oh`.`date_added`");

		return $query->rows;
	}

	/**
	 * Get Total Histories
	 *
	 * Get the total number of total order history records in the database.
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return int total number of history records that have order ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $history_total = $this->model_account_order->getTotalHistories($order_id);
	 */
	public function getTotalHistories(int $order_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_history` WHERE `order_id` = '" . (int)$order_id . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * Get Total Orders
	 *
	 * Get the total number of total order records in the database.
	 *
	 * @return int total number of order records
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $order_total = $this->model_account_order->getTotalOrders();
	 */
	public function getTotalOrders(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order` `o` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `o`.`order_status_id` > '0' AND `o`.`store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * Get Total Orders By Product ID
	 *
	 * Get the total number of total orders by product records in the database.
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return int total number of order product records that have product ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $order_total = $this->model_account_order->getTotalOrdersByProductId($product_id);
	 */
	public function getTotalOrdersByProductId(int $product_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_product` `op` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`op`.`order_id` = `o`.`order_id`) WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "' AND `op`.`product_id` = '" . (int)$product_id . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * Get Total Products By Order ID
	 *
	 * Get the total number of total orders by order records in the database.
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return int total number of product records that have order ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $order_total = $this->model_account_order->getTotalProductsByOrderId($order_id);
	 */
	public function getTotalProductsByOrderId(int $order_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * Get Total Orders By Subscription ID
	 *
	 * Get the total number of total orders by subscription records in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 *
	 * @return int total number of subscription records that have subscription ID
	 *
	 * @example
	 *
	 * $this->load->model('account/order');
	 *
	 * $order_total = $this->model_account_order->getTotalOrdersBySubscriptionId($subscription_id);
	 */
	public function getTotalOrdersBySubscriptionId(int $subscription_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

		return (int)$query->row['total'];
	}
}
