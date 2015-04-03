<?php
class ModelAccountAddress extends Model {
	public function addAddress($data) {
		$this->event->trigger('pre.customer.add.address', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$this->customer->getId() . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "'");

		$address_id = $this->db->getLastId();

		if (!empty($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}

		$this->event->trigger('post.customer.add.address', $address_id);

		return $address_id;
	}

	public function editAddress($address_id, $data) {
		$this->event->trigger('pre.customer.edit.address', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE address_id  = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		if (!empty($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}

		$this->event->trigger('post.customer.edit.address', $address_id);
	}

	public function deleteAddress($address_id) {
		$this->event->trigger('pre.customer.delete.address', $address_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		$this->event->trigger('post.customer.delete.address', $address_id);
	}

	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		if ($address_query->num_rows) {
			return $this->buildAddress($address_query->row);
		} else {
			return false;
		}
	}

	public function getAddresses() {
		$address_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		foreach ($query->rows as $result) {
			$address_data[$result['address_id']] = $this->buildAddress($result);
		}

		return $address_data;
	}

	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
	
	private function buildAddress($result) {
		$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");

		if ($country_query->num_rows) {
			$country = $country_query->row['name'];
			$iso_code_2 = $country_query->row['iso_code_2'];
			$iso_code_3 = $country_query->row['iso_code_3'];
			$address_format = $country_query->row['address_format'];
		} else {
			$country = '';
			$iso_code_2 = '';
			$iso_code_3 = '';
			$address_format = '';
		}

		$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

		if ($zone_query->num_rows) {
			$zone = $zone_query->row['name'];
			$zone_code = $zone_query->row['code'];
		} else {
			$zone = '';
			$zone_code = '';
		}

		return array(
			'address_id'     => $result['address_id'],
			'firstname'      => $result['firstname'],
			'lastname'       => $result['lastname'],
			'company'        => $result['company'],
			'address_1'      => $result['address_1'],
			'address_2'      => $result['address_2'],
			'postcode'       => $result['postcode'],
			'city'           => $result['city'],
			'zone_id'        => $result['zone_id'],
			'zone'           => $zone,
			'zone_code'      => $zone_code,
			'country_id'     => $result['country_id'],
			'country'        => $country,
			'iso_code_2'     => $iso_code_2,
			'iso_code_3'     => $iso_code_3,
			'address_format' => $address_format,
			'custom_field'   => unserialize($result['custom_field'])
		);
	}
}
