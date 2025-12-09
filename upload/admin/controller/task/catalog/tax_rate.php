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
	 * Generate tax rate task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/tax_rate');

		$this->load->model('setting/task');

		$this->load->model('localisation/geo_zone');

		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();

		foreach ($geo_zones as $geo_zone) {
			$task_data = [
				'code'   => 'tax_rate',
				'action' => 'task/catalog/tax_rate.info',
				'args'   => ['geo_zone_id' => $geo_zone['geo_zone_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * Info
	 *
	 * Generate tax rate information.
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

		$this->load->model('localisation/geo_zone');

		$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($args['geo_zone_id']);

		if (!$geo_zone_info) {
			return ['error' => $this->language->get('error_geo_zone')];
		}

		$tax_rate_data = [];

		$this->load->model('localisation/tax_rate');

		$tax_rates = $this->model_localisation_tax_rate->getTaxRatesByGeoZoneId($geo_zone_info['geo_zone_id']);

		foreach ($tax_rates as $tax_rate) {
			$customer_groups = $this->model_localisation_tax_rate->getCustomerGroups($tax_rate['tax_rate_id']);

			foreach ($customer_groups as $customer_group_id) {
				$tax_rate_data[] = $tax_rate + ['customer_group_id' => $customer_group_id];
			}
		}

		$directory = DIR_CATALOG . 'view/data/localisation/';
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
	 * Clear
	 *
	 * Delete generated JSON tax rate files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/tax_rate');

		$files = oc_directory_read( DIR_CATALOG . 'view/data/localisation/', false, '/tax_rate\-.+\.json$/');

		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}

