<?php
final class Customer {
	private $customer_id;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $address_id;
	private $address = array();
	
  	public function __construct() {
		$this->db = Registry::get('db');
		$this->request = Registry::get('request');
		$this->session = Registry::get('session');
				
		if (isset($this->session->data['customer_id'])) { 
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND status = '1'");
			
			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->address_id = $customer_query->row['address_id'];
			
				$address_query = $this->db->query("SELECT *, c.name AS country, z.name AS zone FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "country c ON a.country_id = c.country_id LEFT JOIN " . DB_PREFIX . "zone z ON a.zone_id = z.zone_id WHERE a.customer_id = '" . (int)$this->session->data['customer_id'] . "'");
						 
				foreach ($address_query->rows as $result) {
					$this->address[$result['address_id']] = array(
						'firstname'      => $result['firstname'],
						'lastname'       => $result['lastname'],
						'company'        => $result['company'],
						'address_1'      => $result['address_1'],
						'address_2'      => $result['address_2'],
						'postcode'       => $result['postcode'],
						'city'           => $result['city'],
						'country_id'     => $result['country_id'],
						'zone_id'        => $result['zone_id'],
						'iso_code_2'     => $result['iso_code_2'],
						'iso_code_3'     => $result['iso_code_3'],
						'code'           => $result['code'],
						'zone'           => $result['zone'],
						'country'        => $result['country'],	
						'address_format' => $result['address_format']
					);
				}
			
      			$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(serialize($this->session->data['cart'])) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "'");
			} else {
				$this->logout();
			}
  		}
	}
		
  	public function login($email, $password) {
		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "' AND password = '" . $this->db->escape(md5($password)) . "' AND status = '1'");
		
		if ($customer_query->num_rows) {
			$this->session->data['customer_id'] = $customer_query->row['customer_id'];	
		    
			if (($customer_query->row['cart']) && (is_string($customer_query->row['cart']))) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->data['cart'])) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}
			
			$this->customer_id = $customer_query->row['customer_id'];
			$this->firstname = $customer_query->row['firstname'];
			$this->lastname = $customer_query->row['lastname'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->fax = $customer_query->row['fax'];
			$this->newsletter = $customer_query->row['newsletter'];
			$this->address_id = $customer_query->row['address_id'];
			
			$address_query = $this->db->query("SELECT *, c.name AS country, z.name AS zone FROM " . DB_PREFIX . "address a LEFT JOIN " . DB_PREFIX . "country c ON a.country_id = c.country_id LEFT JOIN " . DB_PREFIX . "zone z ON a.zone_id = z.zone_id WHERE a.customer_id = '" . (int)$this->session->data['customer_id'] . "'");
						 
			foreach ($address_query->rows as $result) {
				$this->address[$result['address_id']] = array(
					'firstname'      => $result['firstname'],
					'lastname'       => $result['lastname'],
					'company'        => $result['company'],
					'address_1'      => $result['address_1'],
					'address_2'      => $result['address_2'],
					'postcode'       => $result['postcode'],
					'city'           => $result['city'],
					'country_id'     => $result['country_id'],
					'zone_id'        => $result['zone_id'],
					'iso_code_2'     => $result['iso_code_2'],
					'iso_code_3'     => $result['iso_code_3'],
					'code'           => $result['code'],
					'zone'           => $result['zone'],
					'country'        => $result['country'],	
					'address_format' => $result['address_format']
				);
			}			
      
	  		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
  
  	public function logout() {
		unset($this->session->data['customer_id']);

		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->address_id = '';
		$this->address = array();
  	}
  
  	public function isLogged() {
    	return $this->customer_id;
  	}

  	public function getId() {
    	return $this->customer_id;
  	}
      
  	public function getFirstName() {
		return $this->firstname;
  	}
  
  	public function getLastName() {
		return $this->lastname;
  	}
  
  	public function getEmail() {
		return $this->email;
  	}
  
  	public function getTelephone() {
		return $this->telephone;
  	}
  
  	public function getFax() {
		return $this->fax;
  	}
	
  	public function getNewsletter() {
		return $this->newsletter;	
  	}
	
  	public function getAddressId() {
		return $this->address_id;	
  	}

	public function getAddress($address_id) {
		return (isset($this->address[$address_id]) ? $this->address[$address_id] : array());
	}
	
	public function hasAddress($address_id) {
		return isset($this->address[$address_id]);	
	}
}
?>