<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Geo Zone
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class GeoZone extends \Opencart\System\Engine\Model {
	/**
	 * Get Geo Zone
	 *
	 * @param int $geo_zone_id
	 * @param int $country_id
	 * @param int $zone_id
	 *
	 * @return array<string, mixed>
	 */
	public function getGeoZone(int $geo_zone_id, int $country_id, int $zone_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "' AND `country_id` = '" . (int)$country_id . "' AND (`zone_id` = '" . (int)$zone_id . "' OR `zone_id` = '0')");

		return $query->row;
	}

	/**
	 * Get Geo Zones
	 *
	 * @return array<string, mixed>
	 */
	public function getGeoZones(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "geo_zone` ORDER BY `name`");

		return $query->rows;
	}
}
