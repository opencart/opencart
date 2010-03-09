<?php
class ModelLocalisationMeasurementClass extends Model {
	public function addMeasurementClass($data) {
		foreach ($data['measurement_class'] as $language_id => $value) {
			if (isset($measurement_class_id)) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "measurement_class SET measurement_class_id = '" . (int)$measurement_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "measurement_class SET language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
				
				$measurement_class_id = $this->db->getLastId();
			}
		}
		
		if (isset($data['measurement_rule'])) {
			foreach ($data['measurement_rule'] as  $key => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "measurement_rule SET from_id = '" . $measurement_class_id . "', to_id = '" . (int)$key . "', rule = '" . (float)$value . "'");
			}
		}
		
		$this->cache->delete('measurement_class');
	}
	
	public function editMeasurementClass($measurement_class_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "measurement_class WHERE measurement_class_id = '" . (int)$measurement_class_id . "'");

		foreach ($data['measurement_class'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "measurement_class SET measurement_class_id = '" . (int)$measurement_class_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', unit = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "measurement_rule WHERE from_id = '" . (int)$measurement_class_id . "'");
		
		if (isset($data['measurement_rule'])) {
			foreach ($data['measurement_rule'] as $key => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "measurement_rule set from_id = '" . (int)$measurement_class_id . "', to_id = '" . (int)$key . "', rule = '" . (float)$value . "'");
			}
		}
		
		$this->cache->delete('measurement_class');	
	}
	
	public function deleteMeasurementClass($measurement_class_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "measurement_class WHERE measurement_class_id = '" . (int)$measurement_class_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "measurement_rule WHERE from_id = '" . (int)$measurement_class_id . "'");	
		
		$this->cache->delete('measurement_class');
	}
	
	public function getMeasurementClasses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "measurement_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
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
			$measurement_class_data = $this->cache->get('measurement_class.' . $this->config->get('config_language_id'));

			if (!$measurement_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "measurement_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
				$measurement_class_data = $query->rows;
			
				$this->cache->set('measurement_class.' . $this->config->get('config_language_id'), $measurement_class_data);
			}
			
			return $measurement_class_data;
		}
	}
		
	public function getMeasurementClassDescriptions($measurement_class_id) {
		$measurement_class_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "measurement_class WHERE measurement_class_id = '" . (int)$measurement_class_id . "'");
				
		foreach ($query->rows as $result) {
			$measurement_class_data[$result['language_id']] = array(
				'title' => $result['title'],
				'unit'  => $result['unit']
			);
		}
		
		return $measurement_class_data;
	}
	
	public function getMeasurementRules($measurement_class_id) {
		$measurement_rule_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "measurement_rule WHERE from_id = '" . (int)$measurement_class_id . "'");

		foreach ($query->rows as $result) {
			$measurement_rule_data[$result['to_id']] = array('rule' => $result['rule']);
		}
		
		return $measurement_rule_data;
	}

	public function getMeasurementTo($measurement_class_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "measurement_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND measurement_class_id != '" . (int)$measurement_class_id . "'");
				
		return $query->rows;
	}
			
	public function getTotalMeasurementClasses() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "measurement_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}		
}
?>