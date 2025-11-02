<?php
namespace Opencart\Catalog\Model\Catalog;
/**
 * Class Subscription Plan
 *
 * Can be called using $this->load->model('catalog/subscription_plan');
 *
 * @package Opencart\Catalog\Model\Catalog
 */
class SubscriptionPlan extends \Opencart\System\Engine\Model {
	/**
	 * Get Subscription Plan
	 *
	 * Get the record of the subscription plan record in the database.
	 *
	 * @param int $subscription_plan_id primary key of the subscription plan record
	 *
	 * @return array<string, mixed> subscription plan record that have subscription plan ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $subscription_plan_info = $this->model_catalog_subscription_plan->getSubscriptionPlan($subscription_plan_id);
	 */
	public function getSubscriptionPlan(int $subscription_plan_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_plan` `sp` LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` `spd` ON (`sp`.`subscription_plan_id` = `spd`.`subscription_plan_id`) WHERE `sp`.`subscription_plan_id` = '" . (int)$subscription_plan_id . "' AND `spd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Subscription Plans
	 *
	 * Get the record of the subscription plan records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> subscription plan records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $results = $this->model_catalog_subscription_plan->getSubscriptionPlans();
	 */
	public function getSubscriptionPlans(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "subscription_plan` `sp` LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` `spd` ON (`sp`.`subscription_plan_id` = `spd`.`subscription_plan_id`) WHERE `spd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND `spd`.`name` LIKE '" . $this->db->escape($data['filter_name'] . '%') . "'";
		}

		$sort_data = [
			'name'       => 'spd.name',
			'sort_order' => 'sp.order_id'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `spd`.`name`";
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
	 * Get Total Subscription Plans
	 *
	 * Get the total number of total subscription plan records in the database.
	 *
	 * @return int total number of subscription plan records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $subscription_plan_total = $this->model_catalog_subscription_plan->getTotalSubscriptionPlans();
	 */
	public function getTotalSubscriptionPlans(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_plan`");

		return (int)$query->row['total'];
	}
}
