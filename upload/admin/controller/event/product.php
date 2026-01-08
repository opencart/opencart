<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Product
 *
 * @package Opencart\Admin\Controller\Event
 */
class Product extends \Opencart\System\Engine\Controller {
	public function index(string &$route, array &$args, &$output): void {
		$pos = strpos($route, '.');

		if ($pos == false) {
			return;
		}

		$method = substr($route, 0, $pos);

		$callable = [$this, $method];

		if (is_callable($callable)) {
			$callable($route, $args, $output);
		}
	}

	public function addProduct(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'product.info.' . $output,
			'action' => 'task/catalog/product.info',
			'args'   => ['product_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editProduct(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'product.info.' . $args[0],
			'action' => 'task/catalog/product.info',
			'args'   => ['product_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function deleteProduct(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'product.delete.' . $args[0],
			'action' => 'task/catalog/product.delete',
			'args'   => ['product_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
