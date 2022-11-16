<?php
namespace Opencart\Catalog\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$language_data = [];

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) $language_data[$result['code']] = $result;

		// If no language can be found, we use the default one
		$code = $this->config->get('config_language');

		if (isset($this->request->get['language']) && array_key_exists($this->request->get['language'], $language_data)) {
			$code = $this->request->get['language'];
		}

		// Set the config language_id key
		$this->config->set('config_language_id', $language_data[$code]['language_id']);
		$this->config->set('config_language', $code);

		// Language
		$language = new \Opencart\System\Library\Language($code);
		$language->addPath(DIR_LANGUAGE);

		if ($language_data[$code]['extension']) {
			$language->addPath('extension/' . $language_data[$code]['extension'], DIR_EXTENSION . $language_data[$code]['extension'] . '/catalog/language/');
		}

		$language->load('default');

		$this->registry->set('language', $language);
	}
}
