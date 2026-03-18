<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Filter
 *
 * @package Opencart\Admin\Controller\Event
 */
class Filter extends \Opencart\System\Engine\Controller {
	/*
	 * Add Filter Group
	 *
	 * Adds task to generate new filter data.
	 *
	 * Trigger admin/model/catalog/filter_group.editFilterGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addFilter(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'filter_group.' . $output,
			'action' => 'task/catalog/filter',
			'args'   => ['filter_group_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Edit Filter
	 *
	 * Adds task to generate new filter data.
	 *
	 * Trigger admin/model/catalog/filter_group.editFilterGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editFilter(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'filter_group.' . $args[0],
			'action' => 'task/catalog/filter',
			'args'   => ['filter_group_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Delete Filter
	 *
	 * Adds task to generate new filter data.
	 *
	 * Trigger admin/model/catalog/filter_group.deleteFilterGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteFilter(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'filter.delete.' . $args[0],
			'action' => 'task/catalog/filter.delete',
			'args'   => ['filter_group_id' => $args[0]],
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
