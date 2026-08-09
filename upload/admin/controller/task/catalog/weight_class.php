<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Weight Class
 *
 * Generates weight class information.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class WeightClass extends \Opencart\System\Engine\Controller {
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
		$this->load->language('task/catalog/weight_class');

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

		$weight_class_data = [];

		$this->load->model('localisation/weight_class');

		$weight_classes = (array)$this->model_localisation_weight_class->getWeightClasses();

		foreach ($weight_classes as $weight_class) {
			$description_data = [];

			$descriptions = $this->model_localisation_weight_class->getDescriptions($weight_class['weight_class_id']);

			foreach ($descriptions as $code => $description) {
				$description_data[$code] = ['title' => $description['title']];
			}

			$weight_class_data[] = [
				'weight_class_id' => $weight_class['weight_class_id'],
				'description'     => $description_data,
				'value'           => $weight_class['value']
			];
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/localisation/';
		$filename = 'weight_class.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($weight_class_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}
}
