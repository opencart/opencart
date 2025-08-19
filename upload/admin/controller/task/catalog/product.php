<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Product
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Product extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/product');


		$limit = [10, 25, 50, 75, 100];


		return [];
	}

	public function list(array $args = []): array {
		$this->load->language('task/catalog/product');

		return [];
	}

	public function rating(array $args = []): array {
		$this->load->language('task/catalog/product');
		
		return [];
	}
	
	public function sale(array $args = []): array {
		$this->load->language('task/catalog/product');

		return [];
	}


	public function clear(array $args = []): array {
		$this->load->language('task/catalog/product');

		return [];
	}
}
