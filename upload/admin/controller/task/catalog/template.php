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
		$this->load->language('task/catalog/template');

		if (!array_key_exists('store_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Generate new data
		$ignore = [
			'api',
			'mail',
			'task'
		];

		$routes = [];

		$directory = DIR_CATALOG . 'template/';

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
				'code'   => 'template.info.' . $store_info['store_id'],
				'action' => 'task/catalog/template.write',
				'args'   => [
					'route'       => $route,
					'store_id'    => $store['store_id'],
					'language_id' => $language['language_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}








		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	/**
	 *
	 *
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/template');

		if (!array_key_exists('template_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Template
		$this->load->model('design/template');

		$template_info = $this->model_design_template->getTemplate($args['template_id']);

		if (!$template_info || !$template_info['status']) {
			return ['error' => $this->language->get('error_template')];
		}

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true
		];


		$this->load->model('design/template');

		$results = $this->model_design_template->getTemplates($filter_data);

		foreach ($results as $result) {
			if ($result['status']) {
				$task_data = [
					'code'   => 'template.info.' . $store_info['store_id'] . '.' . $result['template_id'],
					'action' => 'task/catalog/template.info',
					'args'   => [
						'template_id' => $result['template_id'],
						'store_id'    => $store_info['store_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}


		$pos = strrpos($args['route'], '/');

		$directory = DIR_CATALOG . 'shop/' .parse_url($store_info['url'], PHP_URL_HOST) . '/data/template/'  .  substr($args['route'], 0, $pos) . '/';
		$filename = substr($args['route'], $pos + 1) . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_info')];
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
	public function delete(array $args = []): array {
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

