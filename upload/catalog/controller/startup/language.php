<?php
namespace Opencart\Catalog\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	public static $extension = '';

	public function index(): void {
		if (isset($this->request->get['language'])) {
			$code = (string)$this->request->get['language'];
		} else {
			$code = '';
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($code);

		if (!$language_info) {
			// If no language can be found, we use the default one
			$language_info = $this->model_localisation_language->getLanguageByCode($this->config->get('config_language'));
		}

		if ($language_info) {
			// 1. initialize new language class
			$language = new \Opencart\System\Library\Language($this->config->get('config_language'));

			// 2. if extension switch to default extension language directory
			if (!$language_info['extension']) {
				$language->addPath(DIR_LANGUAGE);
			} else {
				self::$extension = $language_info['extension'];

				$language->addPath(DIR_LANGUAGE);
				$language->addPath('extension/' . $language_info['extension'], DIR_EXTENSION . $language_info['extension'] . '/catalog/language/');
			}

			// Set the config language_id key
			$this->config->set('config_language_id', $language_info['language_id']);
			$this->config->set('config_language', $language_info['code']);

			$this->registry->set('language', $language);

			$this->load->language('default');
		}
	}
	
	// Fill the language up with default values
	public function after(&$route, &$prefix, &$code, &$output): void {
		// Use language->load so it's not triggering infinite loops
		if (oc_substr($route, 0, 10) != 'extension/' && self::$extension) {
			$this->load->language('extension/' . self::$extension . '/' . $route, $prefix, $this->config->get('config_language'));
		}
	}
}
