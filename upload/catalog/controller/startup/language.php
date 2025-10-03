<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Language
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * @var array<string, array<string, mixed>>
	 */
	private static array $languages = [];

	/**
	 * Index
	 *
	 * @return ?\Opencart\System\Engine\Action
	 */
	public function index(): void {
		// Languages
		$this->load->model('localisation/language');

		self::$languages = $this->model_localisation_language->getLanguages();

		$code = '';

		if (isset($this->request->get['language'])) {
			$code = $this->request->get['language'];
		}

		// If SEO URL then the first path has to be language code
		if (isset($this->request->get['_route_']) && preg_match('/^([a-z]{2}-[a-z]{2})/', $this->request->get['_route_'], $matches)) {
			$code = $matches[0];
		}

		if (!$code) {
			$code = $this->config->get('config_language_catalog');
		}

		// Use default language if on homepage and no language code set
		if (!isset(self::$languages[$code])) {
			$code = $this->config->get('config_language_catalog');
		}

		// If extension switch add language directory
		if (self::$languages[$code]['extension']) {
			$this->language->addPath('extension/' . self::$languages[$code]['extension'], DIR_EXTENSION . self::$languages[$code]['extension'] . '/catalog/language/');
		}

		// Set the config language_id key
		$this->config->set('config_language_id', self::$languages[$code]['language_id']);
		$this->config->set('config_language', self::$languages[$code]['code']);

		$this->load->language('default');
	}

	/**
	 * After
	 *
	 * Override the language default values
	 *
	 * @param string       $route
	 * @param string       $prefix
	 * @param string       $code
	 * @param array<mixed> $output
	 *
	 * @return void
	 */
	public function after(&$route, &$prefix, &$code, &$output): void {
		if (!$code) {
			$code = $this->config->get('config_language');
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
