<?php
namespace Opencart\Admin\Model\Localisation;
class Zone extends \Opencart\System\Engine\Model {
	public function addZone(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "zone` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `country_id` = '" . (int)$data['country_id'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "'");

		$this->cache->delete('zone');

		return $this->db->getLastId();
	}

	public function editZone(int $zone_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "zone` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `country_id` = '" . (int)$data['country_id'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "' WHERE `zone_id` = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');
	}

	public function deleteZone(int $zone_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "zone` WHERE `zone_id` = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');
	}

	public function getZone(int $zone_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "zone` WHERE `zone_id` = '" . (int)$zone_id . "'");

		return $query->row;
	}

	public function getZones(array $data = []): array {
		$sql = "SELECT *, z.`name`, c.`name` AS country FROM `" . DB_PREFIX . "zone` z LEFT JOIN `" . DB_PREFIX . "country` c ON (z.`country_id` = c.`country_id`)";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "z.`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_country'])) {
			$implode[] = "c.`name` LIKE '" . $this->db->escape((string)$data['filter_country'] . '%') . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "z.`code` LIKE '" . $this->db->escape((string)$data['filter_code'] . '%') . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			'c.name',
			'z.name',
			'z.code'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c.`name`";
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

	public function getZonesByCountryId(int $country_id): array {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);

		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "' AND `status` = '1' ORDER BY `name`");

			$zone_data = $query->rows;

			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}

		return $zone_data;
	}

	public function getTotalZones(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "zone` z";

		if (!empty($data['filter_country'])) {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "country` c ON (z.`country_id` = c.`country_id`)";
		}

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "z.`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_country'])) {
			$implode[] = "c.`name` LIKE '" . $this->db->escape((string)$data['filter_country'] . '%') . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "z.`code` LIKE '" . $this->db->escape((string)$data['filter_code'] . '%') . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	public function getTotalZonesByCountryId(int $country_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "'");

		return (int)$query->row['total'];
	}
}
