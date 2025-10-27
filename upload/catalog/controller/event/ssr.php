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
		if (!isset($this->request->get['_route_'])) {
			return;
		}

		$output = $this->response->getOutput();

		// Create the directory and file names.
		$base = DIR_OPENCART . 'catalog/view/html/';
		$directory = parse_url($this->config->get('config_url'), PHP_URL_HOST) . '/' . $this->request->get['_route_'];
		$filename = 'index.html';

		oc_directory_create($base . $directory, 0777);

		file_put_contents($base . $directory . $filename, $output);
	}
}
