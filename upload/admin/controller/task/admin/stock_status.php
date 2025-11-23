<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Stock Status
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class StockStatus extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate stock status task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/stock_status');

		// Clear old data
		$task_data = [
			'code'   => 'stock_status',
			'action' => 'task/admin/stock_status.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'stock_status',
			'action' => 'task/admin/stock_status.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON stock status list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/stock_status');

		$stock_status_data = [];

		$this->load->model('localisation/stock_status');

		$stock_statuses = $this->model_localisation_stock_status->getStockStatuses();

		foreach ($stock_statuses as $stock_status) {
			$stock_status_data[] = $stock_status + ['description' => $this->model_localisation_stock_status->getDescriptions($stock_status['return_status_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'stock_status.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($stock_status_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON stock status files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/stock_status');

		$file = DIR_APPLICATION . 'view/data/localisation/stock_status.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
