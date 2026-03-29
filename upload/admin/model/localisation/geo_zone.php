<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Geo Zone
 *
 * Can be loaded using $this->load->model('localisation/geo_zone');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class GeoZone extends \Opencart\System\Engine\Model {
	/**
	 * Add Geo Zone
	 *
	 * Create a new geo zone record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new geo zone record
	 *
	 * @example
	 *
	 * $geo_zone_data = [
	 *     'name'        => 'Geo Zone Name',
	 *     'description' => 'Geo Zone Description'
	 * ];
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $geo_zone_id = $this->model_localisation_geo_zone->addGeoZone($geo_zone_data);
	 */
	public function addGeoZone(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "geo_zone` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "'");

		$geo_zone_id = $this->db->getLastId();

		if (isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $zone_to_geo_zone) {
				$this->addZone($geo_zone_id, $zone_to_geo_zone);
			}
		}

		$this->cache->delete('geo_zone');

		return $geo_zone_id;
	}

	/**
	 * Edit Geo Zone
	 *
	 * Edit geo zone record in the database.
	 *
	 * @param int                  $geo_zone_id primary key of the geo zone record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $geo_zone_data = [
	 *     'name'        => 'Geo Zone Name',
	 *     'description' => 'Geo Zone Description'
	 * ];
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $this->model_localisation_geo_zone->editGeoZone($geo_zone_id, $geo_zone_data);
	 */
	public function editGeoZone(int $geo_zone_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "geo_zone` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "' WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "'");

		$this->deleteZones($geo_zone_id);

		if (isset($data['zone_to_geo_zone'])) {
			foreach ($data['zone_to_geo_zone'] as $zone_to_geo_zone) {
				$this->addZone($geo_zone_id, $zone_to_geo_zone);
			}
		}

		$this->cache->delete('geo_zone');
	}

	/**
	 * Delete Geo Zone
	 *
	 * Delete geo zone record in the database.
	 *
	 * @param int $geo_zone_id primary key of the geo zone record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $this->model_localisation_geo_zone->deleteGeoZone($geo_zone_id);
	 */
	public function deleteGeoZone(int $geo_zone_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "geo_zone` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "'");

		$this->deleteZones($geo_zone_id);

		$this->cache->delete('geo_zone');
	}

	/**
	 * Get Geo Zone
	 *
	 * Get the record of the geo zone record in the database.
	 *
	 * @param int $geo_zone_id primary key of the geo zone record
	 *
	 * @return array<string, mixed> geo zone record that has geo zone ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($geo_zone_id);
	 */
	public function getGeoZone(int $geo_zone_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "geo_zone` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "'");

		return $query->row;
	}

	/**
	 * Get Geo Zones
	 *
	 * Get the record of the geo zone records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> geo zone records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $results = $this->model_localisation_geo_zone->getGeoZones($filter_data);
	 */
	public function getGeoZones(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "geo_zone`";

		$sort_data = [
			'name',
			'description'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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

		$geo_zone_data = $this->cache->get('geo_zone.' . $key);

		if (!$geo_zone_data) {
			$query = $this->db->query($sql);

			$geo_zone_data = $query->rows;

			$this->cache->set('geo_zone.' . $key, $geo_zone_data);
		}

		return $geo_zone_data;
	}

	/**
	 * Get Total Geo Zones
	 *
	 * Get the total number of geo zone records in the database.
	 *
	 * @return int total number of geo zone records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $geo_zone_total = $this->model_localisation_geo_zone->getTotalGeoZones($filter_data);
	 */
	public function getTotalGeoZones(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "geo_zone`");

		return (int)$query->row['total'];
	}

	/**
	 * Add Zone
	 *
	 * Create a new zone to geo zone record in the database.
	 *
	 * @param int                  $geo_zone_id primary key of the geo zone record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $zone_to_geo_zone_data = [
	 *     'geo_zone_id' => 1,
	 *     'country_id'  => 1,
	 *     'zone_id'     => 1
	 * ];
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $this->model_geo_zone->addZone($geo_zone_id, $zone_to_geo_zone_data);
	 */
	public function addZone(int $geo_zone_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "zone_to_geo_zone` SET `geo_zone_id` = '" . (int)$geo_zone_id . "', `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "'");
	}

	/**
	 * Delete Zones
	 *
	 * Delete zone to geo zone record in the database.
	 *
	 * @param int $geo_zone_id primary key of the geo zone record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $this->model_geo_zone->deleteZones($geo_zone_id);
	 */
	public function deleteZones(int $geo_zone_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "'");
	}

	/**
	 * Get Zones
	 *
	 * Get the record of the zone to geo zone records in the database.
	 *
	 * @param int $geo_zone_id primary key of the geo zone record
	 *
	 * @return array<int, array<string, mixed>> geo zone records that have geo zone ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $results = $this->model_localisation_geo_zone->getGeoZones($geo_zone_id);
	 */
	public function getZones(int $geo_zone_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Zones
	 *
	 * Get the total number of zone to geo zone records in the database.
	 *
	 * @param int $geo_zone_id primary key of the geo zone record
	 *
	 * @return int total number of zone records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $geo_zone_total = $this->model_localisation_geo_zone->getTotalZones($geo_zone_id);
	 */
	public function getTotalZones(int $geo_zone_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Zone To Geo Zone By Country ID
	 *
	 * Get the total number of zone to geo zone by country records in the database.
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return int total number of zone to geo zone records that have country ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByCountryId($country_id);
	 */
	public function getTotalZoneToGeoZoneByCountryId(int $country_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `country_id` = '" . (int)$country_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Zone To Geo Zone By Zone ID
	 *
	 * Get the total number of zone to geo zone by zone records in the database.
	 *
	 * @param int $zone_id primary key of the zone record
	 *
	 * @return int total number of zone to geo zone records that have zone ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByZoneId($zone_id);
	 */
	public function getTotalZoneToGeoZoneByZoneId(int $zone_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `zone_id` = '" . (int)$zone_id . "'");

		return (int)$query->row['total'];
	}
}
