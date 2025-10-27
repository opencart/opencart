<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Filter Group
 *
 * @package Opencart\Admin\Controller\Event
 */
class FilterGroup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new filter group list
	 *
	 * Called using admin/model/catalog/filter_group/addFilter/after
	 * Called using admin/model/catalog/filter_group/editFilter/after
	 * Called using admin/model/catalog/filter_group/catalog/deleteFilter/after
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
