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
	 * Generates the translation list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/translation');

		$paths = [];

		$directory = DIR_CATALOG . 'language/' . $this->config->get('config_language') . '/';

		$files = oc_directory_read($directory, true, '/.+\.php$/');

		foreach ($files as $file) {
			$paths[] = substr(substr($file, 0, strrpos($file, '.')), strlen($directory));
		}

		$directories = oc_directory_read(DIR_EXTENSION, false);

		foreach ($directories as $directory) {
			$extension = basename($directory);

			$path = DIR_EXTENSION . $extension . '/catalog/language/' . $this->config->get('config_language') . '/';

			$files = oc_directory_read($path, true, '/.+\.php/');

			foreach ($files as $file) {
				$language = substr(substr($file, 0, strrpos($file, '.')), strlen($path));

				if ($language) {
					$paths[] = 'extension/' . $extension . '/' . $language;
				}
			}
		}


		$this->load->model('setting/task');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			foreach ($languages as $language) {

				$task_data = [
					'code'   => 'translation',
					'action' => 'task/catalog/translation.list',
					'args'   => [
						'route'       =>
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

		}

		return ['success' => $this->language->get('text_success')];
	}

	public function write(array $args = []): array {
		$this->load->language('task/catalog/translation');









		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * Path
	 *
	 * @return void
	 */
	public function path(): void {
		$this->load->language('design/translation');

		$json = [];

		if (isset($this->request->get['language_id'])) {
			$language_id = (int)$this->request->get['language_id'];
		} else {
			$language_id = 0;
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($language_id);

		if (!empty($language_info)) {
			$directory = DIR_CATALOG . 'language/' . $language_info['code'] . '/';

			$files = oc_directory_read($directory, true, '/.+\.php$/');

			foreach ($files as $file) {
				$template = substr(substr($file, 0, strrpos($file, '.')), strlen($directory));

				if ($template) {
					$json[] = $template;
				}
			}

			$directories = oc_directory_read(DIR_EXTENSION, false);

			foreach ($directories as $directory) {
				$extension = basename($directory);

				$path = DIR_EXTENSION . $extension . '/catalog/language/' . $language_info['code'] . '/';

				$files = oc_directory_read($path, true, '/.+\.php/');

				foreach ($files as $file) {
					$language = substr(substr($file, 0, strrpos($file, '.')), strlen($path));

					if ($language) {
						$json[] = 'extension/' . $extension . '/' . $language;
					}
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function translation(): void {
		$this->load->language('task/catalog/translation');

		$json = [];

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['language_id'])) {
			$language_id = (int)$this->request->get['language_id'];
		} else {
			$language_id = 0;
		}

		if (isset($this->request->get['path'])) {
			$route = $this->request->get['path'];
		} else {
			$route = '';
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($language_id);

		$part = explode('/', $route);

		if ($part[0] != 'extension') {
			$directory = DIR_CATALOG . 'language/';
		} else {
			$directory = DIR_EXTENSION . $part[1] . '/catalog/language/';

			array_shift($part);
			// Don't remove. Required for extension route.
			array_shift($part);

			$route = implode('/', $part);
		}

		if ($language_info && is_file($directory . $language_info['code'] . '/' . $route . '.php') && substr(str_replace('\\', '/', realpath($directory . $language_info['code'] . '/' . $route . '.php')), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
			$_ = [];

			include($directory . $language_info['code'] . '/' . $route . '.php');

			foreach ($_ as $key => $value) {
				$json[] = [
					'key'   => $key,
					'value' => $value
				];
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Clear
	 *
	 * Clears generated translation files.
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/translation');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/customer/translation.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
