<?php
class ModelAccountAddress extends Model {
	public function addAddress($data) {
      	$this->db->query("INSERT INTO address SET customer_id = '" . (int)$this->customer->getId() . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "'");
		
		$address_id = $this->db->getLastId();
		
      	if (@$data['default']) {
        	$this->db->query("UPDATE customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
      	}
		
		return $address_id;
	}
	
	public function editAddress($address_id, $data) {
		$this->db->query("UPDATE address SET company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "' WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	
      	if (@$data['default']) {
        	$this->db->query("UPDATE customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
      	}
	}
	
	public function deleteAddress($address_id) {
		$this->db->query("DELETE FROM address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	}	
	
	public function getAddress($address_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM address WHERE address_id = '" . (int)$address_id . "' and customer_id = '" . (int)$this->customer->getId() . "'");
		
		return $query->row;
	}
	
	public function getAddresses() {
		$query = $this->db->query("SELECT *, c.name AS country, z.name AS zone FROM address a LEFT JOIN country c ON (a.country_id = c.country_id) LEFT JOIN zone z ON (a.zone_id = z.zone_id) WHERE a.customer_id = '" . (int)$this->customer->getId() . "'");
	
		return $query->rows;
	}	
	
	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM address WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	
		return $query->row['total'];
	}
}
?>