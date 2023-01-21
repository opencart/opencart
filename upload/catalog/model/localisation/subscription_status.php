<?php
namespace Opencart\Catalog\Model\Localisation;
class SubscriptionStatus extends \Opencart\System\Engine\Model {
	public function getSubscriptionStatus(int $subscription_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `subscription_status_id` = '" . (int)$subscription_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getSubscriptionStatuses(): array {
		$subscription_status_data = $this->cache->get('subscription_status.' . (int)$this->config->get('config_language_id'));

		if (!$subscription_status_data) {
			$query = $this->db->query("SELECT `subscription_status_id`, `name` FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`");

			$subscription_status_data = $query->rows;

			$this->cache->set('subscription_status.' . (int)$this->config->get('config_language_id'), $subscription_status_data);
		}

		return $subscription_status_data;
	}
}
