<?php
namespace Opencart\Admin\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$language_data = [];

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) $language_data[$result['code']] = $result;

		// Language not available then use default
		$code = $this->config->get('config_language_admin');

		if (isset($this->request->cookie['language']) && array_key_exists($this->request->cookie['language'], $language_data)) {
			$code = $this->request->cookie['language'];
		}

		// Set the config language_id
		$this->config->set('config_language_id', $language_data[$code]['language_id']);
		$this->config->set('config_language_admin', $code);

		// Language
		$language = new \Opencart\System\Library\Language($code);

		if (!$language_data[$code]['extension']) {
			$language->addPath(DIR_LANGUAGE);
		} else {
			$language->addPath(DIR_EXTENSION . $language_data[$code]['extension'] . '/admin/language/');
		}

		$language->load($code);
		
		$this->registry->set('language', $language);
	}
}