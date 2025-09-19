<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Store
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/store');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$base = DIR_CATALOG . 'view/data/';
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/';
				$filename = 'currency.json';

				if (!oc_directory_create($base . $directory, 0777)) {
					$json['error'] = $this->language->get('error_directory', $directory);

					break;
				}

				$file = $base . $directory . $filename;

				if (!file_put_contents($file, json_encode($stores))) {
					$json['error'] = $this->language->get('error_file', $directory . $filename);

					break;
				}
			}
		}
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON generated store files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/store');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/setting/setting.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
