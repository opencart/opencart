<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Geo Zone
 *
 * @example $geo_zone_model = $this->model_localisation_geo_zone;
 *
 * Can be called from $this->load->model('localisation/geo_zone');
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class GeoZone extends \Opencart\System\Engine\Model {
	/**
	 * Get Geo Zone
	 *
	 * @param int $geo_zone_id primary key of the geo zone record
	 * @param int $country_id  primary key of the country record
	 * @param int $zone_id     primary key of the zone record
	 *
	 * @return array<string, mixed> geo zone record that has geo zone ID, country ID, zone ID
	 */
	public function getGeoZone(int $geo_zone_id, int $country_id, int $zone_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "' AND `country_id` = '" . (int)$country_id . "' AND (`zone_id` = '" . (int)$zone_id . "' OR `zone_id` = '0')");

		return $query->row;
	}

	/**
	 * Get Geo Zones
	 *
	 * @return array<string, mixed> geo zone records
	 */
	public function getGeoZones(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "geo_zone` ORDER BY `name`");

		return $query->rows;
	}
}
