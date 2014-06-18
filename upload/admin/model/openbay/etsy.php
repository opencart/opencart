<?php
class ModelOpenbayEtsy extends Model{
	public function install(){
		$settings                 = array();
		$settings["etsy_token"]   = '';
		$settings["etsy_secret"]  = '';
		$settings["etsy_string1"] = '';
		$settings["etsy_string2"] = '';

		$this->model_setting_setting->editSetting('etsy', $settings);

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "etsy_setting_option` (
					`etsy_setting_option_id` INT(11) NOT NULL AUTO_INCREMENT,
					`key` VARCHAR(100) NOT NULL,
					`last_updated` DATETIME NOT NULL,
					`data` TEXT NOT NULL,
					PRIMARY KEY (`etsy_setting_option_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

	}

	public function uninstall(){

	}
}