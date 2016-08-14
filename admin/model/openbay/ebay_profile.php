<?php
class ModelOpenbayEbayProfile extends Model{
	public function add($data) {
		if ($data['default'] == 1) {
			$this->clearDefault($data['type']);
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_profile` SET `name` = '" . $this->db->escape($data['name']) . "', `description` = '" . $this->db->escape($data['description']) . "', `type` = '" . (int)$data['type'] . "', `default` = '" . (int)$data['default'] . "', `data` = '" . $this->db->escape(serialize($data['data'])) . "'");

		return $this->db->getLastId();
	}

	public function edit($id, $data) {
		if ($data['default'] == 1) {
			$this->clearDefault($data['type']);
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "ebay_profile` SET `name` = '" . $this->db->escape($data['name']) . "', `description` = '" . $this->db->escape($data['description']) . "', `data` = '" . $this->db->escape(serialize($data['data'])) . "', `default` = '" . (int)$data['default'] . "' WHERE `ebay_profile_id` = '" . (int)$id . "' LIMIT 1");
	}

	public function delete($id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_profile` WHERE `ebay_profile_id` = '" . (int)$id . "' LIMIT 1");

		if ($this->db->countAffected() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get($id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_profile` WHERE `ebay_profile_id` = '" . (int)$id . "' LIMIT 1");

		if ($qry->num_rows) {
			$row                = $qry->row;
			$row['link_edit']   = $this->url->link('openbay/ebay_profile/edit', 'token=' . $this->session->data['token'] . '&ebay_profile_id=' . $row['ebay_profile_id'], 'SSL');
			$row['link_delete'] = $this->url->link('openbay/ebay_profile/delete', 'token=' . $this->session->data['token'] . '&ebay_profile_id=' . $row['ebay_profile_id'], 'SSL');
			$row['data']        = unserialize($row['data']);

			return $row;
		} else {
			return false;
		}
	}

	public function getAll($type = '') {
		$sql = "SELECT * FROM `" . DB_PREFIX . "ebay_profile`";

		if($type !== '') {
			$sql .= " WHERE `type` = '" . (int)$type . "'";
		}

		$qry = $this->db->query($sql);

		if($qry->num_rows) {
			$profiles = array();
			foreach ($qry->rows as $row) {
				$row['link_edit']   = $this->url->link('openbay/ebay_profile/edit', 'token=' . $this->session->data['token'] . '&ebay_profile_id=' . $row['ebay_profile_id'], 'SSL');
				$row['link_delete'] = $this->url->link('openbay/ebay_profile/delete', 'token=' . $this->session->data['token'] . '&ebay_profile_id=' . $row['ebay_profile_id'], 'SSL');
				$row['data']        = !empty($row['data']) ? unserialize($row['data']) : array();
				$profiles[]         = $row;
			}

			return $profiles;
		}else{
			return false;
		}
	}

	public function getTypes() {
		$types = array(
			0 => array(
				'name'          => $this->language->get('text_type_shipping'),
				'template'      => 'openbay/ebay_profile_form_shipping.tpl'
			),
			1 => array(
				'name'          => $this->language->get('text_type_returns'),
				'template'      => 'openbay/ebay_profile_form_returns.tpl'
			),
			2 => array(
				'name'          => $this->language->get('text_type_template'),
				'template'      => 'openbay/ebay_profile_form_template.tpl'
			),
			3 => array(
				'name'          => $this->language->get('text_type_general'),
				'template'      => 'openbay/ebay_profile_form_generic.tpl'
			)
		);

		return $types;
	}

	public function getDefault($type) {
		$qry = $this->db->query("SELECT `ebay_profile_id` FROM `" . DB_PREFIX . "ebay_profile` WHERE `type` = '" . (int)$type . "' AND `default` = '1'LIMIT 1");

		if ($qry->num_rows) {
			return (int)$qry->row['ebay_profile_id'];
		} else {
			return false;
		}
	}

	private function clearDefault($type) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ebay_profile` SET `default` = '0' WHERE `type` = '" . (int)$type . "'");
	}
}