<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Subscription Status
 *
 * Can be called using $this->load->model('localisation/subscription_status');
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class SubscriptionStatus extends \Opencart\System\Engine\Model {
	/**
	 * Get Subscription Status
	 *
	 * Get the record of the subscription status record in the database.
	 *
	 * @param int $subscription_status_id primary key of the subscription status record
	 *
	 * @return array<string, mixed> subscription status record that has subscription status ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/subscription_status');
	 *
	 * $subscription_status_info = $this->model_localisation_subscription_status->getSubscriptionStatus($subscription_status_id);
	 */
	public function getSubscriptionStatus(int $subscription_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `subscription_status_id` = '" . (int)$subscription_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Subscription Statuses
	 *
	 * Get the record of the subscription status records in the database.
	 *
	 * @return array<int, array<string, mixed>> subscription status records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/subscription_status');
	 *
	 * $subscription_statuses = $this->model_localisation_subscription_status->getSubscriptionStatuses();
	 */
	public function getSubscriptionStatuses(): array {
		$sql = "SELECT `subscription_status_id`, `name` FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`";

		$key = md5($sql);

		$subscription_status_data = $this->cache->get('subscription_status.' . $key);

		if (!$subscription_status_data) {
			$query = $this->db->query($sql);

			$subscription_status_data = $query->rows;

			$this->cache->set('subscription_status.' . $key, $subscription_status_data);
		}

		return $subscription_status_data;
	}
}
