<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class SubscriptionStatus
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class SubscriptionStatus extends \Opencart\System\Engine\Model {
	/**
	 * @param int $subscription_status_id
	 *
	 * @return array
	 */
	public function getSubscriptionStatus(int $subscription_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `subscription_status_id` = '" . (int)$subscription_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @return array
	 */
	public function getSubscriptionStatuses(): array {
		$sql = "SELECT `subscription_status_id`, `name` FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`";

		$subscription_status_data = $this->cache->get('subscription_status.'. md5($sql));

		if (!$subscription_status_data) {
			$query = $this->db->query($sql);

			$subscription_status_data = $query->rows;

			$this->cache->set('subscription_status.'. md5($sql), $subscription_status_data);
		}

		return $subscription_status_data;
	}
}