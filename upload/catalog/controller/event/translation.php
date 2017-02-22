<?php
class ControllerEventTranslation extends Controller {
	public function index($route) {
		$this->load->model('design/translation');
			
		$results = $this->model_design_translation->getTranslations($route);
		
		foreach ($results as $result) {
			$this->language->set($result['key'], $result['value']);
		}
	}
}
