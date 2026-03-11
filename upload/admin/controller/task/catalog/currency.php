<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Currency
 *
 * Generates currency data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Currency extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate currency task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/currency');

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'currency.' . $store_id,
				'action' => 'task/catalog/currency.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate currency list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/currency');

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

		$currency_data = [];

		$this->load->model('setting/setting');
		$this->load->model('localisation/currency');

		$currency_ids = $this->model_setting_setting->getValue('config_currency_list', $store_info['store_id']);

		foreach ($currency_ids as $currency_id) {
			$currency_info = $this->model_localisation_currency->getCurrency((int)$currency_id);

			if ($currency_info && $currency_info['status']) {
				$currency_data[$currency_info['code']] = $currency_info;
			}
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
		$filename = 'currency.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($currency_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}
}
