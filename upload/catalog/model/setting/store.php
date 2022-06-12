<?php
namespace Opencart\Catalog\Model\Setting;
class Store extends \Opencart\System\Engine\Model {
	public function getStore(int $store_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "store` WHERE `store_id` = '" . (int)$store_id . "'");

		return $query->row;
	}

	public function getStoreByHostname(string $url): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape($url) . "'");

		return $query->row;
	}

	public function getStores(): array {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` ORDER BY `url`");

			$store_data = $query->rows;

			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}
}
