<?php
class ModelLocalisationWeightClass extends Model {
	public function addWeightClass($data) {
		foreach ($data['weight_class'] as $language_id => $value) {
			if (isset($weight_class_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class SET weight_class_id = '" . (int)$weight_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class SET language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
				
				$weight_class_id = $this->db->getLastId();
			}
		}
		
		if (isset($data['weight_rule'])) {
			foreach ($data['weight_rule'] as  $key => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "weight_rule SET from_id = '" . $weight_class_id . "', to_id = '" . (int)$key . "', rule = '" . (float)$value . "'");
			}
		}
		
		$this->cache->delete('weight_class');
	}
	
	public function editWeightClass($weight_class_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "weight_class WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		foreach ($data['weight_class'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class SET weight_class_id = '" . (int)$weight_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "weight_rule WHERE from_id = '" . (int)$weight_class_id . "'");
		
		if (isset($data['weight_rule'])) {
			foreach ($data['weight_rule'] as $key => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "weight_rule set from_id = '" . (int)$weight_class_id . "', to_id = '" . (int)$key . "', rule = '" . (float)$value . "'");
			}
		}
		
		$this->cache->delete('weight_class');	
	}
	
	public function deleteWeightClass($weight_class_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "weight_class WHERE weight_class_id = '" . (int)$weight_class_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "weight_rule WHERE from_id = '" . (int)$weight_class_id . "'");	
		
		$this->cache->delete('weight_class');
	}
	
	public function getWeightClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "weight_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'title',
				'unit'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY title";	
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
		} else {
			$weight_class_data = $this->cache->get('weight_class.' . $this->config->get('config_language_id'));

			if (!$weight_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				$weight_class_data = $query->rows;
			
				$this->cache->set('weight_class.' . $this->config->get('config_language_id'), $weight_class_data);
			}
			
			return $weight_class_data;
		}
	}
		
	public function getWeightClassDescriptions($weight_class_id) {
		$weight_class_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class WHERE weight_class_id = '" . (int)$weight_class_id . "'");
				
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
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_rule WHERE from_id = '" . (int)$weight_class_id . "'");

		foreach ($query->rows as $result) {
			$weight_rule_data[$result['to_id']] = array('rule' => $result['rule']);
		}
		
		return $weight_rule_data;
	}

	public function getWeightTo($weight_class_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND weight_class_id != '" . (int)$weight_class_id . "'");
				
		return $query->rows;
	}
			
	public function getTotalWeightClasses() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "weight_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}		
}
?>