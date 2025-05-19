<?php
namespace Opencart\Catalog\Model\Catalog;
/**
 * Class Manufacturer
 *
 * Can be called using $this->load->model('catalog/manufacturer');
 *
 * @package Opencart\Catalog\Model\Catalog
 */
class Manufacturer extends \Opencart\System\Engine\Model {
	/**
	 * Get Manufacturer
	 *
	 * Get the record of the manufacturer record in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return array<string, mixed> manufacturer record that has manufacturer ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
	 */
	public function getManufacturer(int $manufacturer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer` `m` LEFT JOIN `" . DB_PREFIX . "manufacturer_description` `md` ON (`m`.`manufacturer_id` = `md`.`manufacturer_id`) LEFT JOIN `" . DB_PREFIX . "manufacturer_to_store` `m2s` ON (`m`.`manufacturer_id` = `m2s`.`manufacturer_id`) WHERE `m`.`manufacturer_id` = '" . (int)$manufacturer_id . "' AND `m2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `md`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Manufacturer(s)
	 *
	 * Get the record of the manufacturer records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> manufacturer records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $results = $this->model_catalog_manufacturer->getManufacturers();
	 */
	public function getManufacturers(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "manufacturer` `m` LEFT JOIN `" . DB_PREFIX . "manufacturer_description` `md` ON (`m`.`manufacturer_id` = `md`.`manufacturer_id`) LEFT JOIN `" . DB_PREFIX . "manufacturer_to_store` `m2s` ON (`m`.`manufacturer_id` = `m2s`.`manufacturer_id`) WHERE `m2s`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `md`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

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

		$key = md5($sql);

		$manufacturer_data = $this->cache->get('manufacturer.' . $key);

		if (!$manufacturer_data) {
			$query = $this->db->query($sql);

			$manufacturer_data = $query->rows;

			$this->cache->set('manufacturer.' . $key, $manufacturer_data);
		}

		return $manufacturer_data;
	}

	/**
	 * Get Layout ID
	 *
	 * Get the record of the manufacturer layout record in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return int layout record that has manufacturer ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $layout_id = $this->model_catalog_manufacturer->getLayoutId($manufacturer_id);
	 */
	public function getLayoutId(int $manufacturer_id): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}
