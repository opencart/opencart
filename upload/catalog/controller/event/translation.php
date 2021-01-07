<?php
class ControllerEventTranslation extends Controller {
	public function index(&$route, &$key) {
		$this->load->model('design/translation');
		
		$results = $this->model_design_translation->getTranslations($route);
		
		foreach ($results as $result) {
			if (!$key) {
				$this->language->set($result['key'], html_entity_decode( $result['value'], ENT_QUOTES, 'UTF-8'));
			} else {
				$this->language->get($key)->set($result['key'], html_entity_decode( $result['value'], ENT_QUOTES, 'UTF-8'));
			}
		}	
	}
}
