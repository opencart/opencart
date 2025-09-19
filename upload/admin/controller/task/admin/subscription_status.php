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

		$this->load->model('setting/task');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$task_data = [
				'code'   => 'subscription_status',
				'action' => 'task/admin/subscription_status.list',
				'args'   => ['language_id' => $language['language_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
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

		if (!array_key_exists('language_id', $args)) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/subscription_status');

		$subscription_statuses = $this->model_localisation_subscription_status->getSubscriptionStatuses(['filter_language_id' => $language_info['language_id']]);

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'subscription_status.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => $this->language->get('error_directory', $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($subscription_statuses))) {
			return ['error' => $this->language->get('error_file', $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list', $language_info['name'])];
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

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/localisation/subscription_status.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
