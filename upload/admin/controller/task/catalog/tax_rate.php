<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Tax Rate
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class TaxRate extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate tax class task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/tax_rate');

		if (!array_key_exists('store_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

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

		$this->load->model('setting/task');

		$limit = 1000;

		$this->load->model('localisation/tax_rate');

		$tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRates();

		for ($i = 1; $i <= ceil($tax_rate_total / $limit); $i++) {
			$task_data = [
				'code'   => 'tax_rate.list.' . $store_info['store_id'],
				'action' => 'task/catalog/tax_rate.list',
				'args'   => [
					'store_id' => $store_info['store_id'],
					'start'    => $i * $limit,
					'limit'    => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	/*
	 * List
	 *
	 * Generate Article data files.
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/tax_rate');

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

		$this->load->model('setting/task');

		$filter_data = [
			'start' => $args['start'],
			'limit' => $args['limit']
		];

		$this->load->model('localisation/tax_rate');

		$results = $this->model_localisation_tax_rate->getTaxRates($filter_data);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'tax_rate.info.' . $store_info['store_id'] . '.' . $result['geo_zone_id'],
				'action' => 'task/catalog/tax_rate.info',
				'args'   => [
					'geo_zone_id' => $result['geo_zone_id'],
					'store_id'    => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $args['start'], $args['limit'])];
	}

	/**
	 * Index
	 *
	 * Generate tax class task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/tax_rate');

		if (!array_key_exists('geo_zone_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$this->load->model('localisation/geo_zone');

		$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone((int)$args['geo_zone_id']);

		if (!$geo_zone_info) {
			return ['error' => $this->language->get('error_geo_zone')];
		}

		$tax_rate_data = [];

		$this->load->model('localisation/tax_rate');

		$tax_rates = $this->model_localisation_tax_rate->getTaxRatesByGeoZoneId($geo_zone_info['geo_zone_id']);

		foreach ($tax_rates as $tax_rate) {
			$customer_groups = $this->model_localisation_tax_rate->getCustomerGroups($tax_rate['tax_rate_id']);

			foreach ($customer_groups as $customer_group_id) {
				$tax_rate_data[$customer_group_id] = [
					'tax_rule_id'       => $tax_rate['tax_rule_id'],
					'tax_rate_id'       => $tax_rate['tax_rate_id'],
					'tax_class_id'      => $tax_rate['tax_class_id'],
					'name'              => $tax_rate['name'],
					'rate'              => $tax_rate['rate'],
					'type'              => $tax_rate['type'],
					'priority'          => $tax_rate['priority'],
					'geo_zone'          => $tax_rate['geo_zone'],
					'customer_group_id' => $customer_group_id
				];
			}
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
		$filename = 'tax_rate-' . $geo_zone_info['geo_zone_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($tax_rate_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $geo_zone_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON tax rate files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/tax_rate');

		if (!array_key_exists('geo_zone_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

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

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/tax_rate-' . (int)$args['geo_zone_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $store_info['name'])];
	}
}

