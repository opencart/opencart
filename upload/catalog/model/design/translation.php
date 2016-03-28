<?php
class ModelDesignTranslation extends Model {
	public function getTranslations($route) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "translation WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' AND route = '" . $this->db->escape($route) . "'");

		return $query->rows;
	}
}