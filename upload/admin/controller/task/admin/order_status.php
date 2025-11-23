<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Order Status
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class OrderStatus extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate order status task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/order_status');

		// Clear old data
		$task_data = [
			'code'   => 'order_status',
			'action' => 'task/admin/order_status.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'order_status',
			'action' => 'task/admin/order_status.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON order status list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/order_status');

		$order_status_data = [];

		$this->load->model('localisation/order_status');

		$order_statuses = $this->model_localisation_order_status->getOrderStatuses();

		foreach ($order_statuses as $order_status) {
			$order_status_data[] = $order_status + ['description' => $this->model_localisation_order_status->getDescriptions($order_status['order_status_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'order_status.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($order_status_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON order status files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/order_status');

		$file = DIR_APPLICATION . 'view/data/localisation/order_status.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
