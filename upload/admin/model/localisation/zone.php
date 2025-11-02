<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Zone
 *
 * Can be loaded using $this->load->model('localisation/zone');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Zone extends \Opencart\System\Engine\Model {
	/**
	 * Add Zone
	 *
	 * Create a new zone record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $zone_data = [
	 *     'zone_description' => [],
	 *     'code'             => 'Zone Code',
	 *     'country_id'       => 1,
	 *     'status'           => 0
	 * ];
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $zone_id = $this->model_localisation_zone->addZone($zone_data);
	 */
	public function addZone(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "zone` SET `code` = '" . $this->db->escape((string)$data['code']) . "', `country_id` = '" . (int)$data['country_id'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$zone_id = $this->db->getLastId();

		foreach ($data['zone_description'] as $language_id => $zone_description) {
			$this->model_localisation_zone->addDescription($zone_id, $language_id, $zone_description);
		}

		$this->cache->delete('zone');

		return $zone_id;
	}

	/**
	 * Edit Zone
	 *
	 * Edit zone record in the database.
	 *
	 * @param int                  $zone_id primary key of the zone record
	 * @param array<string, mixed> $data    array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $zone_data = [
	 *     'zone_description' => [],
	 *     'code'             => 'Zone Code',
	 *     'country_id'       => 1,
	 *     'status'           => 1
	 * ];
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $this->model_localisation_zone->editZone($zone_id, $zone_data);
	 */
	public function editZone(int $zone_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "zone` SET `code` = '" . $this->db->escape((string)$data['code']) . "', `country_id` = '" . (int)$data['country_id'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `zone_id` = '" . (int)$zone_id . "'");

		$this->model_localisation_zone->deleteDescriptions($zone_id);

		foreach ($data['zone_description'] as $language_id => $zone_description) {
			$this->model_localisation_zone->addDescription($zone_id, $language_id, $zone_description);
		}

		$this->cache->delete('zone');
	}

	/**
	 * Delete Zone
	 *
	 * Delete zone record in the database.
	 *
	 * @param int $zone_id primary key of the zone record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $this->model_localisation_zone->deleteZone($zone_id);
	 */
	public function deleteZone(int $zone_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "zone` WHERE `zone_id` = '" . (int)$zone_id . "'");

		$this->model_localisation_zone->deleteDescriptions($zone_id);

		$this->cache->delete('zone');
	}

	/**
	 * Get Zone
	 *
	 * Get the record of the zone record in the database.
	 *
	 * @param int $zone_id primary key of the zone record
	 *
	 * @return array<string, mixed> zone record that has zone ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $zone_info = $this->model_localisation_zone->getZone($zone_id);
	 */
	public function getZone(int $zone_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "zone` `z` LEFT JOIN `" . DB_PREFIX . "zone_description` `zd` ON (`z`.`zone_id` = `zd`.`zone_id`) WHERE `z`.`zone_id` = '" . (int)$zone_id . "' AND `zd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Zones
	 *
	 * Get the record of the zone records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> zone records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'    => 'Zone Name',
	 *     'filter_country' => 'Country Name',
	 *     'filter_code'    => 'Zone Code',
	 *     'sort'           => 'c.name',
	 *     'order'          => 'DESC',
	 *     'start'          => 0,
	 *     'limit'          => 10
	 * ];
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $results = $this->model_localisation_zone->getZones($filter_data);
	 */
	public function getZones(array $data = []): array {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT *, `zd`.`name` AS `name`, `cd`.`name` AS `country` FROM `" . DB_PREFIX . "zone` `z` LEFT JOIN `" . DB_PREFIX . "zone_description` `zd` ON (`z`.`zone_id` = `zd`.`zone_id`) LEFT JOIN `" . DB_PREFIX . "country_description` `cd` ON (`z`.`country_id` = `cd`.`country_id`) WHERE `zd`.`language_id` = '" . (int)$language_id . "' AND `cd`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`zd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_country_id'])) {
			$sql .= " AND `z`.`country_id` = '" . $this->db->escape(oc_strtolower($data['filter_country_id']) . '%') . "'";
		}

		if (!empty($data['filter_code'])) {
			$sql .= " AND LCASE(`z`.`code`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_code']) . '%') . "'";
		}

		$sort_data = [
			'country' => 'cd.name',
			'name'    => 'zd.name',
			'code'    => 'z.code'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `cd`.`name` ASC, `zd`.`name`";
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

	/**
	 * Get Zones By Country ID
	 *
	 * Get the record of zones by country records in the database.
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return array<int, array<string, mixed>> zone records that have country ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $zones = $this->model_localisation_zone->getZonesByCountryId($country_id);
	 */
	public function getZonesByCountryId(int $country_id, int $language_id = 0): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "zone` `z` LEFT JOIN `" . DB_PREFIX . "zone_description` `zd` ON (`z`.`zone_id` = `zd`.`zone_id`) WHERE `z`.`country_id` = '" . (int)$country_id . "' AND `zd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `zd`.`name`";

		$key = md5($sql);

		$zone_data = $this->cache->get('zone.' . $key);

		if (!$zone_data) {
			$query = $this->db->query($sql);

			$zone_data = $query->rows;

			$this->cache->set('zone.' . $key, $zone_data);
		}

		return $zone_data;
	}

	/**
	 * Get Total Zones
	 *
	 * Get the total number of total zone records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of zone records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'    => 'Zone Name',
	 *     'filter_country' => 'Country Name',
	 *     'filter_code'    => 'Zone Code',
	 *     'sort'           => 'c.name',
	 *     'order'          => 'DESC',
	 *     'start'          => 0,
	 *     'limit'          => 10
	 * ];
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $zone_total = $this->model_localisation_zone->getTotalZones();
	 */
	public function getTotalZones(array $data = []): int {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "zone` `z`";

		if (!empty($data['filter_name'])) {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "zone_description` `zd` ON (`z`.`zone_id` = `zd`.`zone_id`) AND `zd`.`language_id` = '" . (int)$language_id . "'";
		}

		if (!empty($data['filter_country'])) {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "country_description` `cd` ON (`z`.`country_id` = `cd`.`country_id` AND `cd`.`language_id` = '" . (int)$language_id . "')";
		}

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(`zd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_country_id'])) {
			$implode[] = "`z`.`country_id` = '" . $this->db->escape(oc_strtolower($data['filter_country_id']) . '%') . "'";
		}

		if (!empty($data['filter_country'])) {
			$implode[] = "LCASE(`cd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_country']) . '%') . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "LCASE(`z`.`code`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_code']) . '%') . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Zones By Country ID
	 *
	 * Get the total number of total zones by country records in the database.
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return int total number of zone records that have country ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $zone_total = $this->model_localisation_zone->getTotalZonesByCountryId($country_id);
	 */
	public function getTotalZonesByCountryId(int $country_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new zone description record in the database.
	 *
	 * @param int                  $zone_id     primary key of the zone record
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $zone_data['zone_description'] = [
	 *     'name' => 'Zone Name',
	 * ];
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $this->model_catalog_category->addDescription($zone_id, $language_id, $zone_data);
	 */
	public function addDescription(int $zone_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "zone_description` SET `zone_id` = '" . (int)$zone_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete zone description records in the database.
	 *
	 * @param int $zone_id primary key of the zone record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $this->model_localisation_zone->deleteDescriptions($zone_id);
	 */
	public function deleteDescriptions(int $zone_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "zone_description` WHERE `zone_id` = '" . (int)$zone_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete zone descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $this->model_localisation_zone->deleteDescriptionsByLanguageId($country_id, $language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "zone_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Description
	 *
	 * Get the record of the zone description record in the database.
	 *
	 * @param int $zone_id primary key of the zone record
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<string, mixed> description record that has zone ID and language ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $zone_description = $this->model_localisation_zone->getDescription($zone_id, $language_id);
	 */
	public function getDescription(int $zone_id, $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_description` WHERE `zone_id` = '" . (int)$zone_id . "' AND `language_id` = '" . (int)$language_id . "'");

		return $query->row;
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the zone description records in the database.
	 *
	 * @param int $zone_id primary key of the zone record
	 *
	 * @return array<int, array<string, string>> description records that have zone ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $zone_description = $this->model_localisation_zone->getDescriptions($zone_id);
	 */
	public function getDescriptions(int $zone_id): array {
		$zone_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_description` WHERE `zone_id` = '" . (int)$zone_id . "'");

		foreach ($query->rows as $result) {
			$zone_description_data[$result['language_id']] = $result;
		}

		return $zone_description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the zone descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $results = $this->model_localisation_zone->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
