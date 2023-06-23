<?php
namespace Opencart\Catalog\Model\Localisation;
class Zone extends \Opencart\System\Engine\Model {
	public function getZone(int $zone_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `zone_id` = '" . (int)$zone_id . "' AND `status` = '1'");

		return $query->row;
	}

	public function getZonesByCountryId(int $country_id): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "' AND `status` = '1' ORDER BY `name`";

		$zone_data = $this->cache->get('zone.' . md5($sql));

		if (!$zone_data) {
			$query = $this->db->query($sql);

			$zone_data = $query->rows;

			$this->cache->set('zone.' . md5($sql), $zone_data);
		}

		return $zone_data;
	}
}
