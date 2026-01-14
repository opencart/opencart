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
	 * Called using model/catalog/manufacturer/addManufacturer/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addManufacturer(string &$route, array &$args, &$output): void {
		// List
		$task_data = [
			'code'   => 'manufacturer.list',
			'action' => 'task/catalog/manufacturer.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Info
		$task_data = [
			'code'   => 'manufacturer.info.' . $output,
			'action' => 'task/catalog/manufacturer.info',
			'args'   => ['manufacturer_id' => $output]
		];

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Manufacturer
	 *
	 * Adds task to generate new manufacturer data.
	 *
	 * Called using model/catalog/manufacturer/editManufacturer/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editManufacturer(string &$route, array &$args, &$output): void {
		// List
		$task_data = [
			'code'   => 'manufacturer.list',
			'action' => 'task/catalog/manufacturer.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Info
		$task_data = [
			'code'   => 'manufacturer.info.' . $args[0],
			'action' => 'task/catalog/manufacturer.info',
			'args'   => ['manufacturer_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);

		// Products
		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProductsByManufacturerId($args[0]);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'product.info.' . $result['product_id'],
				'action' => 'task/catalog/product.info',
				'args'   => ['product_id' => $result['product_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Delete Manufacturer
	 *
	 * Adds task to generate new manufacturer data.
	 *
	 * Called using model/catalog/manufacturer/editManufacturer/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteManufacturer(string &$route, array &$args, &$output): void {
		// List
		$task_data = [
			'code'   => 'manufacturer.list',
			'action' => 'task/catalog/manufacturer.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Delete
		$task_data = [
			'code'   => 'manufacturer.delete.' . $args[0],
			'action' => 'task/catalog/manufacturer.delete',
			'args'   => ['manufacturer_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Products
		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProductsByManufacturerId($args[0]);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'product.info.' . $result['product_id'],
				'action' => 'task/catalog/product.info',
				'args'   => ['product_id' => $result['product_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
