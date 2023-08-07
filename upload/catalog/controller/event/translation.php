<?php
namespace Opencart\Catalog\Controller\Event;
/**
 * Class Translation
 *
 * @package Opencart\Catalog\Controller\Event
 */
class Translation extends \Opencart\System\Engine\Controller {
	/**
	 * @param string $route
	 * @param string $prefix
	 *
	 * @return void
	 */
	public function index(string &$route, string &$prefix): void {
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
