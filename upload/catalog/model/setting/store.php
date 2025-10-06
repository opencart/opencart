<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Store
 *
 * Can be called using $this->load->model('setting/store');
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Store extends \Opencart\System\Engine\Model {
	/**
	 * Get Store
	 *
	 * Get the record of the store record in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return array<string, mixed> store record that has store ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_info = $this->model_setting_store->getStore($store_id);
	 */
	public function getStore(int $store_id): array {
		if ($store_id == 0) {
			return [
				'store_id'  => 0,
				'logo'      => html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8'),
				'name'      => html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'),
				'store_url' => $this->config->get('config_url')
			];
		}

		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "store` WHERE `store_id` = '" . (int)$store_id . "'");

		return $query->row;
	}

	/**
	 * Get Store By Hostname
	 *
	 * @param string $url
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_info = $this->model_setting_store->getStoreByHostname($url);
	 */
	public function getStoreByHostname(string $url): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape($url) . "'");

		return $query->row;
	}

	/**
	 * Get Stores
	 *
	 * Get the record of the store records in the database.
	 *
	 * @return array<int, array<string, mixed>> store records
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $stores = $this->model_setting_store->getStores();
	 */
	public function getStores(): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "store` ORDER BY `url`";

		$key = md5($sql);

		$store_data = $this->cache->get('store.' . $key);

		if (!$store_data) {
			$query = $this->db->query($sql);

			$store_data = $query->rows;

			$this->cache->set('store.' . $key, $store_data);
		}

		return $store_data;
	}
}
