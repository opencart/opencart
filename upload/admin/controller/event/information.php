<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Event
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * Add Information
	 *
	 * Adds task to generate new information data.
	 *
	 * Called using model/catalog/information/addInformation/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addInformation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information.list',
			'action' => 'task/catalog/information.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'information.info.' . $output,
			'action' => 'task/catalog/information.info',
			'args'   => ['information_id' => $output]
		];

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Information
	 *
	 * Adds task to generate new information data.
	 *
	 * Called using model/catalog/information/addInformation/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editInformation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information.list',
			'action' => 'task/catalog/information.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'information.info.' . $args[0],
			'action' => 'task/catalog/information.info',
			'args'   => ['information_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Delete Information
	 *
	 * Adds task to generate new information data.
	 *
	 * Called using model/catalog/information/deleteInformation/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteInformation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information.list',
			'action' => 'task/catalog/information.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'information.delete.' . $args[0],
			'action' => 'task/catalog/information.delete',
			'args'   => ['information_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
	}
}
