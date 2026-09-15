<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Stylesheet
 *
 * Can be loaded using $this->load->controller('task/catalog/sass');
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Stylesheet extends \Opencart\System\Engine\Controller {
	/**
	 * Stylesheet
	 *
	 * Generate catalog Stylesheet file.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/stylesheet');

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

		// Before we delete we need to make sure there is a sass file to regenerate the css
		$output = file_get_contents(HTTP_CATALOG . 'catalog/view/stylesheet/stylesheet.css');

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/stylesheet/';
		$filename = 'stylesheet.css';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, $output)) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_success'), $store_info['name'])];
	}
}
