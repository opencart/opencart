<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Zone
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class Zone extends \Opencart\System\Engine\Model {
	/**
	 * @param int $zone_id
	 *
	 * @return array
	 */
	public function getZone(int $zone_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `zone_id` = '" . (int)$zone_id . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * @param int $country_id
	 *
	 * @return array
	 */
	public function getZonesByCountryId(int $country_id): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "' AND `status` = '1' ORDER BY `name`";

		$key = md5($sql);

		$zone_data = $this->cache->get('zone.' . $key);

		if (!$zone_data) {
			$query = $this->db->query($sql);

			$zone_data = $query->rows;

			$this->cache->set('zone.' . $key, $zone_data);
		}

		return $zone_data;
	}
}
