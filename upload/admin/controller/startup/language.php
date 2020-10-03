<?php
namespace Opencart\Application\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	public function index() {
		// Language
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `code` = '" . $this->db->escape($this->config->get('config_admin_language')) . "'");

		if ($query->num_rows) {
			$this->config->set('config_language_id', $query->row['language_id']);
		}

		// Language
		$language = new \Opencart\System\Library\Language($this->config->get('config_admin_language'));
		$language->addPath(DIR_LANGUAGE);
		$language->load($this->config->get('config_admin_language'));
		
		$this->registry->set('language', $language);
	}
}
