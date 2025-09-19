<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Language
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate language list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/language');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$base = DIR_OPENCART . 'shop/';
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/data/localisation/';
				$filename = 'language.json';

				if (!oc_directory_create($base . $directory, 0777)) {
					return ['error' => $this->language->get('error_directory', $directory)];
				}

				if (!file_put_contents($base . $directory . $filename, json_encode($languages))) {
					return ['error' => $this->language->get('error_file', $directory . $filename)];
				}
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON language files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/language');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$file = DIR_OPENCART . 'shop/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/data/localisation/language.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}