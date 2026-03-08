<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Store
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate JSON store list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/store');

		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		$directory = DIR_OPENCART . 'view/data/setting/';
		$filename = 'store.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($stores))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON store files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/store');

		$file = DIR_APPLICATION . 'view/data/setting/setting.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
