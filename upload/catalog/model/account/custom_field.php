<?php
class ModelAccountCustomField extends Model {
	public function getCustomField($custom_field_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN `" . DB_PREFIX . "custom_field_description` cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cf.status = '1' AND cf.custom_field_id = '" . (int)$custom_field_id . "' AND cfd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCustomFields($data = array()) {
		$custom_field_data = array();
		
		$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN `" . DB_PREFIX . "custom_field_description` cfd ON (cf.custom_field_id = cfd.custom_field_id)");
		
		if (!empty($data['customer_group_id'])) {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "custom_field_customer_group` cfcg ON (cfcg.custom_field_id = cf.custom_field_id)";
		}
		
		$sql .= " WHERE cfd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cf.status = '1'";
		
		if (!empty($data['customer_group_id'])) {
			$sql .= " AND cfcg.customer_group_id = '" . (int)$data['customer_group_id'] . "'";
		}
		
		if (!empty($data['custom_field_id'])) {
			$sql .= " AND cf.custom_field_id = '" . (int)$data['custom_field_id'] . "'";
		}
		
		if (!empty($data['location'])) {
			if ($data['location'] != 'account') {
				$sql .= " AND cf.location = '" . $this->db->escape($data['location']) . "'";
			} else {
				if ($data['location'] == 'account' && isset($data['affiliate']) && $data['affiliate']) {
					$sql .= " AND (cf.location = 'account') OR (cf.location = 'affiliate')";
				} elseif ($data['location'] == 'account' && isset($data['address']) && $data['address']) {
					$sql .= " AND (cf.location = 'account') OR (cf.location = 'address')";
				} elseif ($data['location'] != 'account' && isset($data['affiliate']) && $data['affiliate']) {
					$sql .= " AND cf.location = 'affiliate'";
				} elseif ($data['location'] == 'account' && !isset($data['affiliate'])) {
					$sql .= " AND cf.location = 'account'";
				}
			}
		}
		
		$sort_data = array(
			'cf.name',
			'cf.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cf.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		$custom_field_query = $this->db->query($sql);

		foreach ($custom_field_query->rows as $custom_field) {
			$custom_field_value_data = array();

			if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio' || $custom_field['type'] == 'checkbox') {
				$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value cfv LEFT JOIN " . DB_PREFIX . "custom_field_value_description cfvd ON (cfv.custom_field_value_id = cfvd.custom_field_value_id) WHERE cfv.custom_field_id = '" . (int)$custom_field['custom_field_id'] . "' AND cfvd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cfv.sort_order ASC");

				foreach ($custom_field_value_query->rows as $custom_field_value) {
					$custom_field_value_data[] = array(
						'custom_field_value_id' => $custom_field_value['custom_field_value_id'],
						'name'                  => $custom_field_value['name']
					);
				}
			}

			$custom_field_data[] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $custom_field_value_data,
				'name'               => $custom_field['name'],
				'type'               => $custom_field['type'],
				'value'              => $custom_field['value'],
				'validation'         => $custom_field['validation'],
				'location'           => $custom_field['location'],
				'required'           => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true,
				'sort_order'         => $custom_field['sort_order']
			);
		}

		return $custom_field_data;
	}
}
