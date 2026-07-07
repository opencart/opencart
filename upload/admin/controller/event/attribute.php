<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Attribute
 *
 * @package Opencart\Admin\Controller\Event
 */
class Attribute extends \Opencart\System\Engine\Controller {
	/*
	 * Edit Attribute
	 *
	 * Adds task to generate new product data.
	 *
	 * Trigger admin/model/catalog/product/editAttribute/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editAttribute(string &$route, array &$args): void {
		$this->load->model('setting/task');

		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProductsByAttributeId($args[0]);

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			foreach ($results as $result) {
				$task_data = [
					'code'   => 'product.info.' . $store_id . '.' . $result['product_id'],
					'action' => 'task/catalog/product.info',
					'args'   => [
						'product_id' => $result['product_id'],
						'store_id'   => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}
