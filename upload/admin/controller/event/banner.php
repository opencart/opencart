<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Banner
 *
 * @package Opencart\Admin\Controller\Event
 */
class Banner extends \Opencart\System\Engine\Controller {
	/*
	 * Add Banner
	 *
	 * Adds task to generate new banner data.
	 *
	 * Trigger admin/model/deign/banner/addBanner/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addBanner(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'banner.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/banner.info',
				'args'   => [
					'banner_id' => $output,
					'store_id'  => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Edit Banner
	 *
	 * Adds task to generate new banner data.
	 *
	 * Trigger admin/model/deign/banner/editBanner/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editBanner(string &$route, array &$args): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'banner.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/banner.info',
				'args'   => [
					'banner_id' => $args[0],
					'store_id'  => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Delete Banner
	 *
	 * Adds task to generate new banner data.
	 *
	 * Trigger admin/model/deign/banner/deleteBanner/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteBanner(string &$route, array &$args): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'banner.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/banner.delete',
				'args'   => [
					'banner_id' => $args[0],
					'store_id'  => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
