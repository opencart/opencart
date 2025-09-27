<?php
namespace Opencart\Catalog\Controller\Event;
/**
 * Class SSR
 *
 * @package Opencart\Catalog\Controller\Event
 */
class Ssr extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Creates a static page from the response from selected pages.
	 *
	 * Triggers
	 *
	 * catalog/controller/common/home/after
	 * catalog/controller/cms/article/after
	 * catalog/controller/cms/topic/after
	 * catalog/controller/product/category/after
	 * catalog/controller/product/compare/after
	 * catalog/controller/product/manufacturer/after
	 * catalog/controller/product/product/after
	 * catalog/controller/product/special/after
	 * catalog/controller/product/search/after
	 * catalog/controller/information/contact/after
	 * catalog/controller/information/information/after
	 * catalog/controller/information/sitemap/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param string            $code
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		// 1. Get the main response
		$output = $this->response->getOutput();

		$keywords = [];

		// Create the directory and file names.
		$this->load->model('design/seo_url');

		foreach ($this->request->get as $key => $value) {
			$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyValue($key, $value, $this->config->get('config_store_id'), $this->config->get('config_language_id'));

			if ($seo_url_info) {
				$keywords[] = $seo_url_info;
			}
		}

		$sort_order = [];

		foreach ($keywords as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $keywords);

		// Create the directory and file names.
		$base = DIR_OPENCART . 'shop/';
		$directory = parse_url($this->config->get('config_url'), PHP_URL_HOST) . '/' . implode('/', array_column($keywords, 'keyword')) . '/';
		$filename = 'index.html';

		if (!oc_directory_create($base . $directory, 0777)) {

		}

		if (!file_put_contents($base . $directory . $filename, $output)) {

		}

	}
}
