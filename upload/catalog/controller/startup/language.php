<?php
namespace Opencart\Application\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	public function index() {
		$code = $this->config->get('language_code');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$language_codes = array_column($languages, 'language_id', 'code');

		// Language Cookie
		if (isset($this->request->cookie['language']) && array_key_exists($this->request->cookie['language'], $language_codes)) {
			$code = $this->request->cookie['language'];
		}

		// No cookie then use the language in the url
		if (!$code && isset($this->request->get['language']) && array_key_exists($this->request->get['language'], $language_codes)) {
			$code = $this->request->get['language'];
		}

		// Language Detection
		if (!$code && !empty($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$detect = '';

			$browser_codes = [];

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

		// Redirect to the new language
		if (isset($this->request->get['language']) && $this->request->get['language'] != $code) {
			if (isset($this->request->get['route'])) {
				$route = $this->request->get['route'];
			} else {
				$route = $this->config->get('action_default');
			}

			unset($this->request->get['route']);
			unset($this->request->get['language']);

			$url = '';

			if ($this->request->get) {
				$url = '&' . urldecode(http_build_query($this->request->get));
			}

			$this->response->redirect($this->url->link($route, 'language=' . $code . $url));
		}

		// Set a new language cookie if the code does not match the current one
		if (!isset($this->request->cookie['language']) || $this->request->cookie['language'] != $code) {
			$option = [
				'expires'  => time() + 60 * 60 * 24 * 30,
				'path'     => '/',
				'SameSite' => 'Lax'
			];

			setcookie('language', $code, $option);
		}

		// Language
		$language = new \Opencart\System\Library\Language($code);
		$language->addPath(DIR_LANGUAGE);
		$language->load($code);

		$this->registry->set('language', $language);

		// Set the config language_id
		if (isset($language_codes[$code])) {
			$this->config->set('config_language_id', $language_codes[$code]);
		}

		$this->config->set('config_language', $code);
	}
}