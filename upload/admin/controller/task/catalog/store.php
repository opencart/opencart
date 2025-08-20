<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Store
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index(): void {
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
					$json['error'] = sprintf($this->language->get('error_directory'), $directory);

					break;
				}

				$file = $base . $directory . $filename;

				if (!file_put_contents($file, json_encode($currencies))) {
					$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

					break;
				}
			}
		}
	}

	public function task() {

	}

	public function clear(array $args = []): array {
		$this->load->language('task/catalog/currency');

	}
}
