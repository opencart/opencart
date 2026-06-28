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
		$routes = [];

		$directory = DIR_CATALOG . 'view/template/';

		$files = oc_directory_read($directory, true, '/.+\.html$/');

		foreach ($files as $file) {
			$route = substr(substr($file, strlen($directory)), 0, -5);

			$pos = strpos($route, '/');

			if ($pos !== false && in_array(substr($route, 0, $pos), ['mail'])) {
				continue;
			}

			$routes[] = $route;
		}

		// Extension
		$directories = oc_directory_read(DIR_EXTENSION, false);

		foreach ($directories as $directory) {
			$extension = basename($directory);

			$path = DIR_EXTENSION . $extension . '/catalog/view/template/';

			$files = oc_directory_read($path, true, '/.+\.html$/');

			foreach ($files as $file) {
				$routes[] = 'extension/' . $extension . '/' . substr(substr($file, strlen($path)), 0, -5);
			}
		}

		$this->load->model('setting/task');

		foreach ($routes as $route) {
			$task_data = [
				'code'   => 'template.info.' . $store_info['store_id'] . '.' . str_replace('/', '.', $route),
				'action' => 'task/catalog/template.info',
				'args'   => [
					'route'    => $route,
					'store_id' => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generate the template list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
     */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/template');

		if (!array_key_exists('route', $args)) {
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

		$filter_data = [
			'filter_route'    => $args['route'],
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'start'           => 0,
			'limit'           => 1
		];

		$this->load->model('design/template');

		$results = $this->model_design_template->getTemplates($filter_data);

		$template_info = array_shift($results);

		if ($template_info && $template_info['status']) {
			$code = $template_info['code'];
		} else {
			if (substr($args['route'], 0, 10) != 'extension/') {
				$directory = DIR_CATALOG . 'view/template/';
				$file = $directory . $args['route'] . '.html';
			} else {
				// Extension template load
				$part = explode('/', $args['route']);

				$directory = DIR_EXTENSION . $part[1] . '/catalog/view/template/';

				unset($part[0]);
				unset($part[1]);

				$file = $directory . implode('/', $part) . '.html';
			}

			if (!is_file($file) || (substr(str_replace('\\', '/', realpath($file)), 0, strlen($directory)) != $directory)) {
				return ['error' => $this->language->get('error_file')];
			}

			$code = file_get_contents($file);
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/template/';
		$filename = $args['route'] . '.html';

		$pos = strrpos($args['route'], '/');

		if ($pos !== false) {
			$directory = substr($args['route'], 0, $pos);
			$filename = substr($args['route'], $pos) . '.json';
		}

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, $code)) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $args['route'])];
	}
}