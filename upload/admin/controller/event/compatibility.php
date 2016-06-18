<?php
class ControllerEventCompatibility extends Controller {
	public function model() {

	}
	
	public function language($route) {
		if ($route == 'extension/analytics/') {
			$this->load->language('');
		}
	}
}
