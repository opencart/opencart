<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Manufacturer
 *
 * @package Opencart\Admin\Controller\Event
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Add Manufacturer
	 *
	 * Adds task to generate new manufacturer data.
	 *
	 * Trigger model/catalog/manufacturer/addManufacturer/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addManufacturer(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$store_ids = [];

		if (isset($args[1]['manufacturer_store'])) {
			$store_ids = (array)$args[1]['manufacturer_store'];
		}

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'manufacturer.list.' . $store_id,
				'action' => 'task/catalog/manufacturer.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'manufacturer.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/manufacturer.info',
				'args'   => [
					'manufacturer_id' => $output,
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Edit Manufacturer
	 *
	 * Adds task to generate new manufacturer data.
	 *
	 * Trigger model/catalog/manufacturer/editManufacturer/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editManufacturer(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$store_ids = [];

		if (isset($args[1]['manufacturer_store'])) {
			$store_ids = (array)$args[1]['manufacturer_store'];
		}

		// Products
		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProducts(['filter_manufacturer_id' => $args[0]]);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'manufacturer.list.' . $store_id,
				'action' => 'task/catalog/manufacturer.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			// Info
			$task_data = [
				'code'   => 'manufacturer.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/manufacturer.info',
				'args'   => [
					'manufacturer_id' => $args[0],
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			foreach ($results as $result) {
				$task_data = [
					'code'   => 'product.' . $store_id . '.' . $result['product_id'],
					'action' => 'task/catalog/product',
					'args'   => [
						'product_id' => $result['product_id'],
						'store_id'   => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		// Remove store ID's
		$this->load->model('catalog/manufacturer');

		$remove_ids = array_diff($this->model_catalog_manufacturer->getStores($args[0]), $store_ids);

		foreach ($remove_ids as $remove_id) {
			$task_data = [
				'code'   => 'manufacturer.delete.' . $remove_id . '.' . $args[0],
				'action' => 'task/catalog/manufacturer.delete',
				'args'   => [
					'manufacturer_id' => $args[0],
					'store_id'        => $remove_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Delete Manufacturer
	 *
	 * Adds task to generate new manufacturer data.
	 *
	 * Trigger model/catalog/manufacturer/editManufacturer/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteManufacturer(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$this->load->model('catalog/manufacturer');

		$store_ids = $this->model_catalog_manufacturer->getStores($args[0]);

		foreach ($store_ids as $store_id) {
			// List
			$task_data = [
				'code'   => 'manufacturer.list.' . $store_id,
				'action' => 'task/catalog/manufacturer.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			// Delete
			$task_data = [
				'code'   => 'manufacturer.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/manufacturer.delete',
				'args'   => [
					'manufacturer_id' => $args[0],
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
