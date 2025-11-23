<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Return Reason
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class ReturnReason extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate return reason task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/return_reason');

		// Clear old data
		$task_data = [
			'code'   => 'return_reason',
			'action' => 'task/admin/return_reason.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'return_reason',
			'action' => 'task/admin/return_reason.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON return reason list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/return_reason');

		$return_reason_data = [];

		$this->load->model('localisation/return_reason');

		$return_reasons = $this->model_localisation_return_reason->getReturnReasons();

		foreach ($return_reasons as $return_reason) {
			$return_reason_data[] = $return_reason + ['description' => $this->model_localisation_return_reason->getDescriptions($return_reason['return_action_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'return_reason.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($return_reason_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON return reason files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/return_reason');

		$file = DIR_APPLICATION . 'view/data/localisation/return_reason.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
