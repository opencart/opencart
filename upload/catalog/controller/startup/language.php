<?php
namespace Opencart\Catalog\Controller\Startup;
use Opencart\System\Engine\Action;
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
	 * @return void
	 */
	public function index(): ?\Opencart\System\Engine\Action {
		// Languages
		$this->load->model('localisation/language');

		self::$languages = $this->model_localisation_language->getLanguages();

		$code = '';

		// Set default language
		if (!isset($this->request->get['route']) && !isset($this->request->get['language'])) {
			$code = $this->config->get('config_language_catalog');
		}

		// If GET has language var
		if (isset($this->request->get['language']) && isset(self::$languages[$this->request->get['language']])) {
			$code = $this->request->get['language'];
		}

		if ($code) {
			// If extension switch add language directory
			if (self::$languages[$code]['extension']) {
				$this->language->addPath('extension/' . self::$languages[$code]['extension'], DIR_EXTENSION . self::$languages[$code]['extension'] . '/catalog/language/');
			}

			// Set the config language_id key
			$this->config->set('config_language_id', self::$languages[$code]['language_id']);
			$this->config->set('config_language', self::$languages[$code]['code']);

			$this->load->language('default');
		} else {
			$this->config->set('config_language', $this->config->get('language_code'));

			$this->request->get['route'] = 'error/not_found';
		}

		return null;
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
