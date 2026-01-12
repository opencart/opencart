<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Option
 *
 * @package Opencart\Admin\Controller\Event
 */
class Option extends \Opencart\System\Engine\Controller {
	public function editOption(string &$route, array &$args, &$output): void {
		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProductsByOptionId($args[0]);

		$this->load->model('setting/task');

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'product.info.' . $result['product_id'],
				'action' => 'task/catalog/product.info',
				'args'   => ['product_id' => $result['product_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	public function deleteOption(string &$route, array &$args, &$output): void {
		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProductsByOptionId($args[0]);

		$this->load->model('setting/task');

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