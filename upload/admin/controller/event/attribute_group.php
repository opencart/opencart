<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Attribute Group
 *
 * @package Opencart\Admin\Controller\Event
 */
class AttributeGroup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new filter group list
	 *
	 * Called using admin/model/catalog/attribute_group/addAttributeGroup/after
	 * Called using admin/model/catalog/attribute_group/editAttributeGroup/after
	 * Called using admin/model/catalog/attribute_group/deleteAttributeGroup/after
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
