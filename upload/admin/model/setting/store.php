<?php
namespace Opencart\Admin\Model\Setting;
class Store extends \Opencart\System\Engine\Model {
	public function addStore(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "store` SET `name` = '" . $this->db->escape((string)$data['config_name']) . "', `url` = '" . $this->db->escape((string)$data['config_url']) . "'");

		$store_id = $this->db->getLastId();

		// Layout Route
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE `store_id` = '0'");

		foreach ($query->rows as $layout_route) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_route` SET `layout_id` = '" . (int)$layout_route['layout_id'] . "', `route` = '" . $this->db->escape($layout_route['route']) . "', `store_id` = '" . (int)$store_id . "'");
		}

		$this->cache->delete('store');

		return $store_id;
	}

	public function editStore(int $store_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "store` SET `name` = '" . $this->db->escape((string)$data['config_name']) . "', `url` = '" . $this->db->escape((string)$data['config_url']) . "' WHERE `store_id` = '" . (int)$store_id . "'");

		$this->cache->delete('store');
	}

	public function deleteStore(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "store` WHERE `store_id` = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_route` WHERE `store_id` = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `store_id` = '" . (int)$store_id . "'");

		$this->cache->delete('store');
	}

	public function getStore(int $store_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "store` WHERE `store_id` = '" . (int)$store_id . "'");

		return $query->row;
	}

	public function getStores(array $data = []): array {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` ORDER BY `url`");

			$store_data = $query->rows;

			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}

	public function getTotalStores(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "store`");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_layout_id' AND `value` = '" . (int)$layout_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByLanguage(string $language): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_language' AND `value` = '" . $this->db->escape($language) . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByCurrency(string $currency): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_currency' AND `value` = '" . $this->db->escape($currency) . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByCountryId(int $country_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_country_id' AND `value` = '" . (int)$country_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByZoneId(int $zone_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_zone_id' AND `value` = '" . (int)$zone_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByCustomerGroupId(int $customer_group_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_customer_group_id' AND `value` = '" . (int)$customer_group_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByInformationId(int $information_id): int {
		$account_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_account_id' AND `value` = '" . (int)$information_id . "' AND `store_id` != '0'");

		$checkout_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_checkout_id' AND `value` = '" . (int)$information_id . "' AND `store_id` != '0'");

		return ($account_query->row['total'] + $checkout_query->row['total']);
	}

	public function getTotalStoresByOrderStatusId(int $order_status_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_order_status_id' AND `value` = '" . (int)$order_status_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}
}
