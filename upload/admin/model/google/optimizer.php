<?php
class ModelGoogleOptimizer extends Model {
	public function addExperiment($data) {
		$this->db->query("INSERT INTO google_optimizer SET name = '" . $this->db->escape($data['name']) . "', url = '" . $this->db->escape($data['url']) . "', control  = '" . $this->db->escape($data['control']) . "', tracking = '" . $this->db->escape($data['tracking']) . "', conversion = '" . $this->db->escape($data['conversion']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	
		$google_optimizer_id = $this->db->getLastId();
		
		if (isset($data['variation'])) {
			foreach ($data['variation'] as $variation) {
				$this->db->query("INSERT INTO google_optimizer_variation SET google_optimizer_id = '" . (int)$google_optimizer_id . "', url = '" . $this->db->escape($variation['url']) . "'");
			}
		}
	}
	
	public function editExperiment($google_optimizer_id, $data) {
		$this->db->query("UPDATE google_optimizer SET name = '" . $this->db->escape($data['name']) . "', url = '" . $this->db->escape($data['url']) . "', control = '" . $this->db->escape($data['control']) . "', tracking = '" . $this->db->escape($data['tracking']) . "', conversion = '" . $this->db->escape($data['conversion']) . "', status = '" . (int)$data['status'] . "' WHERE google_optimizer_id = '" . (int)$google_optimizer_id . "'");
		
		$this->db->query("DELETE FROM google_optimizer_variation WHERE google_optimizer_id = '" . (int)$google_optimizer_id . "'");

		if (isset($data['variation'])) {
			foreach ($data['variation'] as $variation) {
				$this->db->query("INSERT INTO google_optimizer_variation SET google_optimizer_id = '" . (int)$google_optimizer_id . "', url = '" . $this->db->escape($variation['url']) . "'");
			}
		}
	}
	
	public function deleteExperiment($google_optimizer_id) {
		$this->db->query("DELETE FROM google_optimizer WHERE google_optimizer_id = '" . (int)$google_optimizer_id . "'");
		$this->db->query("DELETE FROM google_optimizer_variation WHERE google_optimizer_id = '" . (int)$google_optimizer_id . "'");
	}
	
	public function getExperiment($google_optimizer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM google_optimizer WHERE google_optimizer_id = '" . (int)$google_optimizer_id . "'");
		
		return $query->row;
	}

	public function getExperiments($data = array()) {
		$sql = "SELECT google_optimizer_id, name, status, date_added FROM google_optimizer";																																					  
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);	
		} else {
			$sql .= " ORDER BY name";
		}
			
		if (isset($data['order'])) {
			$sql .= " " . $this->db->escape($data['order']);
		} else {
			$sql .= " ASC";
		}
			
		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}																																							  
																																							  
		$query = $this->db->query($sql);																																				
		
		return $query->rows;	
	}
	
	public function getVariations($google_optimizer_id) {
		$query = $this->db->query("SELECT * FROM google_optimizer_variation WHERE google_optimizer_id = '" . (int)$google_optimizer_id . "'");
		
		return $query->rows;
	}
	
	public function getTotalExperiments() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM google_optimizer");
		
		return $query->row['total'];
	}
}
?>