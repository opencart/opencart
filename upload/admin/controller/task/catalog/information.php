<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates information task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/information');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'information',
					'action' => 'catalog/information.list',
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
	 * Generates information list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/information');

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

		$information_data = [];

		$this->load->model('catalog/information');

		$informations = $this->model_catalog_information->getInformations();

		foreach ($informations as $information) {
			if ($information['status']) {
				$description_info = $this->model_catalog_information->getDescription($information['information_id'], $language_info['language_id']);

				if ($description_info) {
					$information_data[$information['information_id']] = $description_info + $information;
				}
			}
		}

		$code = preg_replace('/[^A-Z0-9\._-]/i', '', $languages[$description['language_id']]['code']);

		$file = DIR_CATALOG . 'view/data/catalog/information.' . (int)$information['information_id'] . '.' . $code . '.json';

		if (!file_put_contents($file, json_encode($information_data))) {
			return ['error' => $this->language->get('error_file')];
		}



		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}
}
