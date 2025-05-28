<?php
namespace Opencart\Catalog\Model\Checkout;
/**
 * Class Subscription
 *
 * Can be called using $this->load->model('checkout/subscription');
 *
 * @package Opencart\Catalog\Model\Checkout
 */
class Subscription extends \Opencart\System\Engine\Model {
	/**
	 * Add Subscription
	 *
	 * Create a new subscription record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new subscription record
	 *
	 * @example
	 *
	 * $subscription_data = [
	 *     'order_id'           => 1,
	 *     'store_id'           => 1,
	 *     'customer_id'        => 1,
	 *     'payment_address_id' => 1,
	 *     'payment_method'     => [
	 *         'name' => 'Payment Name',
	 *         'code' => 'Payment Code'
	 *     ],
	 *     'shipping_address_id' => 1,
	 *     'shipping_method'     => [
	 *         'name' => 'Shipping Name',
	 *         'code' => 'Shipping Code'
	 *     ],
	 *     'subscription_plan_id' => 1,
	 *     'trial_price'          => 0.0000,
	 *     'trial_frequency'      => 'month',
	 *     'trial_cycle'          => 5,
	 *     'trial_duration'       => 1,
	 *     'trial_status'         => 1,
	 *     'price'                => 0.0000,
	 *     'frequency'            => 'month',
	 *     'cycle'                => 5,
	 *     'duration'             => 1,
	 *     'comment'              => '',
	 *     'affiliate_id'         => 1,
	 *     'marketing_id'         => 1,
	 *     'tracking'             => '',
	 *     'language_id'          => 1,
	 *     'currency_id'          => 1
	 * ];
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $subscription_id = $this->model_checkout_subscription->addSubscription($subscription_data);
	 */
	public function addSubscription(array $data): int {
		if ($data['trial_status'] && $data['trial_duration']) {
			$trial_remaining = $data['trial_duration'] - 1;
			$remaining = $data['duration'];
		} elseif ($data['duration']) {
			$trial_remaining = 0;
			$remaining = $data['duration'] - 1;
		} else {
			$trial_remaining = 0;
			$remaining = 0;
		}

		if ($data['trial_status'] && $data['trial_duration']) {
			$date_next = date('Y-m-d', strtotime('+' . $data['trial_cycle'] . ' ' . $data['trial_frequency']));
		} else {
			$date_next = date('Y-m-d', strtotime('+' . $data['cycle'] . ' ' . $data['frequency']));
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription` SET `order_id` = '" . (isset($data['order_id']) ? (int)$data['order_id'] : 0) . "', `store_id` = '" . (int)$data['store_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `payment_address_id` = '" . (int)$data['payment_address_id'] . "', `payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', `shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', `shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) : '') . "', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `trial_tax` = '" . (float)$data['trial_tax'] . "', `trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_remaining` = '" . (int)$trial_remaining . "', `trial_status` = '" . (int)$data['trial_status'] . "', `price` = '" . (float)$data['price'] . "', `tax` = '" . (float)$data['tax'] . "', `frequency` = '" . $this->db->escape($data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `remaining` = '" . (int)$remaining . "', `date_next` = '" . $this->db->escape($date_next) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `language` = '" . $this->db->escape($data['language']) . "', `currency_code` = '" . $this->db->escape($data['currency_code']) . "', `currency_value` = '" . (float)$data['currency_value'] . "', `date_added` = NOW(), `date_modified` = NOW()");

		$subscription_id = $this->db->getLastId();

		foreach ($data['subscription_product'] as $subscription_product) {
			$this->addProduct($subscription_id, $subscription_product);
		}

		return $subscription_id;
	}

	/**
	 * Edit Subscription
	 *
	 * Edit subscription record in the database.
	 *
	 * @param int                  $subscription_id primary key of the subscription record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $subscription_data = [
	 *     'order_id'           => 1,
	 *     'store_id'           => 1,
	 *     'customer_id'        => 1,
	 *     'payment_address_id' => 1,
	 *     'payment_method'     => [
	 *         'name' => 'Payment Name',
	 *         'code' => 'Payment Code'
	 *     ],
	 *     'shipping_address_id' => 1,
	 *     'shipping_method'     => [
	 *         'name' => 'Shipping Name',
	 *         'code' => 'Shipping Code'
	 *     ],
	 *     'subscription_plan_id' => 1,
	 *     'trial_price'          => 0.0000,
	 *     'trial_frequency'      => 'month',
	 *     'trial_cycle'          => 5,
	 *     'trial_duration'       => 1,
	 *     'trial_status'         => 1,
	 *     'price'                => 0.0000,
	 *     'frequency'            => 'month',
	 *     'cycle'                => 5,
	 *     'duration'             => 1,
	 *     'comment'              => '',
	 *     'affiliate_id'         => 1,
	 *     'marketing_id'         => 1,
	 *     'tracking'             => '',
	 *     'language_id'          => 1,
	 *     'currency_id'          => 1
	 * ];
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->addSubscription($subscription_id, $subscription_data);
	 */
	public function editSubscription(int $subscription_id, array $data): void {
		if ($data['trial_status'] && $data['trial_duration']) {
			$trial_remaining = $data['trial_duration'] - 1;
			$remaining = $data['duration'];
		} elseif ($data['duration']) {
			$trial_remaining = 0;
			$remaining = $data['duration'] - 1;
		} else {
			$trial_remaining = 0;
			$remaining = 0;
		}

		if ($data['trial_status'] && $data['trial_duration']) {
			$date_next = date('Y-m-d', strtotime('+' . $data['trial_cycle'] . ' ' . $data['trial_frequency']));
		} else {
			$date_next = date('Y-m-d', strtotime('+' . $data['cycle'] . ' ' . $data['frequency']));
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `order_id` = '" . (isset($data['order_id']) ? (int)$data['order_id'] : 0) . "', `store_id` = '" . (int)$data['store_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `payment_address_id` = '" . (int)$data['payment_address_id'] . "', `payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', `shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', `shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) : '') . "', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `trial_tax` = '" . (float)$data['trial_tax'] . "', `trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_remaining` = '" . (int)$trial_remaining . "',`trial_status` = '" . (int)$data['trial_status'] . "', `price` = '" . (float)$data['price'] . "', `tax` = '" . (float)$data['tax'] . "', `frequency` = '" . $this->db->escape($data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `remaining` = '" . (int)$remaining . "', `date_next` = '" . $this->db->escape($date_next) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `language` = '" . $this->db->escape($data['language']) . "', `currency_code` = '" . $this->db->escape($data['currency_code']) . "', `currency_value` = '" . (float)$data['currency_value'] . "', `date_modified` = NOW() WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->deleteProducts($subscription_id);

		foreach ($data['subscription_product'] as $subscription_product) {
			$this->addProduct($subscription_id, $subscription_product);
		}
	}

	/**
	 * Edit Subscription Status
	 *
	 * Edit subscription status record in the database.
	 *
	 * @param int  $subscription_id        primary key of the subscription record
	 * @param bool $subscription_status_id primary key of the subscription status record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->editSubscriptionStatus($subscription_id, $subscription_status_id);
	 */
	public function editSubscriptionStatus(int $subscription_id, bool $subscription_status_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Edit Remaining
	 *
	 * Edit subscription remaining record in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 * @param int $remaining
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->editRemaining($subscription_id, $remaining);
	 */
	public function editRemaining(int $subscription_id, int $remaining): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `remaining` = '" . (int)$remaining . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Edit Trial Remaining
	 *
	 * Edit subscription trial remaining record in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 * @param int $trial_remaining
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->editTrialRemaining($subscription_id, $trial_remaining);
	 */
	public function editTrialRemaining(int $subscription_id, int $trial_remaining): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `trial_remaining` = '" . (int)$trial_remaining . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Edit Date Next
	 *
	 * Edit date next record in the database.
	 *
	 * @param int    $subscription_id primary key of the subscription record
	 * @param string $date_next
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->editDateNext($subscription_id, $date_next);
	 */
	public function editDateNext(int $subscription_id, string $date_next): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `date_next` = '" . $this->db->escape($date_next) . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Delete Subscription By Order ID
	 *
	 * Delete subscription by order record in the database.
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->deleteSubscriptionByOrderId($order_id);
	 */
	public function deleteSubscriptionByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription` WHERE `order_id` = '" . (int)$order_id . "'");
	}

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
	 * $this->load->model('checkout/subscription');
	 *
	 * $subscription_info = $this->model_checkout_subscription->getSubscription($subscription_id);
	 */
	public function getSubscription(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

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
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> subscription records
	 *
	 * @example
	 * 
	 * $filter_data = [
	 *     'filter_subscription_id'        => 1,
	 *     'filter_order_id'               => 1,
	 *     'filter_order_product_id'       => 1,
	 *     'filter_customer_payment_id'    => 1,
	 *     'filter_customer_id'            => 1,
	 *     'filter_customer'               => 'John Doe',
	 *     'filter_date_next'              => '2022-01-01',
	 *     'filter_subscription_status_id' => 1,
	 *     'filter_date_from'              => '2021-01-01',
	 *     'filter_date_to'                => '2021-01-31',
	 * ];
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $subscriptions = $this->model_checkout_subscription->getSubscriptions();
	 */
	public function getSubscriptions(array $data): array {
		$sql = "SELECT `s`.`subscription_id`, `s`.*, CONCAT(`o`.`firstname`, ' ', `o`.`lastname`) AS `customer`, (SELECT `ss`.`name` FROM `" . DB_PREFIX . "subscription_status` `ss` WHERE `ss`.`subscription_status_id` = `s`.`subscription_status_id` AND `ss`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `subscription_status` FROM `" . DB_PREFIX . "subscription` `s` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`s`.`order_id` = `o`.`order_id`)";

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

		$order_data = [];

		$query = $this->db->query($sql);

		foreach ($query->rows as $key => $result) {
			$order_data[$key] = [
				'payment_method'  => $result['payment_method'] ? json_decode($result['payment_method'], true) : [],
				'shipping_method' => $result['shipping_method'] ? json_decode($result['shipping_method'], true) : []
			] + $result;
		}

		return $order_data;
	}

	/**
	 * Get Total Subscriptions
	 *
	 * Get the total number of total subscription records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of subscription records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_subscription_id'        => 1,
	 *     'filter_order_id'               => 1,
	 *     'filter_order_product_id'       => 1,
	 *     'filter_customer_payment_id'    => 1,
	 *     'filter_customer_id'            => 1,
	 *     'filter_customer'               => 'John Doe',
	 *     'filter_date_next'              => '2022-01-01',
	 *     'filter_subscription_status_id' => 1,
	 *     'filter_date_from'              => '2021-01-01',
	 *     'filter_date_to'                => '2021-01-31',
	 * ];
	 *
	 * $this->load->model('sale/subscription');
	 *
	 * $subscription_total = $this->model_sale_subscription->getTotalSubscriptions($filter_data);
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

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Add Product
	 *
	 * Create a new subscription product record in the database.
	 *
	 * @param int                  $subscription_id primary key of the subscription record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return int returns the primary key of the new subscription product record
	 *
	 * @example
	 *
	 * $subscription_product_data = [
	 *     'order_id'         => 1,
	 *     'order_product_id' => 1,
	 *     'product_id'       => 1,
	 *     'name'             => 'Product Name',
	 *     'model'            => 'Product Model',
	 *     'quantity'         => 1,
	 *     'trial_price'      => 0.0000,
	 *     'price'            => 0.0000
	 * ];
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->addProduct($subscription_id, $subscription_product_data);
	 */
	public function addProduct(int $subscription_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_product` SET `subscription_id` = '" . (int)$subscription_id . "', `order_id` = '" . (int)$data['order_id'] . "', `order_product_id` = '" . (int)$data['order_product_id'] . "', `product_id` = '" . (int)$data['product_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `model` = '" . $this->db->escape($data['model']) . "', `quantity` = '" . (int)$data['quantity'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `price` = '" . (float)$data['price'] . "'");

		$subscription_product_id = $this->db->getLastId();

		if ($data['option']) {
			foreach ($data['option'] as $option) {
				$this->model_checkout_subscription->addOption($subscription_id, $subscription_product_id, $option);
			}
		}

		return $subscription_product_id;
	}

	/**
	 * Delete Subscription Products
	 *
	 * Delete subscription product records in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->deleteProducts($subscription_id);
	 */
	public function deleteProducts(int $subscription_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->deleteOptions($subscription_id);
	}

	/**
	 * Get Products
	 *
	 * Get the record of the subscription product records in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 *
	 * @return array<string, mixed> product records that have subscription ID
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $products = $this->model_checkout_subscription->getProducts($subscription_id);
	 */
	public function getProducts(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return $query->rows;
	}

	/**
	 * Get Product By Order Product ID
	 *
	 * Get the record of the subscription products by order product records in the database.
	 *
	 * @param int $order_id         primary key of the order record
	 * @param int $order_product_id primary key of the order product record
	 *
	 * @return array<string, mixed> product record that has order ID, order product ID
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $subscription_product_info = $this->model_checkout_subscription->getProductByOrderProductId($order_id, $order_product_id);
	 */
	public function getProductByOrderProductId(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_product` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	/**
	 * Add Option
	 *
	 * Create a new subscription option record in the database.
	 *
	 * @param int                  $subscription_id         primary key of the subscription record
	 * @param int                  $subscription_product_id primary key of the subscription product record
	 * @param array<string, mixed> $data                    array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $subscription_option_data = [
	 *     'product_option_id'       => 1,
	 *     'product_option_value_id' => 1,
	 *     'name'                    => 'Option Name',
	 *     'value'                   => 'Option Value',
	 *     'type'                    => 'radio'
	 * ];
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->addOtion($subscription_id, $subscription_product_id, $subscription_option_data);
	 */
	public function addOption(int $subscription_id, int $subscription_product_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_option` SET `subscription_id` = '" . (int)$subscription_id . "', `subscription_product_id` = '" . (int)$subscription_product_id . "', `product_option_id` = '" . (int)$data['product_option_id'] . "', `product_option_value_id` = '" . (int)$data['product_option_value_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `value` = '" . $this->db->escape($data['value']) . "', `type` = '" . $this->db->escape($data['type']) . "'");
	}

	/**
	 * Delete Options
	 *
	 * Delete subscription option records in the database.
	 *
	 * @param int $subscription_id primary key of the subscription record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->deleteOptions($subscription_id);
	 */
	public function deleteOptions(int $subscription_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_option` WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Get Option
	 *
	 * Get the record of the subscription option record in the database.
	 *
	 * @param int $subscription_id         primary key of the subscription record
	 * @param int $subscription_product_id primary key of the subscription product record
	 * @param int $product_option_id       primary key of the product option record
	 *
	 * @return array<string, mixed> option record that has subscription ID, subscription product ID, product option ID
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $option = $this->model_checkout_subscription->getOption($subscription_id, $subscription_product_id, $product_option_id);
	 */
	public function getOption(int $subscription_id, int $subscription_product_id, int $product_option_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_option` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `subscription_product_id` = '" . (int)$subscription_product_id . "' AND `product_option_id` = '" . (int)$product_option_id . "'");

		return $query->row;
	}

	/**
	 * Get Options
	 *
	 * Get the record of the subscription option records in the database.
	 *
	 * @param int $subscription_id         primary key of the subscription record
	 * @param int $subscription_product_id primary key of the subscription product record
	 *
	 * @return array<int, array<string, mixed>> option records that have subscription ID, subscription product ID
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $options = $this->model_checkout_subscription->getOptions($subscription_id, $subscription_product_id);
	 */
	public function getOptions(int $subscription_id, int $subscription_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_option` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `subscription_product_id` = '" . (int)$subscription_product_id . "'");

		return $query->rows;
	}

	/**
	 * Add History
	 *
	 * Create a new subscription history record in the database.
	 *
	 * @param int    $subscription_id        primary key of the subscription record
	 * @param int    $subscription_status_id primary key of the subscription status record
	 * @param string $comment
	 * @param bool   $notify
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->addHistory($subscription_id, $subscription_status_id, $comment, $notify);
	 */
	public function addHistory(int $subscription_id, int $subscription_status_id, string $comment = '', bool $notify = false): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_history` SET `subscription_id` = '" . (int)$subscription_id . "', `subscription_status_id` = '" . (int)$subscription_status_id . "', `comment` = '" . $this->db->escape($comment) . "', `notify` = '" . (int)$notify . "', `date_added` = NOW()");
	}

	/**
	 * Add Log
	 *
	 * Create a new subscription log record in the database.
	 *
	 * @param int    $subscription_id primary key of the subscription record
	 * @param string $code
	 * @param string $description
	 * @param bool   $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('checkout/subscription');
	 *
	 * $this->model_checkout_subscription->addLog($subscription_id, $code, $description, $status);
	 */
	public function addLog(int $subscription_id, string $code, string $description, bool $status = false): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_log` SET `subscription_id` = '" . (int)$subscription_id . "', `code` = '" . $this->db->escape($code) . "', `description` = '" . $this->db->escape($description) . "', `status` = '" . (int)$status . "', `date_added` = NOW()");
	}
}
