<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Tax Class
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class TaxClass extends \Opencart\System\Engine\Controller {
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
		$this->load->language('task/catalog/tax_class');

		if (!array_key_exists('tax_class_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('localisation/tax_class');

		$tax_class_info = $this->model_localisation_tax_class->getTaxClass((int)$args['tax_class_id']);

		if (!$tax_class_info) {
			return ['error' => $this->language->get('error_tax_class')];
		}

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'tax_class.info.' . $tax_class_info['tax_class_id'],
				'action' => 'task/catalog/tax_class.info',
				'args'   => [
					'tax_class_id' => $tax_class_info['tax_class_id'],
					'store_id'     => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_info'), $tax_class_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generate tax class information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/tax_class');

		if (!array_key_exists('tax_class_id', $args)) {
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

		// Tax Class
		$this->load->model('localisation/tax_class');

		$tax_class_info = $this->model_localisation_tax_class->getTaxClass((int)$args['tax_class_id']);

		if (!$tax_class_info) {
			return ['error' => $this->language->get('error_tax_class')];
		}

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
		$filename = 'tax_class-' . $tax_class_info['tax_class_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($tax_class_info))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $tax_class_info['title'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON country files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/tax_class');

		if (!array_key_exists('tax_class_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('localisation/tax_class');

		$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($args['tax_class_id']);

		if (!$geo_zone_info) {
			return ['error' => $this->language->get('error_geo_zone')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/localisation/tax_rate-' . $geo_zone_info['geo_zone_id'] . '.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $geo_zone_info['name'])];
	}
}
