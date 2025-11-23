<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Return Action
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class ReturnAction extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate return action task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/return_action');

		// Clear old data
		$task_data = [
			'code'   => 'return_action',
			'action' => 'task/admin/return_action.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'return_action',
			'action' => 'task/admin/return_action.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON return action list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/return_action');

		$return_action_data = [];

		$this->load->model('localisation/return_action');

		$return_actions = $this->model_localisation_return_action->getReturnActions();

		foreach ($return_actions as $return_action) {
			$return_action_data[] = $return_action + ['description' => $this->model_localisation_return_action->getDescriptions($return_action['return_action_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'return_action.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($return_action_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON return action files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/return_action');

		$file = DIR_APPLICATION . 'view/data/localisation/return_action.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
