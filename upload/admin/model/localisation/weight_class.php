<?php
class ModelLocalisationWeightClass extends Model {
	public function addWeightClass($data) {
		foreach ($data['weight_class'] as $language_id => $value) {
			$this->db->query("INSERT INTO weight_class SET weight_class_id = '" . (int)@$weight_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");

			$weight_class_id = $this->db->getLastId();
		}
		
		if (isset($data['weight_rule'])) {
			foreach ($data['weight_rule'] as  $key => $value) {
				$this->db->query("INSERT INTO weight_rule SET from_id = '" . $weight_class_id . "', to_id = '" . (int)$key . "', rule = '" . (float)$value . "'");
			}
		}
		
		$this->cache->delete('weight_class');
	}
	
	public function editWeightClass($weight_class_id, $data) {
		$this->db->query("DELETE FROM weight_class WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		foreach ($data['weight_class'] as $language_id => $value) {
			$this->db->query("INSERT INTO weight_class SET weight_class_id = '" . (int)$weight_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->db->query("DELETE FROM weight_rule WHERE from_id = '" . (int)$weight_class_id . "'");
		
		if (isset($data['weight_rule'])) {
			foreach ($data['weight_rule'] as $key => $value) {
				$this->db->query("INSERT INTO weight_rule set from_id = '" . (int)$weight_class_id . "', to_id = '" . (int)$key . "', rule = '" . (float)$value . "'");
			}
		}
		
		$this->cache->delete('weight_class');	
	}
	
	public function deleteWeightClass($weight_class_id) {
		$this->db->query("DELETE FROM weight_class WHERE weight_class_id = '" . (int)$weight_class_id . "'");
		$this->db->query("DELETE FROM weight_rule WHERE from_id = '" . (int)$weight_class_id . "'");	
		
		$this->cache->delete('weight_class');
	}
	
	public function getWeightClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM weight_class WHERE language_id = '" . (int)$this->language->getId() . "'";
		
			if (isset($data['sort'])) {
				$sql .= " ORDER BY " . $this->db->escape($data['sort']);	
			} else {
				$sql .= " ORDER BY title";	
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
		} else {
			$weight_class = $this->cache->get('weight_class.' . $this->language->getId());

			if (!$weight_class) {
				$query = $this->db->query("SELECT * FROM weight_class WHERE language_id = '" . (int)$this->language->getId() . "'");
	
				$weight_class = $query->rows;
			
				$this->cache->set('weight_class.' . $this->language->getId(), $weight_class);
			}
			
			return $weight_class;
		}
	}
		
	public function getWeightClassDescriptions($weight_class_id) {
		$weight_class_data = array();
		
		$query = $this->db->query("SELECT * FROM weight_class WHERE weight_class_id = '" . (int)$weight_class_id . "'");
				
		foreach ($query->rows as $result) {
			$weight_class_data[$result['language_id']] = array(
				'title' => $result['title'],
				'unit'  => $result['unit']
			);
		}
		
		return $weight_class_data;
	}
	
	public function getWeightRules($weight_class_id) {
		$weight_rule_data = array();
		
		$query = $this->db->query("SELECT * FROM weight_rule WHERE from_id = '" . (int)$weight_class_id . "'");

		foreach ($query->rows as $result) {
			$weight_rule_data[$result['to_id']] = array('rule' => $result['rule']);
		}
		
		return $weight_rule_data;
	}

	public function getWeightTo($weight_class_id) {
		$query = $this->db->query("SELECT * FROM weight_class WHERE language_id = '" . (int)$this->language->getId() . "' AND weight_class_id != '" . (int)$weight_class_id . "'");
				
		return $query->rows;
	}
			
	public function getTotalWeightClasses() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM weight_class WHERE language_id = '" . (int)$this->language->getId() . "'");
		
		return $query->row['total'];
	}		
}
?>