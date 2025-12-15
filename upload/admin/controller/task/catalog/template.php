<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Template
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Template extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate the template list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/translation');

		$ignore = [
			'api',
			'mail',
			'task'
		];

		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/task');

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$routes = [];

				$directory = DIR_CATALOG . 'view/template/' . $language['code'] . '/';

				$files = oc_directory_read($directory, true, '/.+\.php$/');

				foreach ($files as $file) {
					$route = substr(substr($file, strlen($directory)), 0, -4);

					$pos = strpos($route, '/');

					if ($pos == false || in_array(substr($route, 0, $pos), $ignore)) {
						continue;
					}

					$routes[] = $route;
				}

				$directories = oc_directory_read(DIR_EXTENSION, false);

				foreach ($directories as $directory) {
					$extension = basename($directory);

					$path = DIR_EXTENSION . $extension . '/catalog/language/' . $language['code'] . '/';

					$files = oc_directory_read($path, true, '/.+\.php/');

					foreach ($files as $file) {
						$routes[] = 'extension/' . $extension . '/' . substr(substr($file, strlen($path)), 0, -4);
					}
				}

				foreach ($routes as $route) {
					$task_data = [
						'code'   => 'translation',
						'action' => 'task/catalog/translation.write',
						'args'   => [
							'route'       => $route,
							'store_id'    => $store['store_id'],
							'language_id' => $language['language_id']
						]
					];

					$this->model_setting_task->addTask($task_data);
				}
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/*
	 * Write
	 *
	 * Write template file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function write(array $args = []): array {
		$this->load->language('task/catalog/translation');

		$required = [
			'route',
			'store_id',
			'language_id'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

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

		$language = new \Opencart\System\Library\Language((string)$language_info['code']);
		$language->addPath(DIR_CATALOG . 'language/');

		$part = explode('/', $args['route']);

		if ($part[0] == 'extension' && count($part) > 2) {
			$language->addPath('extension/' . $part[1], DIR_EXTENSION . $part[1] . '/admin/language/');
		}

		$language->load('default');
		$language->load($args['route']);

		$filter_data = [
			'filter_route'       => $args['route'],
			'filter_store_id'    => $store_info['store_id'],
			'filter_language_id' => $language_info['language_id']
		];

		// Overrides
		$this->load->model('design/translation');

		$results = $this->model_design_translation->getTranslations($filter_data);

		foreach ($results as $result) {
			$language->set($result['key'], $result['value']);
		}

		$data = $language->all();

		ksort($data, SORT_REGULAR);

		$pos = strrpos($args['route'], '/');

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/language/'  .  substr($args['route'], 0, $pos) . '/';
		$filename = substr($args['route'], $pos + 1) . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * Clear
	 *
	 * Delete generated template files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/translation');

		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$directories = oc_directory_read(DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/language/', false);

				foreach ($directories as $directory) {
					oc_directory_delete($directory);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}

