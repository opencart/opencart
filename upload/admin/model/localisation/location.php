<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Location
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Location extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addLocation(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "location` SET `name` = '" . $this->db->escape((string)$data['name']) . "', address = '" . $this->db->escape((string)$data['address']) . "', `geocode` = '" . $this->db->escape((string)$data['geocode']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `open` = '" . $this->db->escape((string)$data['open']) . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "'");

		return $this->db->getLastId();
	}

	/**
	 * @param int   $location_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editLocation(int $location_id, array $data): void {
			$this->db->query("UPDATE `" . DB_PREFIX . "location` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `address` = '" . $this->db->escape((string)$data['address']) . "', `geocode` = '" . $this->db->escape((string)$data['geocode']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `open` = '" . $this->db->escape((string)$data['open']) . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "' WHERE `location_id` = '" . (int)$location_id . "'");
	}

	/**
	 * @param int $location_id
	 *
	 * @return void
	 */
	public function deleteLocation(int $location_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "location` WHERE `location_id` = '" . (int)$location_id . "'");
	}

	/**
	 * @param int $location_id
	 *
	 * @return array
	 */
	public function getLocation(int $location_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "location` WHERE `location_id` = '" . (int)$location_id . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getLocations(array $data = []): array {
		$sql = "SELECT `location_id`, `name`, `address` FROM `" . DB_PREFIX . "location`";

		$sort_data = [
			'name',
			'address',
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * @return int
	 */
	public function getTotalLocations(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "location`");

		return (int)$query->row['total'];
	}
}
