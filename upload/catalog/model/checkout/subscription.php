<?php
namespace Opencart\Catalog\Model\Checkout;
class Subscription extends \Opencart\System\Engine\Model {
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

		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription` SET 
			`order_id` = '" . (int)$data['order_id'] . "', 
			`order_product_id` = '" . (int)$data['order_product_id'] . "', 
			`store_id` = '" . (int)$data['store_id'] . "', 
			`customer_id` = '" . (int)$data['customer_id'] . "', 
			`customer_group_id` = '" . (int)$data['customer_group_id'] . "', 
			`payment_address_id` = '" . (int)$data['payment_address_id'] . "', 
			`payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', 
			`shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', 
			`shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) : '') . "', 
			`subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', 
			`product_id` = '" . (int)$data['product_id'] . "', 
			`quantity` = '" . (int)$data['quantity'] . "', 
			`name` = '" . $this->db->escape($data['name']) . "', 
			`trial_price` = '" . (float)$data['trial_price'] . "', 
			`trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', 
			`trial_cycle` = '" . (int)$data['trial_cycle'] . "', 
			`trial_duration` = '" . (int)$data['trial_duration'] . "', 
			`trial_remaining` = '" . (int)$trial_remaining . "', 
			`trial_status` = '" . (int)$data['trial_status'] . "', 
			`price` = '" . (float)$data['price'] . "', 
			`frequency` = '" . $this->db->escape($data['frequency']) . "', 
			`cycle` = '" . (int)$data['cycle'] . "', 
			`duration` = '" . (int)$data['duration'] . "', 
			`remaining` = '" . (int)$trial_remaining . "', 
			`date_next` = '" . $this->db->escape($date_next) . "', 
			`comment` = '" . $this->db->escape($data['comment']) . "', 
			`affiliate_id` = '" . (int)$data['affiliate_id'] . "', 
			`commission` = '" . (float)$data['commission'] . "', 
			`marketing_id` = '" . (int)$data['marketing_id'] . "', 
			`tracking` = '" . $this->db->escape($data['tracking']) . "', 
			`language_id` = '" . (int)$data['language_id'] . "', 
			`currency_id` = '" . (int)$data['currency_id'] . "', 
			`ip` = '" . $this->db->escape($data['ip']) . "', 
			`forwarded_ip` = '" . $this->db->escape($data['forwarded_ip']) . "', 
			`user_agent` = '" . $this->db->escape($data['user_agent']) . "', 
			`accept_language` = '" . $this->db->escape($data['accept_language']) . "', 
			`date_added` = NOW(), 
			`date_modified` = NOW()
		");

		return $this->db->getLastId();
	}

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

		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET 
			`order_id` = '" . (int)$data['order_id'] . "', 
			`order_product_id` = '" . (int)$data['order_product_id'] . "', 
			`store_id` = '" . (int)$data['store_id'] . "', 
			`customer_id` = '" . (int)$data['customer_id'] . "', 
			`payment_address_id` = '" . (int)$data['payment_address_id'] . "', 
			`payment_method` = '" . $this->db->escape($data['payment_method'] ? json_encode($data['payment_method']) : '') . "', 
			`shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', 
			`shipping_method` = '" . $this->db->escape($data['shipping_method'] ? json_encode($data['shipping_method']) :  '') . "',
			`subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', 
			`name` = '" . $this->db->escape($data['name']) . "', 
			`trial_price` = '" . (float)$data['trial_price'] . "', 
			`trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', 
			`trial_cycle` = '" . (int)$data['trial_cycle'] . "', 
			`trial_duration` = '" . (int)$data['trial_duration'] . "', 
			`trial_remaining` = '" . (int)$trial_remaining . "', 
			`trial_status` = '" . (int)$data['trial_status'] . "', 
			`price` = '" . (float)$data['price'] . "', 
			`frequency` = '" . $this->db->escape($data['frequency']) . "', 
			`cycle` = '" . (int)$data['cycle'] . "', 
			`duration` = '" . (int)$data['duration'] . "', 
			`remaining` = '" . (int)$remaining . "', 
			`date_next` = '" . $this->db->escape($date_next) . "', 
			`comment` = '" . $this->db->escape($data['comment']) . "', 
			`affiliate_id` = '" . (int)$data['affiliate_id'] . "', 
			`commission` = '" . (float)$data['commission'] . "', 
			`marketing_id` = '" . (int)$data['marketing_id'] . "', 
			`tracking` = '" . $this->db->escape($data['tracking']) . "', 
			`language_id` = '" . (int)$data['language_id'] . "', 
			`currency_id` = '" . (int)$data['currency_id'] . "', 
			`ip` = '" . $this->db->escape($data['ip']) . "', 
			`forwarded_ip` = '" . $this->db->escape($data['forwarded_ip']) . "', 
			`user_agent` = '" . $this->db->escape($data['user_agent']) . "', 
			`accept_language` = '" . $this->db->escape($data['accept_language']) . "', 
			`date_modified` = NOW()
			WHERE `subscription_id` = '" . (int)$subscription_id . "'
		");
	}

	public function deleteSubscriptionByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function getSubscriptionByOrderProductId(int $order_id, int $order_product_id): array {
		$subscription_data = [];

		$query = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "subscription` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		if ($query->num_rows) {
			$subscription_data = $query->row;

			$subscription_data['payment_method'] = ($query->row['payment_method'] ? json_decode($query->row['payment_method'], true) : '');
			$subscription_data['shipping_method'] = ($query->row['shipping_method'] ? json_decode($query->row['shipping_method'], true) : '');
		}

		return $subscription_data;
	}

	public function addHistory(int $subscription_id, int $subscription_status_id, string $comment = '', bool $notify = false): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_history` SET `subscription_id` = '" . (int)$subscription_id . "', `subscription_status_id` = '" . (int)$subscription_status_id . "', `comment` = '" . $this->db->escape($comment) . "', `notify` = '" . (int)$notify . "', `date_added` = NOW()");

		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	public function editSubscriptionStatus(int $subscription_id, bool $subscription_status_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	public function editTrialRemaining(int $subscription_id, int $trial_remaining): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `trial_remaining` = '" . (int)$trial_remaining . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	public function editDateNext(int $subscription_id, string $date_next): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `date_next` = '" . $this->db->escape($date_next) . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}
}