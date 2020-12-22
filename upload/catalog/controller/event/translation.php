<?php
namespace Opencart\Application\Controller\Event;
class Translation extends \Opencart\System\Engine\Controller {
	public function index(&$route, &$prefix) {
		$this->load->model('design/translation');

		$results = $this->model_design_translation->getTranslations($route);

		foreach ($results as $result) {
			if (!$prefix) {
				$this->language->set($result['key'], html_entity_decode($result['value'], ENT_QUOTES, 'UTF-8'));
			} else {
				$this->language->set($prefix . '_' . $result['key'], html_entity_decode($result['value'], ENT_QUOTES, 'UTF-8'));
			}
		}	
	}
}
