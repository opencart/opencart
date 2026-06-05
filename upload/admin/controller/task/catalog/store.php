<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Setting
 *
 * Generates setting data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Setting extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate setting task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'setting',
				'action' => 'task/catalog/setting.store',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON currency files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/setting');

		$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '-' . $language['code'] . '.yaml';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
