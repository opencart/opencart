<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Stock Status
 *
 * Generates stock status information.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class StockStatus extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate currency data based on store.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/stock_status');

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

		$stock_status_data = [];

		$this->load->model('localisation/stock_status');

		$stock_statuses = (array)$this->model_localisation_stock_status->getStockStatuses();

		foreach ($stock_statuses as $stock_status) {
			$description_data = [];

			$descriptions = $this->model_localisation_stock_status->getDescriptions($stock_status['stock_status_id']);

			foreach ($descriptions as $code => $description) {
				$description_data[$code] = ['name' => $description['name']];
			}

			$stock_status_data[] = [
				'stock_status_id' => $stock_status['stock_status_id'],
				'description'     => $description_data
			];
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/localisation/';
		$filename = 'stock_status.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($stock_status_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}
}
