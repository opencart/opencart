<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Translation
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Translation extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate translation task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/translation');

		if (!array_key_exists('store_id', $args)) {
			//return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		//if ($args['store_id']) {
			$this->load->model('setting/store');

			//$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			//if (!$store_info) {
				//return ['error' => $this->language->get('error_store')];
			//}
		//}

		$this->load->model('setting/task');

		// Ignore directories
		$ignore = [
			'api',
			'mail',
			'tool'
		];

		// Generate new data
		$routes = [];

		$directory = DIR_CATALOG . 'language/' . $this->config->get('config_language_catalog') . '/';

		$files = oc_directory_read($directory, true, '/.+\.php$/');

		foreach ($files as $file) {
			$route = substr(substr($file, strlen($directory)), 0, -4);

			$pos = strpos($route, '/');

			if ($pos !== false && in_array(substr($route, 0, $pos), $ignore)) {
				continue;
			}

			$routes[] = $route;
		}

		// Extension
		$directories = oc_directory_read(DIR_EXTENSION, false);

		foreach ($directories as $directory) {
			$extension = basename($directory);

			$path = DIR_EXTENSION . $extension . '/catalog/language/' . $this->config->get('config_language_catalog') . '/';

			$files = oc_directory_read($path, true, '/.+\.php/');

			foreach ($files as $file) {
				$routes[] = 'extension/' . $extension . '/' . substr(substr($file, strlen($path)), 0, -4);
			}
		}

		print_r($routes);

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			foreach ($routes as $route) {
				$task_data = [
					'code'   => 'translation.info.' . $store_info['store_id'] . '.' . $language['language_id'] . '.' . str_replace('/', '.', $route),
					'action' => 'task/catalog/translation.info',
					'args'   => [
						'route'       => $route,
						'store_id'    => $store_info['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
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
		$this->load->language('task/catalog/translation');

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

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		if (substr($args['route'], 0, 10) != 'extension/') {
			$directory = DIR_CATALOG . 'language/' . $language_info['code'] . '/';
			$file = $directory . $args['route'] . '.php';
		} else {
			// Extension template load
			$part = explode('/', $args['route']);

			$directory = DIR_EXTENSION . $part[1] . '/catalog/language/' . $language_info['code'] . '/';

			unset($part[0]);
			unset($part[1]);

			$file = $directory . implode('/', $part) . '.php';
		}

		if (!is_file($file) || (substr(str_replace('\\', '/', realpath($file)), 0, strlen($directory)) != $directory)) {
			return ['error' => $this->language->get('error_file')];
		}

		$_ = [];

		include($file);

		$filter_data = [
			'filter_route'       => $args['route'],
			'filter_store_id'    => $store_info['store_id'],
			'filter_language_id' => $language_info['language_id'],
			'filter_status'      => true,
			'start'              => 0,
			'limit'              => 1
		];

		$this->load->model('design/translation');

		$results = $this->model_design_translation->getTranslations($filter_data);

		$translation_info = array_shift($results);

		if ($translation_info && $translation_info['status']) {
			$descriptions = $this->model_design_translation->getDescriptions($translation_info['translation_id']);

			foreach ($descriptions as $description) {
				$_[$description['key']] = $description['value'];
			}
		}

		$pos = strrpos($args['route'], '/');

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/language/' . $language_info['code'] . '/'  .  substr($args['route'], 0, $pos) . '/';
		$filename = substr($args['route'], $pos + 1) . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($_))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $language_info['name'], $args['route'])];
	}
}