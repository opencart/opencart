<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Category
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Category extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates information task list.
	 *
	 * @return array
	 */
	public function index(): void {
		$this->load->language('task/catalog/category');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		//if (!$this->user->hasPermission('modify', 'task/catalog/article')) {
		$json['error'] = $this->language->get('error_permission');
		//}

		$directory = DIR_CATALOG . 'view/data/cms/';

		//if (!is_dir($directory) && !mkdir($directory, 0777)) {
		//	$json['error'] = $this->language->get('error_directory');
		//}


	}

	public function clear(array $args = []): array {
		$this->load->language('task/catalog/category');


	}
}
