<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Zone
 *
 * Can be called using $this->load->model('localisation/zone');
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class Zone extends \Opencart\System\Engine\Model {
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
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` `z` LEFT JOIN `" . DB_PREFIX . "zone_description` `zd` ON (`z`.`zone_id` = `zd`.`zone_id`) WHERE `z`.`zone_id` = '" . (int)$zone_id . "' AND `zd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `z`.`status` = '1'");

		return $query->row;
	}

	/**
	 * Get Zones By Country ID
	 *
	 * Get the record of the zones by country record in the database.
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
	public function getZonesByCountryId(int $country_id): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "zone` `z` LEFT JOIN `" . DB_PREFIX . "zone_description` `zd` ON (`z`.`zone_id` = `zd`.`zone_id`) WHERE `z`.`country_id` = '" . (int)$country_id . "' AND `zd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `z`.`status` = '1' ORDER BY `zd`.`name`";

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
	 * Get Total Zones By Country ID
	 *
	 * Get the total number of zones by country records in the database.
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('localisation/zone');
	 *
	 * $zone_total = $this->model_localisation_zone->getTotalZonesByCountryId($country_id);
	 */
	public function getTotalZonesByCountryId(int $country_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "' AND `status` = '1'");

		return (int)$query->row['total'];
	}
}
