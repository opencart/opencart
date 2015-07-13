<?php
class ModelCustomerCustomField extends Model {
	public function addCustomField($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field` SET type = '" . $this->db->escape($data['type']) . "', value = '" . $this->db->escape($data['value']) . "', location = '" . $this->db->escape($data['location']) . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$custom_field_id = $this->db->getLastId();

		foreach ($data['custom_field_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_description SET custom_field_id = '" . (int)$custom_field_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		if (isset($data['custom_field_customer_group'])) {
			foreach ($data['custom_field_customer_group'] as $custom_field_customer_group) {
				if (isset($custom_field_customer_group['customer_group_id'])) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_customer_group SET custom_field_id = '" . (int)$custom_field_id . "', customer_group_id = '" . (int)$custom_field_customer_group['customer_group_id'] . "', required = '" . (int)(isset($custom_field_customer_group['required']) ? 1 : 0) . "'");
				}
			}
		}

		if (isset($data['custom_field_value'])) {
			foreach ($data['custom_field_value'] as $custom_field_value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_value SET custom_field_id = '" . (int)$custom_field_id . "', sort_order = '" . (int)$custom_field_value['sort_order'] . "'");

				$custom_field_value_id = $this->db->getLastId();

				foreach ($custom_field_value['custom_field_value_description'] as $language_id => $custom_field_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_value_description SET custom_field_value_id = '" . (int)$custom_field_value_id . "', language_id = '" . (int)$language_id . "', custom_field_id = '" . (int)$custom_field_id . "', name = '" . $this->db->escape($custom_field_value_description['name']) . "'");
				}
			}
		}
	}

	public function editCustomField($custom_field_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "custom_field` SET type = '" . $this->db->escape($data['type']) . "', value = '" . $this->db->escape($data['value']) . "', location = '" . $this->db->escape($data['location']) . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE custom_field_id = '" . (int)$custom_field_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "custom_field_description WHERE custom_field_id = '" . (int)$custom_field_id . "'");

		foreach ($data['custom_field_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_description SET custom_field_id = '" . (int)$custom_field_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "custom_field_customer_group WHERE custom_field_id = '" . (int)$custom_field_id . "'");

		if (isset($data['custom_field_customer_group'])) {
			foreach ($data['custom_field_customer_group'] as $custom_field_customer_group) {
				if (isset($custom_field_customer_group['customer_group_id'])) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_customer_group SET custom_field_id = '" . (int)$custom_field_id . "', customer_group_id = '" . (int)$custom_field_customer_group['customer_group_id'] . "', required = '" . (int)(isset($custom_field_customer_group['required']) ? 1 : 0) . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "custom_field_value WHERE custom_field_id = '" . (int)$custom_field_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "custom_field_value_description WHERE custom_field_id = '" . (int)$custom_field_id . "'");

		if (isset($data['custom_field_value'])) {
			foreach ($data['custom_field_value'] as $custom_field_value) {
				if ($custom_field_value['custom_field_value_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_value SET custom_field_value_id = '" . (int)$custom_field_value['custom_field_value_id'] . "', custom_field_id = '" . (int)$custom_field_id . "', sort_order = '" . (int)$custom_field_value['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_value SET custom_field_id = '" . (int)$custom_field_id . "', sort_order = '" . (int)$custom_field_value['sort_order'] . "'");
				}

				$custom_field_value_id = $this->db->getLastId();

				foreach ($custom_field_value['custom_field_value_description'] as $language_id => $custom_field_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_value_description SET custom_field_value_id = '" . (int)$custom_field_value_id . "', language_id = '" . (int)$language_id . "', custom_field_id = '" . (int)$custom_field_id . "', name = '" . $this->db->escape($custom_field_value_description['name']) . "'");
				}
			}
		}
	}

	public function deleteCustomField($custom_field_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field` WHERE custom_field_id = '" . (int)$custom_field_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_description` WHERE custom_field_id = '" . (int)$custom_field_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_customer_group` WHERE custom_field_id = '" . (int)$custom_field_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_value` WHERE custom_field_id = '" . (int)$custom_field_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_value_description` WHERE custom_field_id = '" . (int)$custom_field_id . "'");
	}

	public function getCustomField($custom_field_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN " . DB_PREFIX . "custom_field_description cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cf.custom_field_id = '" . (int)$custom_field_id . "' AND cfd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCustomFields($data = array()) {
		if (empty($data['filter_customer_group_id'])) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN " . DB_PREFIX . "custom_field_description cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cfd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		} else {
			$sql = "SELECT * FROM " . DB_PREFIX . "custom_field_customer_group cfcg LEFT JOIN `" . DB_PREFIX . "custom_field` cf ON (cfcg.custom_field_id = cf.custom_field_id) LEFT JOIN " . DB_PREFIX . "custom_field_description cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cfd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND cfd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND cfcg.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		$sort_data = array(
			'cfd.name',
			'cf.type',
			'cf.location',
			'cf.status',
			'cf.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cfd.name";
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
	}

	public function getCustomFieldDescriptions($custom_field_id) {
		$custom_field_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_description WHERE custom_field_id = '" . (int)$custom_field_id . "'");

		foreach ($query->rows as $result) {
			$custom_field_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $custom_field_data;
	}
	
	public function getCustomFieldValue($custom_field_value_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value cfv LEFT JOIN " . DB_PREFIX . "custom_field_value_description cfvd ON (cfv.custom_field_value_id = cfvd.custom_field_value_id) WHERE cfv.custom_field_value_id = '" . (int)$custom_field_value_id . "' AND cfvd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	public function getCustomFieldValues($custom_field_id) {
		$custom_field_value_data = array();

		$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value cfv LEFT JOIN " . DB_PREFIX . "custom_field_value_description cfvd ON (cfv.custom_field_value_id = cfvd.custom_field_value_id) WHERE cfv.custom_field_id = '" . (int)$custom_field_id . "' AND cfvd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cfv.sort_order ASC");

		foreach ($custom_field_value_query->rows as $custom_field_value) {
			$custom_field_value_data[$custom_field_value['custom_field_value_id']] = array(
				'custom_field_value_id' => $custom_field_value['custom_field_value_id'],
				'name'                  => $custom_field_value['name']
			);
		}

		return $custom_field_value_data;
	}
	
	public function getCustomFieldCustomerGroups($custom_field_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_customer_group` WHERE custom_field_id = '" . (int)$custom_field_id . "'");

		return $query->rows;
	}

	public function getCustomFieldValueDescriptions($custom_field_id) {
		$custom_field_value_data = array();

		$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value WHERE custom_field_id = '" . (int)$custom_field_id . "'");

		foreach ($custom_field_value_query->rows as $custom_field_value) {
			$custom_field_value_description_data = array();

			$custom_field_value_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE custom_field_value_id = '" . (int)$custom_field_value['custom_field_value_id'] . "'");

			foreach ($custom_field_value_description_query->rows as $custom_field_value_description) {
				$custom_field_value_description_data[$custom_field_value_description['language_id']] = array('name' => $custom_field_value_description['name']);
			}

			$custom_field_value_data[] = array(
				'custom_field_value_id'          => $custom_field_value['custom_field_value_id'],
				'custom_field_value_description' => $custom_field_value_description_data,
				'sort_order'                     => $custom_field_value['sort_order']
			);
		}

		return $custom_field_value_data;
	}

	public function getTotalCustomFields() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "custom_field`");

		return $query->row['total'];
	}
}