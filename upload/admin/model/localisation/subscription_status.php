<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class SubscriptionStatus
 *
 * @package Opencart\Admin\Model\Localisation
 */
class SubscriptionStatus extends \Opencart\System\Engine\Model {
	/**
	 * Add Subscription Status
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return ?int
	 */
	public function addSubscriptionStatus(array $data): ?int {
		$subscription_status_id = 0;

		foreach ($data['subscription_status'] as $language_id => $subscription_status) {
			if (!$subscription_status_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_status` SET `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($subscription_status['name']) . "'");

				$subscription_status_id = $this->db->getLastId();
			} else {
				$this->model_localisation_subscription_status->addDescription($subscription_status_id, $language_id, $subscription_status);
			}
		}

		$this->cache->delete('subscription_status');

		return $subscription_status_id;
	}

	/**
	 * Edit Subscription Status
	 *
	 * @param int                  $subscription_status_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editSubscriptionStatus(int $subscription_status_id, array $data): void {
		$this->deleteSubscriptionStatus($subscription_status_id);

		foreach ($data['subscription_status'] as $language_id => $subscription_status) {
			$this->model_localisation_subscription_status->addDescription($subscription_status_id, $language_id, $subscription_status);
		}

		$this->cache->delete('subscription_status');
	}

	/**
	 * Delete Subscription Status
	 *
	 * @param int $subscription_status_id
	 *
	 * @return void
	 */
	public function deleteSubscriptionStatus(int $subscription_status_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_status` WHERE `subscription_status_id` = '" . (int)$subscription_status_id . "'");

		$this->cache->delete('subscription_status');
	}

	/**
	 * Delete Subscription Statuses By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteStockStatusesByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('subscription_status');
	}

	/**
	 * Get Subscription Status
	 *
	 * @param int $subscription_status_id
	 *
	 * @return array<string, mixed>
	 */
	public function getSubscriptionStatus(int $subscription_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `subscription_status_id` = '" . (int)$subscription_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Subscription Statuses
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getSubscriptionStatuses(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`";

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

		$key = md5($sql);

		$subscription_status_data = $this->cache->get('subscription_status.' . $key);

		if (!$subscription_status_data) {
			$query = $this->db->query($sql);

			$subscription_status_data = $query->rows;

			$this->cache->set('subscription_status.' . $key, $subscription_status_data);
		}

		return $subscription_status_data;
	}

	/**
	 * Add Description
	 *
	 * @param int                  $subscription_status_id
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addDescription(int $subscription_status_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_status` SET `subscription_status_id` = '" . (int)$subscription_status_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $subscription_status_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptions(int $subscription_status_id): array {
		$subscription_status_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `subscription_status_id` = '" . (int)$subscription_status_id . "'");

		foreach ($query->rows as $result) {
			$subscription_status_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $subscription_status_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Subscription Statuses
	 *
	 * @return int
	 */
	public function getTotalSubscriptionStatuses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return (int)$query->row['total'];
	}
}
