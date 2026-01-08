<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Event
 */
class Currency extends \Opencart\System\Engine\Controller {
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

	public function addCurrency(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'currency.info.' . $output,
			'action' => 'task/catalog/currency.info',
			'args'   => ['currency_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		/*
		// Admin
		$task_data = [
			'code'   => 'currency',
			'action' => 'task/admin/currency.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'currency',
			'action' => 'task/admin/currency.info',
			'args'   => ['currency_id' => $output]
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}

	public function editCurrency(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'currency.info.' . $args[0],
			'action' => 'task/catalog/currency.info',
			'args'   => ['currency_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Admin
		/*
		$task_data = [
			'code'   => 'currency',
			'action' => 'task/admin/currency.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'currency',
			'action' => 'task/admin/currency.info',
			'args'   => ['currency_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}

	public function deleteCurrency(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'currency.delete.' . $args[0],
			'action' => 'task/admin/currency.delete',
			'args'   => ['currency_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
