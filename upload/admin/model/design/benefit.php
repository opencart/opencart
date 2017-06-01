<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelDesignBenefit extends Model {
	public function addBenefit($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "benefit SET name = '" . $this->db->escape($data['name']) . "', link = '" . $data['link'] . "', type = '" . (int)$data['type'] . "', status = '" . (int)$data['status'] . "'");

		$benefit_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "benefit SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE benefit_id = '" . (int)$benefit_id . "'");
		}
		
		foreach ($data['benefit_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "benefit_description SET benefit_id = '" . (int)$benefit_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
	}

	public function editBenefit($benefit_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "benefit SET name = '" . $this->db->escape($data['name']) . "', link = '" . $data['link'] . "', type = '" . (int)$data['type'] . "', status = '" . (int)$data['status'] . "' WHERE benefit_id = '" . (int)$benefit_id . "'");	
	
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "benefit SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE benefit_id = '" . (int)$benefit_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "benefit_description WHERE benefit_id = '" . (int)$benefit_id . "'");
	
		foreach ($data['benefit_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "benefit_description SET benefit_id = '" . (int)$benefit_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
		}
	
	}

	public function deleteBenefit($benefit_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "benefit WHERE benefit_id = '" . (int)$benefit_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "benefit_description WHERE benefit_id = '" . (int)$benefit_id . "'");
	}

	public function getBenefit($benefit_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "benefit WHERE benefit_id = '" . (int)$benefit_id . "'");

		return $query->row;
	}

	public function getBenefits($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "benefit";

		$sort_data = array(
			'name',
			'status'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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

	public function getProductBenefit($product_id) {
		$benefits = array();

		$query = $this->db->query("SELECT benefit_id, position FROM " . DB_PREFIX . "product_to_benefit WHERE product_id = '" . (int)$product_id . "' GROUP BY position");
		
		foreach ($query->rows as $benefit) {
			$benefits[$benefit['position']] = $benefit['benefit_id'];
		}

		return $benefits;
	}

	public function getTotalBenefits() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "benefit");

		return $query->row['total'];
	}	
	
	public function validateDelete($selected) {
	
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_benefit WHERE benefit_id IN (". $selected .")");

		return $query->row['total'];
	}
	
	
	public function getBenefitDescriptions($benefit_id) {
		$benefit_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "benefit_description WHERE benefit_id = '" . (int)$benefit_id . "'");

		foreach ($query->rows as $result) {
			$benefit_description_data[$result['language_id']] = array(
				'description'  => $result['description'],
			);
		}

		return $benefit_description_data;
	}
	
}
?>