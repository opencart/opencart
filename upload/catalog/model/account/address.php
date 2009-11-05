<?php
class ModelAccountAddress extends Model {
	public function addAddress($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$this->customer->getId() . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "'");
		
		$address_id = $this->db->getLastId();
		
		if (isset($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}
		
		return $address_id;
	}
	
	public function editAddress($address_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "address SET company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "' WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	
		if (isset($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}
	}
	
	public function deleteAddress($address_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
	}	
	
	public function getAddress($address_id) {
		$query = $this->db->query("SELECT DISTINCT *, c.name AS country, z.name AS zone FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "country c ON a.country_id = c.country_id LEFT JOIN " . DB_PREFIX . "zone z ON a.zone_id = z.zone_id WHERE a.address_id = '" . (int)$address_id . "' and a.customer_id = '" . (int)$this->customer->getId() . "'");
		
		if ($query->num_rows) {
			$address_data = array(
				'firstname'      => $query->row['firstname'],
				'lastname'       => $query->row['lastname'],
				'company'        => $query->row['company'],
				'address_1'      => $query->row['address_1'],
				'address_2'      => $query->row['address_2'],
				'postcode'       => $query->row['postcode'],
				'city'           => $query->row['city'],
				'zone_id'        => $query->row['zone_id'],
				'zone'           => $query->row['zone'],
				'zone_code'      => $query->row['code'],
				'country_id'     => $query->row['country_id'],
				'country'        => $query->row['country'],	
				'iso_code_2'     => $query->row['iso_code_2'],
				'iso_code_3'     => $query->row['iso_code_3'],
				'address_format' => $query->row['address_format']
			);
			
			return $address_data;
		} else {
			return FALSE;	
		}
	}
	
	public function getAddresses() {
		$query = $this->db->query("SELECT *, c.name AS country, z.name AS zone FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "country c ON (a.country_id = c.country_id) LEFT JOIN " . DB_PREFIX . "zone z ON (a.zone_id = z.zone_id) WHERE a.customer_id = '" . (int)$this->customer->getId() . "'");
	
		return $query->rows;
	}	
	
	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	
		return $query->row['total'];
	}
}
?>