<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Subscription Plan
 *
 * Can be loaded using $this->load->model('catalog/subscription_plan');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class SubscriptionPlan extends \Opencart\System\Engine\Model {
	/**
	 * Add Subscription Plan
	 *
	 * Create a new subscription plan record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new subscription plan record
	 *
	 * @example
	 *
	 * $subscription_data = [
	 *     'subscription_plan_description' => [],
	 *     'trial_frequency'               => 'month',
	 *     'trial_duration'                => 1,
	 *     'trial_cycle'                   => 5,
	 *     'trial_status'                  => 1,
	 *     'frequency'                     => 1,
	 *     'cycle'                         => 5,
	 *     'status'                        => 0,
	 *     'sort_order'                    => 0
	 * ];
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $subscription_plan_id = $this->model_catalog_subscription_plan->addSubscriptionPlan($subscription_data);
	 */
	public function addSubscriptionPlan(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_plan` SET `trial_frequency` = '" . $this->db->escape((string)$data['trial_frequency']) . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_status` = '" . (int)$data['trial_status'] . "', `frequency` = '" . $this->db->escape((string)$data['frequency']) . "', `duration` = '" . (int)$data['duration'] . "', `cycle` = '" . (int)$data['cycle'] . "', `status` = '" . (bool)$data['status'] . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$subscription_plan_id = $this->db->getLastId();

		foreach ($data['subscription_plan_description'] as $language_id => $subscription_plan_description) {
			$this->model_catalog_subscription_plan->addDescription($subscription_plan_id, $language_id, $subscription_plan_description);
		}

		return $subscription_plan_id;
	}

	/**
	 * Edit Subscription Plan
	 *
	 * Edit subscription plan record in the database.
	 *
	 * @param int                  $subscription_plan_id primary key of the subscription plan record
	 * @param array<string, mixed> $data                 array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $subscription_plan_data = [
	 *     'subscription_plan_description' => [],
	 *     'trial_frequency'               => 'month',
	 *     'trial_duration'                => 1,
	 *     'trial_cycle'                   => 5,
	 *     'trial_status'                  => 1,
	 *     'frequency'                     => 1,
	 *     'cycle'                         => 5,
	 *     'status'                        => 1,
	 *     'sort_order'                    => 0
	 * ];
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $this->model_catalog_subscription_plan->editSubscriptionPlan($subscription_plan_id, $subscription_plan_data);
	 */
	public function editSubscriptionPlan(int $subscription_plan_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription_plan` SET `trial_frequency` = '" . $this->db->escape((string)$data['trial_frequency']) . "', `trial_duration` = '" . (int)$data['trial_duration'] . "', `trial_cycle` = '" . (int)$data['trial_cycle'] . "', `trial_status` = '" . (int)$data['trial_status'] . "', `frequency` = '" . $this->db->escape((string)$data['frequency']) . "', `duration` = '" . (int)$data['duration'] . "', `cycle` = '" . (int)$data['cycle'] . "', `status` = '" . (bool)$data['status'] . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");

		$this->model_catalog_subscription_plan->deleteDescriptions($subscription_plan_id);

		foreach ($data['subscription_plan_description'] as $language_id => $subscription_plan_description) {
			$this->model_catalog_subscription_plan->addDescription($subscription_plan_id, $language_id, $subscription_plan_description);
		}
	}

	/**
	 * Copy Subscription Plan
	 *
	 * @param int $subscription_plan_id primary key of the subscription plan record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $this->model_catalog_subscription_plan->copySubscriptionPlan($subscription_plan_id);
	 */
	public function copySubscriptionPlan(int $subscription_plan_id): void {
		$this->model_catalog_subscription_plan->addSubscriptionPlan($this->model_catalog_subscription_plan->getSubscriptionPlan($subscription_plan_id) + ['subscription_plan_description' => $this->model_catalog_subscription_plan->getDescription($subscription_plan_id)]);
	}

	/**
	 * Delete Subscription Plan
	 *
	 * Delete subscription plan record in the database.
	 *
	 * @param int $subscription_plan_id primary key of the subscription plan record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $this->model_catalog_subscription_plan->deleteSubscriptionPlan($subscription_plan_id);
	 */
	public function deleteSubscriptionPlan(int $subscription_plan_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_plan` WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");

		$this->model_catalog_subscription_plan->deleteDescriptions($subscription_plan_id);

		// Product
		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteSubscriptionsBySubscriptionPlanId($subscription_plan_id);
	}

	/**
	 * Get Subscription Plan
	 *
	 * Get the record of the subscription plan record in the database.
	 *
	 * @param int $subscription_plan_id primary key of the subscription plan record
	 *
	 * @return array<string, mixed> subscription plan record that has subscription plan ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $subscription_info = $this->model_catalog_subscription_plan->getSubscriptionPlan($subscription_plan_id);
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
	 * $filter_data = [
	 *     'sort'  => 'rd.name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $subscription_plan = $this->model_catalog_subscription_plan->getSubscriptionPlans($filter_data);
	 */
	public function getSubscriptionPlans(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "subscription_plan` `sp` LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` `spd` ON (`sp`.`subscription_plan_id` = `spd`.`subscription_plan_id`) WHERE `spd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`spd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		$sort_data = [
			'spd.name',
			'sp.sort_order',
			'sp.status'
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
	 * Edit Status
	 *
	 * Edit subscription_plan status record in the database.
	 *
	 * @param int  $subscription_plan_id primary key of the subscription_plan record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $this->model_catalog_subscription_plan->editStatus($subscription_plan_id, $status);
	 */
	public function editStatus(int $subscription_plan_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription_plan` SET `status` = '" . (bool)$status . "' WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");
	}

	/**
	 * Add Description
	 *
	 * Create a new subscription plan description record in the database.
	 *
	 * @param int                  $subscription_plan_id primary key of the subscription plan record
	 * @param int                  $language_id          primary key of the language record
	 * @param array<string, mixed> $data                 array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $subscription_data['subscription_plan_description'] = [
	 *     'name' => 'Subscription Plan Name'
	 * ];
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $this->model_catalog_subscription_plan->addDescription($subscription_plan_id, $language_id, $subscription_data);
	 */
	public function addDescription(int $subscription_plan_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_plan_description` SET `subscription_plan_id` = '" . (int)$subscription_plan_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete subscription plan description records in the database.
	 *
	 * @param int $subscription_plan_id primary key of the subscription plan record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $this->model_catalog_subscription_plan->deleteDescriptions($subscription_plan_id);
	 */
	public function deleteDescriptions(int $subscription_plan_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_plan_description` WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete subscription plan descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $this->model_catalog_subscription_plan->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_plan_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the subscription plan description records in the database.
	 *
	 * @param int $subscription_plan_id primary key of the subscription plan record
	 *
	 * @return array<int, array<string, string>> description records that have subscription plan ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $subscription_plan_description = $this->model_catalog_subscription_plan->getDescriptions($subscription_plan_id);
	 */
	public function getDescriptions(int $subscription_plan_id): array {
		$subscription_plan_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_plan_description` WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");

		foreach ($query->rows as $result) {
			$subscription_plan_description_data[$result['language_id']] = $result;
		}

		return $subscription_plan_description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the subscription plan descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/subscription_plan');
	 *
	 * $results = $this->model_catalog_subscription_plan->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_plan_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Subscription Plans
	 *
	 * Get the total number of subscription plan records in the database.
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
