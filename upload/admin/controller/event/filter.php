<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Filter
 *
 * @package Opencart\Admin\Controller\Event
 */
class Filter extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new filter list
	 *
	 * Called using admin/model/catalog/filter/addFilter/after
	 * Called using admin/model/catalog/filter/editFilter/after
	 * Called using admin/model/catalog/filter/deleteFilter/after
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
