<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Sass
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Sass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate SASS file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 *
	 * @throws \Exception\ScssPhp\ScssPhp\Exception\SassException
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/sass');

		$file = DIR_CATALOG . 'view/stylesheet/stylesheet.scss';

		if (!is_file($file)) {
			return ['error' => $this->language->get('error_file')];
		}

		$filename = basename($file, '.scss');

		$stylesheet = dirname($file) . '/' . $filename . '.css';

		$scss = new \ScssPhp\ScssPhp\Compiler();
		$scss->setImportPaths(DIR_CATALOG . 'view/stylesheet/');

		$output = $scss->compileString('@import "' . $filename . '.scss"')->getCss();

		$handle = fopen($stylesheet, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, $output);

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * Clear
	 *
	 * Delete generated SASS file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/sass');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$file = DIR_OPENCART . 'shop/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/data/sass.css';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
