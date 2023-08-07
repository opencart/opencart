<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Order
 *
 * @package Opencart\Catalog\Model\Account
 */
class Order extends \Opencart\System\Engine\Model {
	/**
	 * @param int $order_id
	 *
	 * @return array
	 */
	public function getOrder(int $order_id): array {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `customer_id` != '0' AND `order_status_id` > '0'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `country_id` = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `zone_id` = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `zone_id` = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			return [
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'] ? json_decode($order_query->row['payment_method'], true) : '',
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'] ? json_decode($order_query->row['shipping_method'], true) : '',
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip']
			];
		} else {
			return [];
		}
	}

	/**
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array
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
	 * @param int $subscription_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array
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
	 * @param int $subscription_id
	 *
	 * @return int
	 */
	public function getTotalOrdersBySubscriptionId(int $subscription_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $order_id
	 * @param int $order_product_id
	 *
	 * @return array
	 */
	public function getProduct(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	/**
	 * @param int $order_id
	 *
	 * @return array
	 */
	public function getProducts(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

		return $query->rows;
	}

	/**
	 * @param int $order_id
	 * @param int $order_product_id
	 *
	 * @return array
	 */
	public function getOptions(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_option` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	/**
	 * @param int $order_id
	 * @param int $order_product_id
	 *
	 * @return array
	 */
	public function getSubscription(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_subscription` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	/**
	 * @param int $order_id
	 *
	 * @return array
	 */
	public function getVouchers(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE `order_id` = '" . (int)$order_id . "'");

		return $query->rows;
	}

	/**
	 * @param int $order_id
	 *
	 * @return array
	 */
	public function getTotals(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY `sort_order`");

		return $query->rows;
	}

	/**
	 * @param int $order_id
	 *
	 * @return array
	 */
	public function getHistories(int $order_id): array {
		$query = $this->db->query("SELECT `date_added`, os.`name` AS status, oh.`comment`, oh.`notify` FROM `" . DB_PREFIX . "order_history` oh LEFT JOIN `" . DB_PREFIX . "order_status` os ON oh.`order_status_id` = os.`order_status_id` WHERE oh.`order_id` = '" . (int)$order_id . "' AND os.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY oh.`date_added`");

		return $query->rows;
	}

	/**
	 * @param int $order_id
	 *
	 * @return int
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
	 * @return int
	 */
	public function getTotalOrders(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order` o WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND o.`order_status_id` > '0' AND o.`store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * @param int $product_id
	 *
	 * @return int
	 */
	public function getTotalOrdersByProductId(int $product_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.`order_id` = o.`order_id`) WHERE o.`customer_id` = '" . (int)$this->customer->getId() . "' AND op.`product_id` = '" . (int)$product_id . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * @param int $order_id
	 *
	 * @return int
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
	 * @param int $order_id
	 *
	 * @return int
	 */
	public function getTotalVouchersByOrderId(int $order_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_voucher` WHERE `order_id` = '" . (int)$order_id . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}
}
