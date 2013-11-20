<?php
class ModelOpenbayEbayPatch extends Model {
	public function runPatch($manual = true) {
		$this->load->model('setting/setting');

		$this->openbay->ebay->loadSettings();

		return true;
	}
}
?>