<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Location
 *
 * Generates location information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Location extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate location list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/location');

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$store_info = [
				'store_id' => 0,
				'name'     => $this->config->get('config_name'),
				'url'      => HTTP_CATALOG
			];

			if ($store_id) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore((int)$store_id);

				if (!$store_info) {
					return ['error' => $this->language->get('error_store')];
				}
			}

			// Location
			$location_data = [];

			$this->load->model('setting/setting');
			$this->load->model('localisation/location');

			$location_ids = $this->model_setting_setting->getValue('config_location_list', $store_info['store_id']);

			if ($location_ids) {
				foreach ($location_ids as $location_id) {
					$location_info = $this->model_localisation_location->getLocation($location_id);

					if ($location_info) {
						$location_data[] = [
							'location_id' => $location_info['location_id'],
							'name'        => $location_info['name'],
							'address'     => $location_info['address'],
							'telephone'   => $location_info['telephone'],
							'image'       => $location_info['image'],
							'open'        => $location_info['open'],
							'comment'     => $location_info['comment']
						];
					}
				}
			}

			$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
			$filename = 'location.yaml';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, oc_yaml_encode($location_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => $this->language->get('text_task')];
	}
}
