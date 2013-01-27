<?php
class ModelAccountCustomField extends Model {
	public function getCustomFields() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN `" . DB_PREFIX . "custom_field_description` cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cfd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cf.sort_order DESC");
		
		return $query->rows;
	}
}
?>