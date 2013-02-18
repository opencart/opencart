<?php
class ModelSettingLocation extends Model {
	public function addLocation($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "location SET name = '" . $this->db->escape($data['name']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', times = '" . $this->db->escape($data['times']) . "', comment = '" . $this->db->escape($data['comment']) . "',  sort_order = '" . $this->db->escape($data['sort_order']) . "', geocode = '" . $this->db->escape($data['geocode']) . "', status = '" . (int)$data['status'] . "'");
		
		if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "location SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE location_id = '" . (int)$location_id . "'");
        }
        
		$this->cache->delete('location');
  	}
	
	public function editLocation($location_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "location SET name = '" . $this->db->escape($data['name']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "',  times = '" . $this->db->escape($data['times']) . "', comment = '" . $this->db->escape($data['comment']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "' WHERE location_id = '" . (int)$location_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "location SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE location_id = '" . (int)$location_id . "'");
        }
        				
		$this->cache->delete('location');
	}
	
	public function deleteLocation($location_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "location WHERE location_id = " . (int)$location_id);
	}
	
	public function getLocation($location_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");
	
		return $query->row;
	}

	public function getLocations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "location";
	
			$sort_data = array(
			    'location_id',
				'name',
				'address_1',
				'sort_order',
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sort_order, name";	
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
			$location_data = $this->cache->get('location');
		
			if (!$location_data) {
				$location_data = array();
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "location ORDER BY sort_order, name");
	
    			foreach ($query->rows as $result) {
      				$location_data[$result['location_id']] = array(
        				'location_id' => $result['location_id'],
        				'name'        => $result['name'],
        				'image'       => $result['image'],
        				'address_1'   => $result['address_1'],
						'address_2'   => $result['address_2'],
						'city'        => $result['city'],
						'postcode'    => $result['postcode'],
                        'times'       => $result['times'],
                        'comment'     => $result['comment'], 						
						'geocode'     => $result['geocode'],
						'sort_order'  => $result['sort_order']
      				);
    			}	
			
				$this->cache->set('location', $location_data);
			}
		
			return $location_data;			
		}
	}

    //  Get Store Images
    public function getStoreImage($location_id) {
        $query = $this->db->query("SELECT image FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");
        
        return $query->rows;
    }

	public function getTotalLocation() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "location");
		
		return $query->row['total'];
	}
}
?>