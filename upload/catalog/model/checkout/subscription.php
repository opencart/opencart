<?php
namespace Opencart\Catalog\Model\Checkout;
/**
 * Class Subscription
 *
 * Can be called from $this->load->model('checkout/subscription');
 *
 * @package Opencart\Catalog\Model\Checkout
 */
class Subscription extends \Opencart\System\Engine\Model {
	/**
	 * Add Subscription
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int Subscription ID
	 *
	 * @example
	 *
	 * $subscription_data = [
	 *   'order_id'             => $order_info['order_id'],
	 *   'store_id'             => $order_info['store_id'],
	 *   'customer_id'          => $order_info['customer_id'],
	 *   'payment_address_id'   => $order_info['payment_address_id'],
	 *   'payment_method'       => $order_info['payment_method'],
	 *   'shipping_address_id'  => $order_info['shipping_address_id'],
	 *   'shipping_method'      => $order_info['shipping_method'],
	 *   'subscription_plan_id' => $order_subscription_info['subscription_plan_id'],
	 *   'trial_price'          => $order_subscription_info['trial_price'],
	 *   'trial_frequency'      => $order_subscription_info['trial_frequency'],
	 *   'trial_cycle'          => $order_subscription_info['trial_cycle'],
	 *   'trial_duration'       => $order_subscription_info['trial_duration'],
	 *   'trial_status'         => $order_subscription_info['trial_status'],
	 *   'price'                => $order_subscription_info['price'],
	 *   'frequency'            => $order_subscription_info['frequency'],
	 *   'cycle'                => $order_subscription_info['cycle'],
	 *   'duration'             => $order_subscription_info['duration'],
	 *   'comment'              => $order_info['comment'],
	 *   'affiliate_id'         => $order_info['affiliate_id'],
	 *   'marketing_id'         => $order_info['marketing_id'],
	 *   'tracking'             => $order_info['tracking'],
	 *   'language_id'          => $order_info['language_id'],
	 *   'currency_id'          => $order_info['currency_id']
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

		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription` SET `order_id` = '" . (isset($data['order_id']) ? (int)$data['order_id'] : 0) . "', `store_id` = '" . (int)$data['store_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `payment_address_id` = '" . (int)$data['payment_address_id'] . "', `payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', `shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', `shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) : '') . "', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `trial_tax` = '" . (float)$data['trial_tax'] . "', `trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_remaining` = '" . (int)$trial_remaining . "', `trial_status` = '" . (int)$data['trial_status'] . "', `price` = '" . (float)$data['price'] . "', `tax` = '" . (float)$data['tax'] . "', `frequency` = '" . $this->db->escape($data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `remaining` = '" . (int)$remaining . "', `date_next` = '" . $this->db->escape($date_next) . "', `comment` = '" . $this->db->escape($data['comment']) . "',  `language` = '" . $this->db->escape($data['language']) . "', `currency` = '" . $this->db->escape($data['currency']) . "', `date_added` = NOW(), `date_modified` = NOW()");

		$subscription_id = $this->db->getLastId();

		foreach ($data['subscription_product'] as $subscription_product) {
			$this->addProduct($subscription_id, $subscription_product);
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
	 *
	 * @example
	 *
	 * $subscription_data = [
	 *   'order_id'             => $order_info['order_id'],
	 *   'store_id'             => $order_info['store_id'],
	 *   'customer_id'          => $order_info['customer_id'],
	 *   'payment_address_id'   => $order_info['payment_address_id'],
	 *   'payment_method'       => $order_info['payment_method'],
	 *   'shipping_address_id'  => $order_info['shipping_address_id'],
	 *   'shipping_method'      => $order_info['shipping_method'],
	 *   'subscription_plan_id' => $order_subscription_info['subscription_plan_id'],
	 *   'trial_price'          => $order_subscription_info['trial_price'],
	 *   'trial_frequency'      => $order_subscription_info['trial_frequency'],
	 *   'trial_cycle'          => $order_subscription_info['trial_cycle'],
	 *   'trial_duration'       => $order_subscription_info['trial_duration'],
	 *   'trial_status'         => $order_subscription_info['trial_status'],
	 *   'price'                => $order_subscription_info['price'],
	 *   'frequency'            => $order_subscription_info['frequency'],
	 *   'cycle'                => $order_subscription_info['cycle'],
	 *   'duration'             => $order_subscription_info['duration'],
	 *   'comment'              => $order_info['comment'],
	 *   'affiliate_id'         => $order_info['affiliate_id'],
	 *   'marketing_id'         => $order_info['marketing_id'],
	 *   'tracking'             => $order_info['tracking'],
	 *   'language_id'          => $order_info['language_id'],
	 *   'currency_id'          => $order_info['currency_id']
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

		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `order_id` = '" . (isset($data['order_id']) ? (int)$data['order_id'] : 0) . "', `store_id` = '" . (int)$data['store_id'] . "', `customer_id` = '" . (int)$data['customer_id'] . "', `payment_address_id` = '" . (int)$data['payment_address_id'] . "', `payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', `shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', `shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) : '') . "', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `trial_tax` = '" . (float)$data['trial_tax'] . "', `trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_remaining` = '" . (int)$trial_remaining . "',`trial_status` = '" . (int)$data['trial_status'] . "', `price` = '" . (float)$data['price'] . "', `tax` = '" . (float)$data['tax'] . "', `frequency` = '" . $this->db->escape($data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `remaining` = '" . (int)$remaining . "', `date_next` = '" . $this->db->escape($date_next) . "', `comment` = '" . $this->db->escape($data['comment']) . "', `language` = '" . $this->db->escape($data['language']) . "', `currency` = '" . $this->db->escape($data['currency']) . "', `date_modified` = NOW() WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->deleteProducts($subscription_id);

		foreach ($data['subscription_product'] as $subscription_product) {
			$this->addProduct($subscription_id, $subscription_product);
		}
	}

	/**
	 * Edit Subscription Status
	 *
	 * @param int  $subscription_id
	 * @param bool $subscription_status_id
	 *
	 * @return void
	 */
	public function editSubscriptionStatus(int $subscription_id, bool $subscription_status_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
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
	 * Delete Subscription By Order ID
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteSubscriptionByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * Get Subscriptions
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
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

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(`o`.`firstname`, ' ', `o`.`lastname`) LIKE '" . $this->db->escape($data['filter_customer'] . '%') . "'";
		}

