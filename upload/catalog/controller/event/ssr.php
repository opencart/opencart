<?php
namespace Opencart\Catalog\Controller\Event;
/**
 * Class Ssr
 *
 * @package Opencart\Catalog\Controller\Event
 */
class Ssr extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Triggers
	 *
	 * catalog/controller/product/category/after
	 * catalog/controller/product/compare/after
	 * catalog/controller/product/manufacturer/after
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
	public function index(string &$route, array &$args, string &$code): void {



	}
}
