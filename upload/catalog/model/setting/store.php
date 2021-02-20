<?php
namespace Opencart\Catalog\Model\Setting;
class Store extends \Opencart\System\Engine\Model {
	public function getStore($store_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "store` WHERE `store_id` = '" . (int)$store_id . "'");

		return $query->row;
	}

	public function getStores() {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` ORDER BY `url`");

			$store_data = $query->rows;

			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}
}
