<?php
class ModelLocalisationLocation extends Model {
	public function addLocation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "location SET name = '" . $this->db->escape((string)$data['name']) . "', address = '" . $this->db->escape((string)$data['address']) . "', geocode = '" . $this->db->escape((string)$data['geocode']) . "', telephone = '" . $this->db->escape((string)$data['telephone']) . "', fax = '" . $this->db->escape((string)$data['fax']) . "', image = '" . $this->db->escape((string)$data['image']) . "', open = '" . $this->db->escape((string)$data['open']) . "', comment = '" . $this->db->escape((string)$data['comment']) . "'");
	
		return $this->db->getLastId();
	}

	public function editLocation($location_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "location SET name = '" . $this->db->escape((string)$data['name']) . "', address = '" . $this->db->escape((string)$data['address']) . "', geocode = '" . $this->db->escape((string)$data['geocode']) . "', telephone = '" . $this->db->escape((string)$data['telephone']) . "', fax = '" . $this->db->escape((string)$data['fax']) . "', image = '" . $this->db->escape((string)$data['image']) . "', open = '" . $this->db->escape((string)$data['open']) . "', comment = '" . $this->db->escape((string)$data['comment']) . "' WHERE location_id = '" . (int)$location_id . "'");
	}

	public function deleteLocation($location_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "location WHERE location_id = " . (int)$location_id);
	}

	public function getLocation($location_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

		return $query->row;
	}

	public function getLocations($data = array()) {
		$sql = "SELECT location_id, name, address FROM " . DB_PREFIX . "location";

		$sort_data = array(
			'name',
			'address',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getTotalLocations() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "location");

		return $query->row['total'];
	}
}
