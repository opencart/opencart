<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('task/admin/store');

		$json = [];

		if (!$this->user->hasPermission('modify', 'task/admin/store')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/store');

			$stores = $this->model_setting_store->getStores();

			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				$base = DIR_CATALOG . 'view/data/';
				$directory = $language['code'] . '/setting/';
				$filename = 'store.json';

				if (!oc_directory_create($base . $directory, 0777)) {
					$json['error'] = sprintf($this->language->get('error_directory'), $directory);

					break;
				}

				if (!file_put_contents($base . $directory . $filename, json_encode($stores))) {
					$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

					break;
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}




	public function clear(): void {
		$this->load->language('task/admin/store');

		$json = [];

		if (!$this->user->hasPermission('modify', 'task/admin/store')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/store');

			$stores = $this->model_setting_store->getStores();

			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($stores as $store) {
				$store_url = parse_url($store['url'], PHP_URL_HOST);

				foreach ($languages as $language) {
					$file = DIR_CATALOG . 'view/data/' . $store_url . '/' . $language['code'] . '/setting/store.json';

					if (is_file($file)) {
						unlink($file);
					}
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
