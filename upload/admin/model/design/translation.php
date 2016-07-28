<?php
class ModelDesignTranslation extends Model {
	public function editTranslation($store_id, $language_id, $route, $data) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "translation`  WHERE route = '" . $this->db->escape($route) . "'");

		if (isset($data['translation'])) {
			foreach ($data['translation'] as $translation) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "translation` SET store_id = '" . (int)$translation['store_id'] . "', language_id = '" . (int)$translation['language_id'] . "', route = '" . $this->db->escape($route) . "', `key` = '" . $this->db->escape($translation['key']) . "', value = '" . $this->db->escape($translation['value']) . "'");
			}
		}
	}

	public function deleteTranslation($translation_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "translation` WHERE translation_id = '" . (int)$translation_id . "'");
	}

	public function getTranslations($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "translation`";
		
		$implode = array();
		
		if (isset($data['filter_store_id'])) {
			$implode[] = "store_id = '" . (int)$data['filter_store_id'] . "'";
		}

		if (isset($data['filter_language_id'])) {
			$implode[] = "language_id = '" . (int)$data['filter_language_id'] . "'";
		}
		
		if (isset($data['filter_route'])) {
			$implode[] = "route LIKE '" . $this->db->escape($data['filter_route']) . "'";
		}		
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$sql .= " ORDER BY date_added DESC";
				
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

	public function getTotalTranslations($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "translation`";
		
		$implode = array();
		
		if (isset($data['filter_store_id'])) {
			$implode[] = "store_id = '" . (int)$data['filter_store_id'] . "'";
		}

		if (isset($data['filter_language_id'])) {
			$implode[] = "language_id = '" . (int)$data['filter_language_id'] . "'";
		}
		
		if (isset($data['filter_route'])) {
			$implode[] = "route LIKE '" . $this->db->escape($data['filter_route']) . "'";
		}		
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}	
}