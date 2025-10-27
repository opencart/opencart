<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Manufacturer
 *
 * @package Opencart\Admin\Controller\Event
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new manufacturer list
	 *
	 * Called using admin/model/catalog/manufacturer/addManufacturer/after
	 * Called using admin/model/catalog/manufacturer/editManufacturer/after
	 * Called using admin/model/catalog/manufacturer/deleteManufacturer/after
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
