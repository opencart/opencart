<?php
class ModelDesignTranslation extends Model {
	public function editTranslation($route, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "translation  WHERE route = '" . $this->db->escape($route) . "'");

		if (isset($data['translation'])) {
			foreach ($data['translation'] as $translation) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "translation SET store_id = '" . (int)$translation['store_id'] . "', language_id = '" . (int)$translation['language_id'] . "', route = '" . $this->db->escape($route) . "', `key` = '" . $this->db->escape($translation['key']) . "', value = '" . $this->db->escape($translation['value']) . "'");
			}
		}
	}

	public function getTranslations($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "translation";

		$sort_data = array('name');

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

	public function getTotalTranslationsByRoute($route) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "translation WHERE route = '" . $this->db->escape($route) . "'");

		return $query->row['total'];
	}
}