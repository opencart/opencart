<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Subscription Plan
 *
 * @package Opencart\Admin\Model\Catalog
 */
class SubscriptionPlan extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addSubscriptionPlan(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_plan` SET `trial_frequency` = '" . $this->db->escape((string)$data['trial_frequency']) . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_status` = '" . (int)$data['trial_status'] . "', `frequency` = '" . $this->db->escape((string)$data['frequency']) . "', `duration` = '" . (int)$data['duration'] . "', `cycle` = '" . (int)$data['cycle'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$subscription_plan_id = $this->db->getLastId();

		foreach ($data['subscription_plan_description'] as $language_id => $subscription_plan_description) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_plan_description` SET `subscription_plan_id` = '" . (int)$subscription_plan_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($subscription_plan_description['name']) . "'");
		}

		return $subscription_plan_id;
	}

	/**
	 * @param int   $subscription_plan_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editSubscriptionPlan(int $subscription_plan_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription_plan` SET  `trial_frequency` = '" . $this->db->escape((string)$data['trial_frequency']) . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_status` = '" . (int)$data['trial_status'] . "', `frequency` = '" . $this->db->escape((string)$data['frequency']) . "', `duration` = '" . (int)$data['duration'] . "', `cycle` = '" . (int)$data['cycle'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_plan_description` WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");

		foreach ($data['subscription_plan_description'] as $language_id => $subscription_plan_description) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_plan_description` SET `subscription_plan_id` = '" . (int)$subscription_plan_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($subscription_plan_description['name']) . "'");
		}
	}

	/**
	 * @param int $subscription_plan_id
	 *
	 * @return void
	 */
	public function copySubscriptionPlan(int $subscription_plan_id): void {
		$data = $this->getSubscriptionPlan($subscription_plan_id);

		$data['subscription_plan_description'] = $this->getDescription($subscription_plan_id);

		$this->addSubscriptionPlan($data);
	}

	/**
	 * @param int $subscription_plan_id
	 *
	 * @return void
	 */
	public function deleteSubscriptionPlan(int $subscription_plan_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_plan` WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_plan_description` WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_subscription` WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_plan_id` = '0' WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");
	}

	/**
	 * @param int $subscription_plan_id
	 *
	 * @return array
	 */
	public function getSubscriptionPlan(int $subscription_plan_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_plan` sp LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` spd ON (sp.`subscription_plan_id` = spd.`subscription_plan_id`) WHERE sp.`subscription_plan_id` = '" . (int)$subscription_plan_id . "' AND spd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param int $subscription_plan_id
	 *
	 * @return array
	 */
	public function getDescription(int $subscription_plan_id): array {
		$subscription_plan_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_plan_description` WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");

		foreach ($query->rows as $result) {
			$subscription_plan_description_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $subscription_plan_description_data;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getSubscriptionPlans(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "subscription_plan` sp LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` spd ON (sp.`subscription_plan_id` = spd.`subscription_plan_id`) WHERE spd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND spd.`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		$sort_data = [
			'spd.name',
			'sp.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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
	 * @return int
	 */
	public function getTotalSubscriptionPlans(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_plan`");

		return (int)$query->row['total'];
	}
}
