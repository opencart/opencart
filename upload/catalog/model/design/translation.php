<?php
class ModelDesignTranslation extends Model {
	public function getTranslations($route) {
		$language_code = !empty($this->session->data['language']) ? $this->session->data['language'] : $this->config->get('config_language');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "translation WHERE store_id = '" . (int)$this->config->get('config_store_id') . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "' AND (route = '" . $this->db->escape($route) . "' OR route = '" . $this->db->escape($language_code) . "')");

		return $query->rows;
	}
}
