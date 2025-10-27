<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Category
 *
 * @package Opencart\Admin\Controller\Event
 */
class Category extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new category list
	 *
	 * Called using admin/model/catalog/category/addCategory/after
	 * Called using admin/model/catalog/category/editCategory/after
	 * Called using admin/model/catalog/category/deleteCategory/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$files = oc_directory_read(DIR_OPENCART . 'view/html/');

		foreach ($files as $file) {
			oc_directory_delete($file);
		}
	}
}
