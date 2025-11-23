<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Subscription Status
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class SubscriptionStatus extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate subscription status task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/subscription_status');

		// Clear old data
		$task_data = [
			'code'   => 'subscription_status',
			'action' => 'task/admin/subscription_status.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'subscription_status',
			'action' => 'task/admin/subscription_status.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON subscription status list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/subscription_status');

		$subscription_status_data = [];

		$this->load->model('localisation/subscription_status');

		$subscription_statuses = $this->model_localisation_subscription_status->getSubscriptionStatuses();

		foreach ($subscription_statuses as $subscription_status) {
			$subscription_status_data[] = $subscription_status + ['description' => $this->model_localisation_stock_status->getDescriptions($subscription_status['subscription_status_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'subscription_status.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($subscription_status_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON subscription status files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/subscription_status');

		$file = DIR_APPLICATION . 'view/data/localisation/subscription_status.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
