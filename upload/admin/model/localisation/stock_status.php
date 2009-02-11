<?php 
class ModelLocalisationStockStatus extends Model {
	public function addStockStatus($data) {
		foreach ($data['stock_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO stock_status SET stock_status_id = '" . (int)@$stock_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			
			$stock_status_id = $this->db->getLastId();
		}
		
		$this->cache->delete('stock_status');
	}

	public function editStockStatus($stock_status_id, $data) {
		$this->db->query("DELETE FROM stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		foreach ($data['stock_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO stock_status SET stock_status_id = '" . (int)$stock_status_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
				
		$this->cache->delete('stock_status');
	}
	
	public function deleteStockStatus($stock_status_id) {
		$this->db->query("DELETE FROM stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "'");
	
		$this->cache->delete('stock_status');
	}
		
	public function getStockStatus($stock_status_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "' AND language_id = '" . (int)$this->language->getId() . "'");
		
		return $query->row;
	}
	
	public function getStockStatuses($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM stock_status WHERE language_id = '" . (int)$this->language->getId() . "'";
      		
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
		} else {
			$stock_status = $this->cache->get('stock_status.' . $this->language->getId());
		
			if (!$stock_status) {
				$query = $this->db->query("SELECT stock_status_id, name FROM stock_status WHERE language_id = '" . (int)$this->language->getId() . "' ORDER BY name");
	
				$stock_status = $query->rows;
			
				$this->cache->set('stock_status.' . $this->language->getId(), $stock_status);
			}	
	
			return $stock_status;			
		}
	}
	
	public function getStockStatusDescriptions($stock_status_id) {
		$stock_status_data = array();
		
		$query = $this->db->query("SELECT * FROM stock_status WHERE stock_status_id = '" . (int)$stock_status_id . "'");
		
		foreach ($query->rows as $result) {
			$stock_status_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $stock_status_data;
	}
	
	public function getTotalStockStatuses() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM stock_status WHERE language_id = '" . (int)$this->language->getId() . "'");
		
		return $query->row['total'];
	}	
}
?>