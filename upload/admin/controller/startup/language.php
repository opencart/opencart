<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Language
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * @var array<string, array<string, string>>
	 */
	private static array $languages = [];

	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		// Languages
		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			self::$languages[$result['code']] = $result;
		}
		
		$language_info = [];

		// Set default language
		if (isset(self::$languages[$this->config->get('config_language_admin')])) {
			$language_info = self::$languages[$this->config->get('config_language_admin')];
		}

		// If cookie has language stored
		if (isset($this->request->cookie['language']) && isset(self::$languages[$this->request->cookie['language']])) {
			$language_info = self::$languages[$this->request->cookie['language']];
		}

		if ($language_info) {
			if ($language_info['extension']) {
				$this->language->addPath('extension/' . $language_info['extension'], DIR_EXTENSION . $language_info['extension'] . '/admin/language/');
			}

			// Set the config language_id key
			$this->config->set('config_language_id', $language_info['language_id']);
			$this->config->set('config_language_admin', $language_info['code']);

			$this->load->language('default');
		}
	}

	/**
	 * After
	 *
	 * Fill the language up with default values
	 *
	 * @param string       $route
	 * @param string       $prefix
	 * @param string       $code
	 * @param array<mixed> $output
	 *
	 * @return void
	 */
	public function after(string &$route, string &$prefix, string &$code, array &$output): void {
		if (!$code) {
			$code = $this->config->get('config_language_admin');
		}

		// Use $this->language->load so it's not triggering infinite loops
		$this->language->load($route, $prefix, $code);

		if (isset(self::$languages[$code])) {
			$language_info = self::$languages[$code];

			$path = '';

			if ($language_info['extension']) {
				$extension = 'extension/' . $language_info['extension'];

				if (oc_substr($route, 0, strlen($extension)) != $extension) {
					$path = $extension . '/';
				}
			}

			// Use $this->language->load so it's not triggering infinite loops
			$this->language->load($path . $route, $prefix, $code);
		}
	}
}
