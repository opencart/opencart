<?php
namespace Opencart\admin\controller\ssr\catalog;
/**
 * Class Manufacturer
 *
 * @package Opencart\Admin\Controller\Event
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new manufacturer list
	 *
	 * Called using admin/model/catalog/manufacturer/addManufacturer/after
	 * Called using admin/model/catalog/manufacturer/editManufacturer/after
	 * Called using admin/model/catalog/manufacturer/deleteManufacturer/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addManufacturer(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'manufacturer.info.' . $output,
			'action' => 'task/catalog/manufacturer.info',
			'args'   => ['manufacturer_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editManufacturer(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'manufacturer.info.' . $args[0],
			'action' => 'task/catalog/manufacturer.info',
			'args'   => ['manufacturer_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Update Products
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

	public function deleteManufacturer(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'manufacturer.delete.' . $args[0],
			'action' => 'task/catalog/manufacturer.delete',
			'args'   => ['manufacturer_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
