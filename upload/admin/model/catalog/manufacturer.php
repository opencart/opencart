<?php
namespace Opencart\Admin\Model\Catalog;
class Manufacturer extends \Opencart\System\Engine\Model {
	public function addManufacturer(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$manufacturer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `image` = '" . $this->db->escape((string)$data['image']) . "' WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
		}

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET `manufacturer_id` = '" . (int)$manufacturer_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		// SEO URL
		if (isset($data['manufacturer_seo_url'])) {
			foreach ($data['manufacturer_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'manufacturer_id', `value` = '" . (int)$manufacturer_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
				}
			}
		}

		if (isset($data['manufacturer_layout'])) {
			foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_layout` SET `manufacturer_id` = '" . (int)$manufacturer_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
			}
		}

		$this->cache->delete('manufacturer');

		return $manufacturer_id;
	}

	public function editManufacturer(int $manufacturer_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `image` = '" . $this->db->escape((string)$data['image']) . "' WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_store` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET `manufacturer_id` = '" . (int)$manufacturer_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'manufacturer_id' AND `value` = '" . (int)$manufacturer_id . "'");

		if (isset($data['manufacturer_seo_url'])) {
			foreach ($data['manufacturer_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'manufacturer_id', `value` = '" . (int)$manufacturer_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
				}
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		if (isset($data['manufacturer_layout'])) {
			foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_layout` SET `manufacturer_id` = '" . (int)$manufacturer_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
			}
		}

		$this->cache->delete('manufacturer');
	}

	public function deleteManufacturer(int $manufacturer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_store` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'manufacturer_id' AND `value` = '" . (int)$manufacturer_id . "'");

		$this->cache->delete('manufacturer');
	}

	public function getManufacturer(int $manufacturer_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "manufacturer` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		return $query->row;
	}

	public function getManufacturers(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "manufacturer`";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE `name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		$sort_data = [
			'name',
			'sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `name`";
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

	public function getStores(int $manufacturer_id): array {
		$manufacturer_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer_to_store` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_store_data[] = $result['store_id'];
		}

		return $manufacturer_store_data;
	}

	public function getSeoUrls(int $manufacturer_id): array {
		$manufacturer_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'manufacturer_id' AND `value` = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $manufacturer_seo_url_data;
	}

	public function getLayouts(int $manufacturer_id): array {
		$manufacturer_layout_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $manufacturer_layout_data;
	}

	public function getTotalManufacturers(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "manufacturer`");

		return (int)$query->row['total'];
	}

	public function getTotalManufacturersByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}
}
