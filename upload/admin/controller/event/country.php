<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Event
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Add Country
	 *
	 * Adds task to generate new country data.
	 *
	 * Trigger admin/model/localisation/country/addCountry/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addCountry(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			// Generate new country list.
			$task_data = [
				'code'   => 'country.' . $store_id,
				'action' => 'task/catalog/country',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			// Generate new country info page.
			$task_data = [
				'code'   => 'country.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/country.info',
				'args'   => [
					'country_id' => $output,
				    'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Admin
		$task_data = [
			'code'   => 'admin.country',
			'action' => 'task/admin/country',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'admin.country.info.' . $output,
			'action' => 'task/admin/country.info',
			'args'   => ['country_id' => $output]
		];

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Country
	 *
	 * Adds task to generate new country data.
	 *
	 * Trigger admin/model/localisation/country/editCountry/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editCountry(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'country.' . $store_id,
				'action' => 'task/catalog/country',
				'args'   => []
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'country.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/country.info',
				'args'   => [
					'country_id' => $args[0],
				    'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Admin
		$task_data = [
			'code'   => 'admin.country',
			'action' => 'task/admin/country',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'admin.country.info.' . $args[0],
			'action' => 'task/admin/country.info',
			'args'   => ['country_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Delete Country
	 *
	 * Adds task to generate new country data.
	 *
	 * Trigger admin/model/localization/country/deleteCountry/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteCountry(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'country.' . $store_id,
				'action' => 'task/catalog/country',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'country.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/country.delete',
				'args'   => [
					'country_id' => $args[0],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Admin
		$task_data = [
			'code'   => 'admin.country',
			'action' => 'task/admin/country',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'admin.country.delete.' . $args[0],
			'action' => 'task/admin/country.delete',
			'args'   => ['country_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
	}
}
