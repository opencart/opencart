<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Event
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * Add Information
	 *
	 * Adds task to generate new information data.
	 *
	 * Trigger model/catalog/information/addInformation/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addInformation(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [];

		if (isset($args[1]['information_store'])) {
			$store_ids = (array)$args[1]['information_store'];
		}

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'information.list.' . $store_id,
				'action' => 'task/catalog/information.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'information.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/information.info',
				'args'   => [
					'information_id' => $output,
					'store_id'       => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Edit Information
	 *
	 * Adds task to generate new information data.
	 *
	 * Trigger model/catalog/information/addInformation/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editInformation(string &$route, array &$args): void {
		$this->load->model('setting/task');

		$store_ids = [];

		if (isset($args[1]['information_store'])) {
			$store_ids = (array)$args[1]['information_store'];
		}

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'information.list.' . $store_id,
				'action' => 'task/catalog/information.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'information.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/information.info',
				'args'   => [
					'information_id' => $args[0],
					'store_id'       => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Remove stores
		$this->load->model('catalog/information');

		$remove_ids = array_diff($this->model_catalog_information->getStores($args[0]), $store_ids);

		foreach ($remove_ids as $remove_id) {
			$task_data = [
				'code'   => 'information.list.' . $remove_id,
				'action' => 'task/catalog/information.list',
				'args'   => ['store_id' => $remove_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'information.delete.' . $remove_id . '.' . $args[0],
				'action' => 'task/catalog/information.delete',
				'args'   => [
					'information_id' => $args[0],
					'store_id'       => $remove_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Delete Information
	 *
	 * Adds task to generate new information data.
	 *
	 * Trigger model/catalog/information/deleteInformation/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteInformation(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$this->load->model('catalog/information');

		$store_ids = $this->model_catalog_information->getStores($args[0]);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'information.list.' . $store_id,
				'action' => 'task/catalog/information.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'information.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/information.delete',
				'args'   => [
					'information_id' => $args[0],
					'store_id'       => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
