<?php
class ModelOpenbayEbayPatch extends Model {
	public function patch($manual = true) {
		$this->load->model('setting/setting');

		$this->openbay->ebay->updateSettings();

		return true;
	}
}