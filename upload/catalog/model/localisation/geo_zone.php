<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Geo Zone
 *
 * Can be called using $this->load->model('localisation/geo_zone');
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class GeoZone extends \Opencart\System\Engine\Model {
	/**
	 * Get Geo Zone
	 *
	 * Get the record of the geo zone record in the database.
	 *
	 * @param int $geo_zone_id primary key of the geo zone record
	 * @param int $country_id  primary key of the country record
	 * @param int $zone_id     primary key of the zone record
	 *
	 * @return array<string, mixed> geo zone record that has geo zone ID, country ID, zone ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $geo_zone_info = $this->model_localisation_geo_zone->getZone($geo_zone_id, $country_id, $zone_id);
	 */
	public function getGeoZone(int $geo_zone_id, int $country_id, int $zone_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "' AND `country_id` = '" . (int)$country_id . "' AND (`zone_id` = '" . (int)$zone_id . "' OR `zone_id` = '0')");

		return $query->row;
	}

	/**
	 * Get Geo Zones
	 *
	 * Get the record of the geo zone records in the database.
	 *
	 * @return array<string, mixed> geo zone records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/geo_zone');
	 *
	 * $geo_zones = $this->model_localisation_geo_zone->getGeoZones();
	 */
	public function getGeoZones(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "geo_zone` ORDER BY `name`");

		return $query->rows;
	}
}
