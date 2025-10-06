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
		$task_data = [
			'code'   => 'attribute_group',
			'action' => 'task/catalog/attribute_group',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
