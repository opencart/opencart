<?php
class ModelOpenbayEtsy extends Model{
	public function install(){
		$settings                 = array();
		$settings["etsy_token"]   = '';
		$settings["etsy_secret"]  = '';
		$settings["etsy_string1"] = '';
		$settings["etsy_string2"] = '';

		$this->model_setting_setting->editSetting('etsy', $settings);

	}

	public function uninstall(){

	}
}