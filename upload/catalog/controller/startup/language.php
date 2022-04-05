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

		// Language Detection
		if (!$code) {
			$detect = '';

			$browser_codes = [];

			if (!empty($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$browser_languages = explode(',', strtolower($this->request->server['HTTP_ACCEPT_LANGUAGE']));

				// Try using local to detect the language
				foreach ($browser_languages as $browser_language) {
					$position = strpos($browser_language, ';q=');

					if ($position !== false) {
						$browser_codes[][substr($browser_language, 0, $position)] = (float)substr($browser_language, $position + 3);
					} else {
						$browser_codes[][$browser_language] = 1.0;
					}
				}
			}

			$sort_order = [];

			foreach ($browser_codes as $key => $value) {
				$sort_order[$key] = $value[key($value)];
			}

			array_multisort($sort_order, SORT_ASC, $browser_codes);

			$browser_codes = array_reverse($browser_codes);

			foreach (array_values($browser_codes) as $browser_code) {
				foreach ($languages as $key => $value) {
					if ($value['status']) {
						$locale = explode(',', $value['locale']);

						if (in_array(key($browser_code), $locale)) {
							$detect = $value['code'];

							break 2;
						}
					}
				}
			}

			$code = ($detect) ? $detect : '';
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