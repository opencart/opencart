<?php
namespace Opencart\Catalog\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	static array $languages = [];

	public function index(): void {
		if (isset($this->request->get['language'])) {
			$code = (string)$this->request->get['language'];
		} else {
			$code = $this->config->get('config_language');
		}

		$this->load->model('localisation/language');

		self::$languages = $this->model_localisation_language->getLanguages();

		if (isset(self::$languages[$code])) {
			$language_info = self::$languages[$code];

			// If extension switch add language directory
			if ($language_info['extension']) {
				$this->language->addPath('extension/' . $language_info['extension'], DIR_EXTENSION . $language_info['extension'] . '/catalog/language/');
			}

			// Set the config language_id key
			$this->config->set('config_language_id', $language_info['language_id']);
			$this->config->set('config_language', $language_info['code']);

			$this->load->language('default');
		} else {
			$url_data = $this->request->get;

			if (isset($url_data['route'])) {
				$route = $url_data['route'];
			} else {
				$route = $this->config->get('action_default');
			}

			unset($url_data['route']);
			unset($url_data['language']);

			$url = '';

			if ($url_data) {
				$url .= '&' . urldecode(http_build_query($url_data));
			}

			// If no language can be found, we use the default one
			$this->response->redirect($this->url->link($route, 'language=' . $this->config->get('config_language') . $url, true));
		}
	}
	
	// Fill the language up with default values
	public function after(&$route, &$prefix, &$code, &$output): void {
		if ($code) {
			$language = $code;
		} else {
			$language = $this->config->get('config_language');
		}

		if (isset(self::$languages[$language])) {
			$path = '';

			$language_info = self::$languages[$language];

			if ($language_info['extension']) {
				$extension = 'extension/' . $language_info['extension'];

				if (oc_substr($route, 0, strlen($extension)) != $extension) {
					$path = $extension . '/';
				}
			}

			// Use load->language so it's not triggering infinite loops
			$this->language->load($path . $route, $prefix, $language);
		}
	}
}
