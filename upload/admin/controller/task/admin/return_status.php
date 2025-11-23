<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Return Status
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class ReturnStatus extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate return status task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/return_status');

		// Clear old data
		$task_data = [
			'code'   => 'return_status',
			'action' => 'task/admin/return_status.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'return_status',
			'action' => 'task/admin/return_status.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON return status list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/return_status');

		$return_status_data = [];

		$this->load->model('localisation/return_status');

		$return_statuses = $this->model_localisation_return_status->getReturnStatuses();

		foreach ($return_statuses as $return_status) {
			$return_status_data[] = $return_status + ['description' => $this->model_localisation_return_reason->getDescriptions($return_status['return_status_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'return_status.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($return_status_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON return status files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/return_status');

		$file = DIR_APPLICATION . 'view/data/localisation/return_status.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
