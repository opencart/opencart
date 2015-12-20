<?php
class ControllerActionLanguage extends Controller {
	public function index() {
		// Language
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $db->escape($config->get('config_admin_language')) . "'");
		
		if ($query->num_rows) {
			$this->config->set('config_language_id', $query->row['language_id']);
		} else {
			exit();
		}
		
		// Language
		$language = new Language($config->get('config_admin_language'));
		$language->load($config->get('config_admin_language'));
		$this->registry->set('language', $language);	
	}
}
