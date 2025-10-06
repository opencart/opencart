<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Location
 *
 * Can be loaded using $this->load->model('localisation/location');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Location extends \Opencart\System\Engine\Model {
	/**
	 * Add Location
	 *
	 * Create a new location record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $location_data = [
	 *     'name'      => 'Location Name',
	 *     'address'   => '',
	 *     'telephone' => '1234567890',
	 *     'image'     => 'location_image',
	 *     'open'      => '',
	 *     'comment'   => ''
	 * ];
	 *
	 * $this->load->model('localisation/location');
	 *
	 * $location_id = $this->model_localisation_location->addLocation($location_data);
	 */
	public function addLocation(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "location` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `address` = '" . $this->db->escape((string)$data['address']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `open` = '" . $this->db->escape((string)$data['open']) . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "'");

		return $this->db->getLastId();
	}

	/**
	 * Edit Location
	 *
	 * Edit location record in the database.
	 *
	 * @param int                  $location_id primary key of the location record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $location_data = [
	 *     'name'      => 'Location Name',
	 *     'address'   => '',
	 *     'telephone' => '1234567890',
	 *     'image'     => 'location_image',
	 *     'open'      => '',
	 *     'comment'   => ''
	 * ];
	 *
	 * $this->load->model('localisation/location');
	 *
	 * $this->model_localisation_location->editLocation($location_id, $location_data);
	 */
	public function editLocation(int $location_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "location` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `address` = '" . $this->db->escape((string)$data['address']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `open` = '" . $this->db->escape((string)$data['open']) . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "' WHERE `location_id` = '" . (int)$location_id . "'");
	}

	/**
	 * Delete Location
	 *
	 * Delete location record in the database.
	 *
	 * @param int $location_id primary key of the location record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/location');
	 *
	 * $this->model_localisation_location->deleteLocation($location_id);
	 */
	public function deleteLocation(int $location_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "location` WHERE `location_id` = '" . (int)$location_id . "'");
	}

	/**
	 * Get Location
	 *
	 * Get the record of the location record in the database.
	 *
	 * @param int $location_id primary key of the location record
	 *
	 * @return array<string, mixed> location record that has location ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/location');
	 *
	 * $location_info = $this->model_localisation_location->getLocation($location_id);
	 */
	public function getLocation(int $location_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "location` WHERE `location_id` = '" . (int)$location_id . "'");

		return $query->row;
	}

	/**
	 * Get Locations
	 *
	 * Get the record of the location records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> location records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/location');
	 *
	 * $results = $this->model_localisation_location->getLocations($filter_data);
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
	 * Get the total number of location records in the database.
	 *
	 * @return int total number of location records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/location');
	 *
	 * $location_total = $this->model_localisation_location->getTotalLocations();
	 */
	public function getTotalLocations(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "location`");

		return (int)$query->row['total'];
	}
}
