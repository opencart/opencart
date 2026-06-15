<?php
namespace Opencart\admin\controller\task\shop;
/**
 * Class Store
 *
 * @package Opencart\Admin\Controller\Event
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * Add Store
	 *
	 * Adds task to generate new store list
	 *
	 * model/setting/store/addStore
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(array &$args): array {
		$this->load->language('task/catalog/store');

		if (!array_key_exists('store_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$this->load->model('setting/task');

		// Comment

		// Filter

		// Review

		// Setting

		// Store

		// tax_rate

		// Template

		// Translation
	}



	public function setting(array $args = []): array {
		$this->load->model('setting/task');

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		return ['success' => sprintf($this->language->get('text_setting'), $store_info['name'])];
	}

	/**
	 * Delete Store
	 *
	 * Adds task to generate new store list
	 *
	 * model/setting/store/deleteStore
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteStore(string &$route, array &$args): array {
		// Language
		$task_data = [
			'code'   => 'language',
			'action' => 'task/catalog/language',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Currency
		if ($this->config->get('config_currency_auto')) {
			$task_data = [
				'code'   => 'currency',
				'action' => 'task/catalog/currency',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Location
		$task_data = [
			'code'   => 'location',
			'action' => 'task/catalog/location',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'store',
			'action' => 'task/admin/store',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_translation')];
	}
}
