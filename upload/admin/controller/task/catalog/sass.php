<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class SASS
 *
 * Can be loaded using $this->load->controller('task/catalog/sass');
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Sass extends \Opencart\System\Engine\Controller {
	/**
	 * SASS Admin
	 *
	 * Generate admin SASS file.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/sass');

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
		$scss = new \ScssPhp\ScssPhp\Compiler();
		$scss->setImportPaths(DIR_CATALOG . 'view/sass/');

		$output = $scss->compileString('@import "stylesheet.scss"')->getCss();

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
