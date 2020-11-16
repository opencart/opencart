<?php
namespace Opencart\Application\Model\Localisation;
class Zone extends \Opencart\System\Engine\Model {
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `zone_id` = '" . (int)$zone_id . "' AND `status` = '1'");

		return $query->row;
	}

	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);

		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "' AND `status` = '1' ORDER BY `name`");

			$zone_data = $query->rows;

			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}

		return $zone_data;
	}
	
	public function getTotalZonesByName($name) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "zone` WHERE LCASE(`name`) = '" . $this->db->escape(strtolower($name)) . "'");

		return $query->row['total'];
	}
}
