<?php
class ControllerUpgrade2001 extends Controller {
	public function index() {
		// Update some language settings
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'en-gb' WHERE `key` = 'config_language' AND `value` = 'en'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'en-gb' WHERE `key` = 'config_admin_language' AND `value` = 'en'");
		
		// Update the template setting
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `key` = 'config_theme', value = 'theme_default' WHERE `key` = 'config_template'");
	}
}