<?php
class ModelCustomerCustomer extends Model {
	public function addCustomer($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape(@$data['firstname']) . "', lastname = '" . $this->db->escape(@$data['lastname']) . "', email = '" . $this->db->escape(@$data['email']) . "', telephone = '" . $this->db->escape(@$data['telephone']) . "', fax = '" . $this->db->escape(@$data['fax']) . "', newsletter = '" . (int)@$data['newsletter'] . "', password = '" . $this->db->escape(md5(@$data['password'])) . "', status = '" . (int)@$data['status'] . "', date_added = NOW()");
	}
	
	public function editCustomer($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape(@$data['firstname']) . "', lastname = '" . $this->db->escape(@$data['lastname']) . "', email = '" . $this->db->escape(@$data['email']) . "', telephone = '" . $this->db->escape(@$data['telephone']) . "', fax = '" . $this->db->escape(@$data['fax']) . "', newsletter = '" . (int)@$data['newsletter'] . "', status = '" . (int)@$data['status'] . "' WHERE customer_id = '" . (int)$customer_id . "'");
	
      	if (@$data['password']) {
        	$this->db->query("UPDATE " . DB_PREFIX . "customer SET password = '" . $this->db->escape(md5(@$data['password'])) . "' WHERE customer_id = '" . (int)$customer_id . "'");
      	}
	}
	
	public function deleteCustomer($customer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
	}
	
	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
	
		return $query->row;
	}
		
	public function getCustomers($data = array()) {
		$sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "customer";

		$implode = array();
		
		if (isset($data['name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['name']) . "%'";
		}
		
		if (isset($data['status'])) {
			$implode[] = "status = '" . (int)$data['status'] . "'";
		}			
		
		if (isset($data['date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'name',
			'status',
			'date_added'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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

	public function getCustomersByNewsletter() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE newsletter = '1' ORDER BY firstname, lastname, email");
	
		return $query->rows;
	}
	
	public function getTotalCustomers($data = array()) {
      	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";
		
		$implode = array();
		
		if (isset($data['name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['name']) . "%'";
		}
		
		if (isset($data['status'])) {
			$implode[] = "status = '" . (int)$data['status'] . "'";
		}		
		
		if (isset($data['date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
	
	public function getTotalAddressesByCustomerId($customer_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalAddressesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getTotalAddressesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row['total'];
	}			
}
?>