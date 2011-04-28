<?php 
class ModelSaleVoucherType extends Model {
	public function addVoucherType($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_type SET image = '" . $this->db->escape($data['image']) . "'");
		
		$voucher_type_id = $this->db->getLastId();
		
		foreach ($data['voucher_type_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_type SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->cache->delete('voucher_type');
	}

	public function editVoucherType($voucher_type_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_type WHERE voucher_type_id = '" . (int)$voucher_type_id . "', image = '" . $this->db->escape($data['image']) . "'");

		foreach ($data['voucher_type_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_type SET voucher_type_id = '" . (int)$voucher_type_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
				
		$this->cache->delete('voucher_type');
	}
	
	public function deleteVoucherType($voucher_type_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_type WHERE voucher_type_id = '" . (int)$voucher_type_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_type_description WHERE voucher_type_id = '" . (int)$voucher_type_id . "'");
	
		$this->cache->delete('voucher_type');
	}
		
	public function getVoucherType($voucher_type_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_type WHERE voucher_type_id = '" . (int)$voucher_type_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
		
	public function getVoucherTypes($data = array()) {
      	if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "voucher_type_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name";	
			
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
			$voucher_type_data = $this->cache->get('voucher_type.' . $this->config->get('config_language_id'));
		
			if (!$voucher_type_data) {
				$query = $this->db->query("SELECT voucher_type_id, name FROM " . DB_PREFIX . "voucher_type WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");
	
				$voucher_type_data = $query->rows;
			
				$this->cache->set('voucher_type.' . $this->config->get('config_language_id'), $voucher_type_data);
			}	
	
			return $voucher_type_data;				
		}
	}
	
	public function getVoucherTypeDescriptions($voucher_type_id) {
		$voucher_type_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_type_description WHERE voucher_type_id = '" . (int)$voucher_type_id . "'");
		
		foreach ($query->rows as $result) {
			$voucher_type_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $voucher_type_data;
	}
	
	public function getTotalVoucherTypes() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "voucher_type");
		
		return $query->row['total'];
	}	
}
?>