		if (!empty($data['filter_date_next'])) {
			$implode[] = "DATE(`s`.`date_next`) = DATE('" . $this->db->escape($data['filter_date_next']) . "')";
		}

		if (!empty($data['filter_subscription_status_id'])) {
			$implode[] = "`s`.`subscription_status_id` = '" . (int)$data['filter_subscription_status_id'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`s`.`date_added`) >= DATE('" . $this->db->escape($data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`s`.`date_added`) <= DATE('" . $this->db->escape($data['filter_date_to']) . "')";
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
				'payment_method'  => json_decode($result['payment_method'], true),
				'shipping_method' => json_decode($result['shipping_method'], true)
			] + $result;
		}

		return $order_data;
	}

	/**
	 * Add Product
	 *
	 * @param int                  $subscription_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addProduct(int $subscription_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_product` SET `subscription_id` = '" . (int)$subscription_id . "', `order_id` = '" . (int)$data['order_id'] . "', `order_product_id` = '" . (int)$data['order_product_id'] . "', `product_id` =  '" . (int)$data['product_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `model` = '" . $this->db->escape($data['model']) . "', `quantity` = '" . (int)$data['quantity'] . "', `trial_price` = '" . (float)$data['trial_price'] . "', `price` = '" . (float)$data['price'] . "'");

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
	 * @param int $subscription_id
	 *
	 * @return void
	 */
	public function deleteProducts(int $subscription_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->deleteOptions($subscription_id);
	}

	/**
	 * Get Products
	 *
	 * @param int $subscription_id
	 *
	 * @return array<string, mixed>
	 */
	public function getProducts(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_product` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return $query->rows;
	}

	/**
	 * Get Subscription Product By Order Product ID
	 *
	 * @param int $order_id
	 * @param int $order_product_id
	 *
	 * @return array<string, mixed>
	 */
	public function getProductByOrderProductId(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_product` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->row;
	}

	/**
	 * Add Option
	 *
	 * @param int                  $subscription_id
	 * @param int                  $subscription_product_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addOption(int $subscription_id, int $subscription_product_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_option` SET `subscription_id` = '" . (int)$subscription_id . "', `subscription_product_id` = '" . (int)$subscription_product_id . "', `product_option_id` = '" . (int)$data['product_option_id'] . "', `product_option_value_id` = '" . (int)$data['product_option_value_id'] . "', `name` = '" . $this->db->escape($data['name']) . "', `value` = '" . $this->db->escape($data['value']) . "', `type` = '" . $this->db->escape($data['type']) . "'");
	}

	/**
	 * Delete Subscription Options
	 *
	 * @param int $subscription_id
	 *
	 * @return void
	 */
	public function deleteOptions(int $subscription_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_option` WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * Get Option
	 *
	 * @param int $subscription_id
	 * @param int $subscription_product_id
	 * @param int $product_option_id
	 *
	 * @return array<string, mixed>
	 */
	public function getOption(int $subscription_id, int $subscription_product_id, int $product_option_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_option` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `subscription_product_id` = '" . (int)$subscription_product_id . "' AND `product_option_id` = '" . (int)$product_option_id . "'");

		return $query->row;
	}

	/**
	 * Get Options
	 *
	 * @param int $subscription_id
	 * @param int $subscription_product_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getOptions(int $subscription_id, int $subscription_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_option` WHERE `subscription_id` = '" . (int)$subscription_id . "' AND `subscription_product_id` = '" . (int)$subscription_product_id . "'");

		return $query->rows;
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
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_history` SET `subscription_id` = '" . (int)$subscription_id . "', `subscription_status_id` = '" . (int)$subscription_status_id . "', `comment` = '" . $this->db->escape($comment) . "', `notify` = '" . (int)$notify . "', `date_added` = NOW()");
	}

	/**
	 * Add Log
	 *
	 * @param int    $subscription_id
	 * @param string $code
	 * @param string $description
	 * @param bool   $status
	 *
	 * @return void
	 */
	public function addLog(int $subscription_id, string $code, string $description, bool $status = false): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_log` SET `subscription_id` = '" . (int)$subscription_id . "', `code` = '" . $this->db->escape($code) . "', `description` = '" . $this->db->escape($description) . "', `status` = '" . (int)$status . "', `date_added` = NOW()");
	}
}
