<?php
class ModelDesignLanguage extends Model {
	public function editTranslation($store_id, $language_id, $route, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "translation  WHERE route = '" . $this->db->escape($route) . "'");

		if (isset($data['translation'])) {
			foreach ($data['translation'] as $translation) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "translation SET store_id = '" . (int)$translation['store_id'] . "', language_id = '" . (int)$translation['language_id'] . "', route = '" . $this->db->escape($route) . "', `key` = '" . $this->db->escape($translation['key']) . "', value = '" . $this->db->escape($translation['value']) . "'");
			}
		}
	}

	public function getTranslations($store_id, $language_id, $route) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "translation WHERE store_id = '" . (int)$store_id . "' AND language_id = '" . (int)$language_id . "' AND route = '" . $this->db->escape($route) . "'");

		return $query->rows;
	}
	
	public function getTotalTranslations($store_id, $language_id, $route) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "translation WHERE store_id = '" . (int)$store_id . "' AND language_id = '" . (int)$language_id . "' AND route = '" . $this->db->escape($route) . "'");

		return $query->row['total'];
	}
}