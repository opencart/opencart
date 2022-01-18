<?php
namespace Opencart\Catalog\Model\Checkout;
class Subscription extends \Opencart\System\Engine\Model {
	public function addSubscription(int $order_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription` SET `order_id` = '" . (int)$order_id . "', `order_product_id` = '" . (int)$data['order_product_id'] . "', `reference` = '', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `name` = '" . $this->db->escape((string)$data['name']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "', `trial_price` = '" . (float)$data['trial_price'] . "', `trial_frequency` = '" . $this->db->escape((string)$data['trial_frequency']) . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_status` = '" . (int)$data['trial_status'] . "', `price` = '" . (float)$data['price'] . "', `frequency` = '" . $this->db->escape((string)$data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `status` = '6', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	public function editSubscription(int $order_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription` SET `order_id` = '" . (int)$order_id . "', `order_product_id` = '" . (int)$data['order_product_id'] . "', `reference` = '', `subscription_plan_id` = '" . (int)$data['subscription_plan_id'] . "', `name` = '" . $this->db->escape((string)$data['name']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "', `trial_price` = '" . (float)$data['trial_price'] . "', `trial_frequency` = '" . $this->db->escape((string)$data['trial_frequency']) . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_status` = '" . (int)$data['trial_status'] . "', `price` = '" . (float)$data['price'] . "', `frequency` = '" . $this->db->escape((string)$data['frequency']) . "', `cycle` = '" . (int)$data['cycle'] . "', `duration` = '" . (int)$data['duration'] . "', `status` = '6', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	public function editReference(int $subscription_id, string $reference): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `reference` = '" . $this->db->escape($reference) . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	public function getSubscriptionByOrderProductId(int $order_product_id, array $data): int {
		$this->db->query("SELECT * FROM  `" . DB_PREFIX . "subscription` SET `date_added` = NOW(), `date_modified` = NOW() WHERE  `order_product_id` = '" . $order_product_id . "'");

		return $this->db->rows;
	}
}
