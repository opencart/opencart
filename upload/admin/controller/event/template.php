<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Template
 *
 * @package Opencart\Admin\Controller\Event
 */
class Template extends \Opencart\System\Engine\Controller {
	/**
	 * Add Template
	 *
	 * Adds task to generate new template data.
	 *
	 * model/design/template/addTemplate/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addTemplate(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'template.info.' . $output,
			'action' => 'task/catalog/template.info',
			'args'   => ['template_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Template
	 *
	 * Adds task to generate new template data
	 *.
	 * model/design/template/editTemplate/before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editTemplate(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'template.info.' . $args[0],
			'action' => 'task/catalog/template.info',
			'args'   => ['template_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Delete Template
	 *
	 * Adds task to generate new template data.
	 *
	 * model/design/template/deleteTemplate/before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteTemplate(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'template.delete.' . $args[0],
			'action' => 'task/catalog/template.delete',
			'args'   => ['template_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
