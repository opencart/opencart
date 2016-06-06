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

	public function getTranslationsByRoute($route) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "translation WHERE route = '" . $this->db->escape($route) . "'");

		return $query->rows;
	}

	public function getTotalTranslationsByRoute($route) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "translation WHERE route = '" . $this->db->escape($route) . "'");

		return $query->row['total'];
	}
}