<?php
namespace Opencart\Catalog\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$language_codes = array_column($languages, 'language_id', 'code');

		$code = '';

		if (isset($this->request->get['language'])) {
			$code = $this->request->get['language'];
		}

		// Language not available then use default
		if (!array_key_exists($code, $language_codes)) {
			$code = $this->config->get('config_language');
		}

		// Set the config language_id
		$this->config->set('config_language_id', $language_codes[$code]);
		$this->config->set('config_language', $code);

		// Language
		$language = new \Opencart\System\Library\Language($code);
		$language->addPath(DIR_LANGUAGE);
		$language->load($code);

		$this->registry->set('language', $language);
	}
}