<?php
namespace Opencart\Admin\Controller\Task\Admin;
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
		$this->load->language('task/admin/translation');

		$paths = [];

		$directory = DIR_APPLICATION . 'language/' . $this->config->get('config_language') . '/';

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

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($paths as $path) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'translation',
					'action' => 'task/catalog/translation.list',
					'args'   => [
						'route'       => $path,
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

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$route = $args['route'];

		$pos = strpos($route, '/');

		if (substr($route, 0, $pos) == 'extension/') {
			$path = DIR_EXTENSION . $extension . '/catalog/language/' . $this->config->get('config_language') . '/';
		}


		$language = substr(substr($file, 0, strrpos($file, '.')), strlen($path));


		$path = DIR_EXTENSION . $extension . '/catalog/language/' . $language_info['code'] . '/';

		 =

		if (is_file()) {

		}




		$directories = [];

		$directory = DIR_CATALOG . 'language/' . $this->config->get('language_code') . '/';

		$files = oc_directory_read($directory, true, '/.+\.php$/');

		foreach ($files as $file) {
			$template = substr(substr($file, 0, strrpos($file, '.')), strlen($directory));

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




		return ['success' => $this->language->get('text_success')];
	}




	/**
	 * Clear
	 *
	 * Clears generated translation files.
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/translation');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/language/translation.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
