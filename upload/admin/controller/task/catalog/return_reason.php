<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Return Reason
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class ReturnReason extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates return reason task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/return_reason');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'return_reason',
					'action' => 'task/catalog/return_reason.list',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates return reason list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/return_reason');

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$return_reason_data = [];

		$this->load->model('localisation/return_reason');

		$return_reasons = $this->model_localisation_return_reason->getReturnReasons();

		foreach ($return_reasons as $return_reason) {
			$description_info = $this->model_localisation_return_reason->getDescription($return_reason['return_reason_id'], $language_info['language_id']);

			if ($description_info) {
				$return_reason_data[$return_reason['return_reason_id']] = $description_info + $return_reason;
			}
		}

		$base = DIR_APPLICATION . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
		$filename = 'return_reason.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($return_reason_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}

	public function clear(array $args = []): array {
		$this->load->language('task/catalog/return_reason');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$base = DIR_CATALOG . 'view/data/';
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/';
				$filename = 'return_reason.json';

				$file = $base . $directory . $filename;

				if (is_file($file)) {
					unlink($file);
				}

				$files = oc_directory_read($base . $directory, false, '/.+\return_reason-.+.json$/');

				foreach ($files as $file) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
