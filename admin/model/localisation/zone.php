<?php
class ModelLocalisationZone extends Model {
	public function addZone($data) {
		$this->db->query("INSERT INTO zone SET name = '" . $this->db->escape(htmlentities($data['name'])) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "'");
			
		$this->cache->delete('zone');
	}
	
	public function editZone($zone_id, $data) {
		$this->db->query("UPDATE zone SET name = '" . $this->db->escape(html_entity_decode($data['name'])) . "', code = '" . $this->db->escape($data['code']) . "', country_id = '" . (int)$data['country_id'] . "' WHERE zone_id = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');
	}
	
	public function deleteZone($zone_id) {
		$this->db->query("DELETE FROM zone WHERE zone_id = '" . (int)$zone_id . "'");

		$this->cache->delete('zone');	
	}
	
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM zone WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row;
	}
	
	public function getZones($data = array()) {
		$sql = "SELECT *, z.name, c.name AS country FROM zone z LEFT JOIN country c ON (z.country_id = c.country_id)";
			
		$sort_data = array(
			'c.name',
			'z.name',
			'z.code'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY c.name";	
		}
			
		if (@$data['order'] == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
			
		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getZonesByCountryId($country_id) {
		$zone = $this->cache->get('zone.' . $country_id);
	
		if (!$zone) {
			$query = $this->db->query("SELECT * FROM zone WHERE country_id = '" . (int)$country_id . "' ORDER BY name");
	
			$zone = $query->rows;
			
			$this->cache->set('zone.' . $country_id, $zone);
		}
	
		return $zone;
	}
	
	public function getTotalZones() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM zone");
		
		return $query->row['total'];
	}
				
	public function getTotalZonesByCountryId($country_id) {
		$query = $this->db->query("SELECT count(*) AS total FROM zone WHERE country_id = '" . (int)$country_id . "'");
	
		return $query->row['total'];
	}
}
?>