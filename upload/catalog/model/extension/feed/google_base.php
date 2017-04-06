<?php
class ModelExtensionFeedGoogleBase extends Model {
    public function getCategories() {
		$query = $this->db->query("SELECT google_base_category_id, (SELECT name FROM `" . DB_PREFIX . "google_base_category` gbc WHERE gbc.google_base_category_id = gbc2c.google_base_category_id) AS google_base_category, category_id, (SELECT name FROM `" . DB_PREFIX . "category_description` cd WHERE cd.category_id = gbc2c.category_id AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS category FROM `" . DB_PREFIX . "google_base_category_to_category` gbc2c ORDER BY google_base_category ASC");

		return $query->rows;
    }

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "google_base_category_to_category`");

		return $query->row['total'];
    }
}
