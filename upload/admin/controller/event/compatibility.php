<?php
class ControllerEventCompatibility extends Controller {
	public function model() {

	}
	
	public function language($route) {
		
		explode();
		
		if ((substr($route, 0, 10) == 'extension/')) {
			
			//substr($route, 0, 20) == 'extension/'
			
			
			//strrchr('/')
			
			
			//analytics/
			
			if (!is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route) . '.php')) {
				$this->load->language('analytics/extension');
			}
		}
	}
}
