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
	 * Generates the return reason task list.
	 *
	 * @return void
	 */
	public function index(): array {
		$this->load->language('task/catalog/return_reason');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/task');

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'return_reason',
					'action' => 'localisation/return_reason.list',
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

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/return_reason');

		$this->load->model('localisation/return_reason');

		$return_reasons = $this->model_localisation_return_reason->getReturnReasons();




			// Get all languages so we don't need to keep querying te DB
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($stores as $store) {
				$countries = $this->model_localisation_country->getCountriesByStoreId($store['store_id']);

				foreach ($languages as $language) {
					$country_data = [];

					foreach ($countries as $country) {
						if ($country['status']) {
							$description_info = $this->model_localisation_country->getDescription($country['country_id'], $language['language_id']);

							if ($description_info) {
								$country_data[$country['country_id']] = $description_info + $country;
							}
						}
					}

					$base = DIR_CATALOG . 'view/data/';
					$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/';
					$filename = 'country.json';

					if (!oc_directory_create($base . $directory, 0777)) {
						$json['error'] = sprintf($this->language->get('error_directory'), $directory);

						break;
					}

					if (!file_put_contents($base . $directory . $filename, json_encode($country_data))) {
						$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

						break;
					}
				}
			}


		// Must not have a path before files and directories can be moved
		if (!$json) {
			$json['text'] = $this->language->get('text_list');

			$json['next'] = $this->url->link('task/catalog/country.info', 'user_token=' . $this->session->data['user_token'], true);
		}


	}


	public function clear(): void {
		$this->load->language('task/catalog/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'task/catalog/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$this->load->model('setting/store');

			$stores = $this->model_setting_store->getStores();

			foreach ($stores as $store) {
				foreach ($languages as $language) {
					$base = DIR_CATALOG . 'view/data/';
					$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/';
					$filename = 'country.json';

					$file = $base . $directory . $filename;

					if (is_file($file)) {
						unlink($file);
					}

					$files = oc_directory_read($base . $directory, false, '/.+\country-.+.json$/');

					foreach ($files as $file) {
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
