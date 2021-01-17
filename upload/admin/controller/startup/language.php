<?php
namespace Opencart\Application\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	public function index() {
		// Added this code so that backup and restore doesn't show text_restore
		$code = $this->config->get('config_language_admin');

		if ($code) {
			$this->session->data['language_admin'] = $code;
		} elseif (isset($this->session->data['language_admin'])) {
			$this->config->set('config_language_admin', $this->session->data['language_admin']);
		}

		// Language
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `code` = '" . $this->db->escape($this->config->get('config_language_admin')) . "'");

		if ($query->num_rows) {
			$this->config->set('config_language_id', $query->row['language_id']);
		}

		// Language
		$language = new \Opencart\System\Library\Language($this->config->get('config_language_admin'));
		$language->addPath(DIR_LANGUAGE);
		$language->load($this->config->get('config_language_admin'));
		
		$this->registry->set('language', $language);
	}
}