<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Location
 * 
 * @example $location_model = $this->model_localisation_location;
 *
 * Can be called from $this->load->model('localisation/location');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Location extends \Opencart\System\Engine\Model {
	/**
	 * Add Location
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 */
	public function addLocation(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "location` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `address` = '" . $this->db->escape((string)$data['address']) . "', `geocode` = '" . $this->db->escape((string)$data['geocode']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `open` = '" . $this->db->escape((string)$data['open']) . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "'");

		return $this->db->getLastId();
	}

	/**
	 * Edit Location
	 *
	 * @param int                  $location_id primary key of the location record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 */
	public function editLocation(int $location_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "location` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `address` = '" . $this->db->escape((string)$data['address']) . "', `geocode` = '" . $this->db->escape((string)$data['geocode']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `open` = '" . $this->db->escape((string)$data['open']) . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "' WHERE `location_id` = '" . (int)$location_id . "'");
	}

	/**
	 * Delete Location
	 *
	 * @param int $location_id primary key of the location record
	 *
	 * @return void
	 */
	public function deleteLocation(int $location_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "location` WHERE `location_id` = '" . (int)$location_id . "'");
	}

	/**
	 * Get Location
	 *
	 * @param int $location_id primary key of the location record
	 *
	 * @return array<string, mixed> location record that has location ID
	 */
	public function getLocation(int $location_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "location` WHERE `location_id` = '" . (int)$location_id . "'");

		return $query->row;
	}

	/**
	 * Get Locations
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> location records
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
	 * Get Total Locations
	 *
	 * @return int total number of location records
	 */
	public function getTotalLocations(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "location`");

		return (int)$query->row['total'];
	}
}
