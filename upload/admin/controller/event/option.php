<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Option
 *
 * @package Opencart\Admin\Controller\Event
 */
class Option extends \Opencart\System\Engine\Controller {
	/*
	 * Edit Option
	 *
	 * Adds task to generate new product data.
	 *
	 * Trigger admin/model/catalog/option.editOption/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editOption(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProductsByOptionId($args[0]);

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			foreach ($results as $result) {
				$task_data = [
					'code'   => 'product.' . $result['product_id'],
					'action' => 'task/catalog/product',
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