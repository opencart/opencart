<?php
class ModelDesignTranslation extends Model {
	public function addTranslation($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "translation` SET `store_id` = '" . (int)$data['store_id'] . "', `language_id` = '" . (int)$data['language_id'] . "', `route` = '" . $this->db->escape($data['route']) . "', `key` = '" . $this->db->escape($data['key']) . "', `value` = '" . $this->db->escape($data['value']) . "', `date_added` = NOW()");
	}
		
	public function editTranslation($translation_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "translation` SET `store_id` = '" . (int)$data['store_id'] . "', `language_id` = '" . (int)$data['language_id'] . "', `route` = '" . $this->db->escape($data['route']) . "', `key` = '" . $this->db->escape($data['key']) . "', `value` = '" . $this->db->escape($data['value']) . "' WHERE `translation_id` = '" . (int)$translation_id . "'");
	}

	public function deleteTranslation($translation_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "translation` WHERE `translation_id` = '" . (int)$translation_id . "'");
	}

	public function getTranslation($translation_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "translation` WHERE `translation_id` = '" . (int)$translation_id . "'");

		return $query->row;
	}
	
	public function getTranslations($data = array()) {
		$sql = "SELECT *, (SELECT s.name FROM `" . DB_PREFIX . "store` s WHERE s.store_id = t.store_id) AS store, (SELECT l.name FROM `" . DB_PREFIX . "language` l WHERE l.language_id = t.language_id) AS language FROM `" . DB_PREFIX . "translation` t";
		
		$sort_data = array(
			'store',
			'language',
			'route',
			'key',
			'value'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY store";
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

	public function getTotalTranslations() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "translation`");
		
		return $query->row['total'];
	}	
}
