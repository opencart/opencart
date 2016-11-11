<?php
class ModelExtensionTranslation extends Model {
	public function addTranslation($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "crowdin` SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', progress = '" . $this->db->escape($data['progress']) . "'");
	}
	
	public function clear() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "crowdin`");
	}
	
	public function getTranslation($translation_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "crowdin` WHERE crowdin_id = '" . (int)$translation_id . "'");

		return $query->row;
	}
	
	public function getTranslations($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "crowdin` ORDER BY name ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTranslationByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "crowdin` WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getTotalTranslations() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "crowdin`");

		return $query->row['total'];
	}
}
