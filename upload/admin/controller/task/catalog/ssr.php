<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Static Site Renderer
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Ssr extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates the static html file for CMS and catalog
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/ssr');

		$required = [
			'store_id',
			'language',
			'route'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		unset($args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode((string)$args['language']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		// 1. Create a store instance using loader class to call controllers, models, views, libraries.
		$this->load->model('setting/store');

		$store = $this->model_setting_store->createStoreInstance($store_info['store_id'], $language_info['code']);

		// Add the args to the URL
		foreach ($args as $key => $value) {
			$store->request->get[$key] = $value;
		}

		// Make sure the SEO URL's work
		$store->load->controller('startup/rewrite');

		// 2. Call the required API controller.
		$store->load->controller($args['route']);

		// 3. Call the required API controller and get the output.
		$output = $store->response->getOutput();

		// 4. Clean up data by clearing cart.
		$store->cart->clear();

		// 5. Deleting the current session, so we are not creating infinite sessions.
		$store->session->destroy();

		// Create the directory and file names.
		$this->load->model('design/seo_url');

		// Create the directory and file names.
		$keywords = [];

		foreach ($args as $key => $value) {
			$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyValue($key, $value, $store_info['store_id'], $language_info['language_id']);

			if ($seo_url_info) {
				$keywords[] = $seo_url_info;
			}
		}

		$sort_order = [];

		foreach ($keywords as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $keywords);

		$base = DIR_OPENCART . 'shop/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/html/' . implode('/', $keywords) . '/';
		$filename = 'index.html';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, $output)) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_success')];
	}
}
