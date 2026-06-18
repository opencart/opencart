<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Language
 *
 * Generates language information.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate language data based on store.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/language');

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$language_data = [];

		$this->load->model('setting/setting');
		$this->load->model('localisation/language');

		$languages = (array)$this->model_setting_setting->getValue('config_language_list', $store_info['store_id']);

		foreach ($languages as $code) {
			$language_info = $this->model_localisation_language->getLanguageByCode((string)$code);

			if ($language_info && $language_info['status']) {
				$language_data[$language_info['code']] = [
					'name '     => $language_info['name'],
					'code'      => $language_info['code'],
					'locale'    => $language_info['locale'],
					'extension' => $language_info['extension']
				];
			}
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/localisation/';
		$filename = 'language.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($language_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_task')];
	}
}