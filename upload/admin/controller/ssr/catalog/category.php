<?php
namespace Opencart\admin\controller\ssr\catalog;
/**
 * Class Category
 *
 * @package Opencart\Admin\Controller\Event
 */
class Category extends \Opencart\System\Engine\Controller {
	public function add(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'category.info.' . $output,
			'action' => 'task/catalog/category.info',
			'args'   => ['category_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function edit(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'category.info.' . $args[0],
			'action' => 'task/catalog/category.info',
			'args'   => ['category_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function delete(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'category.delete.' . $args[0],
			'action' => 'task/catalog/category.delete',
			'args'   => ['category_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
