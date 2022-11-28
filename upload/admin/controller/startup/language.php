<?php
namespace Opencart\Admin\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	private static $extension = '';

	public function index(): void {
		if (isset($this->request->cookie['language'])) {
			$code = (string)$this->request->cookie['language'];
		} else {
			$code = $this->config->get('language_code');
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($code);

		if ($language_info) {
			// Language
			if ($language_info['extension']) {
				self::$extension = $language_info['extension'];

				$this->language->addPath('extension/' . $language_info['extension'], DIR_EXTENSION . $language_info['extension'] . '/admin/language/');
			}

			// Set the config language_id key
			$this->config->set('config_language_id', $language_info['language_id']);
			$this->config->set('config_language_admin', $language_info['code']);

			$this->language->load('default');
		}
	}

	// Fill the language up with default values
	public function after(&$route, &$prefix, &$code, &$output): void {
		if ($code) {
			$language = $code;
		} else {
			$language = $this->config->get('config_language');
		}

		// Use language->load so it's not triggering infinite loops
		if (oc_substr($route, 0, 10) != 'extension/' && self::$extension) {
			$this->load->language('extension/' . self::$extension . '/' . $route, $prefix, $language);
		}
	}
}
