<?php
namespace Opencart\Catalog\Model\Checkout;
class Subscription extends \Opencart\System\Engine\Model {
	public function addSubscription(int $order_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription` SET 
		`order_id` = '" . (int)$data['order_id'] . "', 
		`order_product_id` = '" . (int)$data['order_product_id'] . "', 
		`store_id` = '" . (int)$data['store_id'] . "', 
		`customer_id` = '" . (int)$data['customer_id'] . "', 
		`customer_group_id` = '" . (int)$data['customer_group_id'] . "', 
		`payment_address_id` = '" . (int)$data['payment_address_id'] . "', 
		`payment_method` = '" . $this->db->escape((string)$data['payment_method']) . "', 
		`payment_code` = '" . $this->db->escape((string)$data['payment_code']) . "', 
		`shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', 
		`shipping_method` = '" . $this->db->escape((string)$data['shipping_method']) . "', 
		`shipping_code` = '" . $this->db->escape((string)$data['shipping_code']) . "', 
		`subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', 
		`product_id` = '" . (int)$data['product_id'] . "', 
		`quantity` = '" . (int)$data['quantity'] . "', 
		`name` = '" . $this->db->escape($data['name']) . "', 
		`trial_price` = '" . (float)$data['trial_price'] . "', 
		`trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', 
		`trial_cycle` = '" . (int)$data['trial_cycle'] . "', 
		`trial_duration` = '" . (int)$data['trial_duration'] . "', 
		`trial_remaining` = '" . (int)$data['trial_remaining'] . "', 
		`trial_status` = '" . (int)$data['trial_status'] . "', 
		`price` = '" . (float)$data['price'] . "', 
		`frequency` = '" . $this->db->escape($data['frequency']) . "', 
		`cycle` = '" . (int)$data['cycle'] . "', 
		`duration` = '" . (int)$data['duration'] . "', 
		`remaining` = '" . (int)$data['remaining'] . "', 
		`date_next` = '" . $this->db->escape($data['date_next']) . "', 
		`comment` = '" . $this->db->escape($data['comment']) . "', 
		`subscription_status_id` = '" . (int)$data['subscription_status_id'] . "', 
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
		`date_modified` = NOW()");

		return $this->db->getLastId();
	}

	public function editSubscription(int $subscription_id, array $data): int {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET 
		`order_id` = '" . (int)$data['order_id'] . "', 
		`order_product_id` = '" . (int)$data['order_product_id'] . "', 
		`store_id` = '" . (int)$data['store_id'] . "', 
		`customer_id` = '" . (int)$data['customer_id'] . "', 
		`customer_group_id` = '" . (int)$data['customer_group_id'] . "', 
		`payment_address_id` = '" . (int)$data['payment_address_id'] . "', 
		`payment_method` = '" . $this->db->escape((string)$data['payment_method']) . "', 
		`payment_code` = '" . $this->db->escape((string)$data['payment_code']) . "', 
		`shipping_address_id` = '" . (int)$data['shipping_address_id'] . "', 
		`shipping_method` = '" . $this->db->escape((string)$data['shipping_method']) . "', 
		`shipping_code` = '" . $this->db->escape((string)$data['shipping_code']) . "', 
		`subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', 
		`product_id` = '" . (int)$data['product_id'] . "', 
		`quantity` = '" . (int)$data['quantity'] . "', 
		`name` = '" . $this->db->escape($data['name']) . "', 
		`trial_price` = '" . (float)$data['trial_price'] . "', 
		`trial_frequency` = '" . $this->db->escape($data['trial_frequency']) . "', 
		`trial_cycle` = '" . (int)$data['trial_cycle'] . "', 
		`trial_duration` = '" . (int)$data['trial_duration'] . "', 
		`trial_remaining` = '" . (int)$data['trial_remaining'] . "', 
		`trial_status` = '" . (int)$data['trial_status'] . "', 
		`price` = '" . (float)$data['price'] . "', 
		`frequency` = '" . $this->db->escape($data['frequency']) . "', 
		`cycle` = '" . (int)$data['cycle'] . "', 
		`duration` = '" . (int)$data['duration'] . "', 
		`remaining` = '" . (int)$data['remaining'] . "', 
		`date_next` = '" . $this->db->escape($data['date_next']) . "', 
		`comment` = '" . $this->db->escape($data['comment']) . "', 
		`subscription_status_id` = '" . (int)$data['subscription_status_id'] . "', 
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
		WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	public function deleteSubscriptionByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function getSubscriptionByOrderProductId(int $order_product_id): array {
		$this->db->query("SELECT * FROM  `" . DB_PREFIX . "subscription` WHERE `order_product_id` = '" . (int)$order_product_id . "'");

		return $this->db->row;
	}
	
	public function addHistory(int $subscription_id, int $subscription_status_id, string $comment = '', bool $notify = false): void {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_history` SET `subscription_id` = '" . (int)$subscription_id . "', `subscription_status_id` = '" . (int)$subscription_status_id . "', `comment` = '" . $this->db->escape($comment) . "', `notify` = '" . (int)$notify . "', `date_added` = NOW()");

        $this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
    }
}
