<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Language
 *
 * Generates language data for the admin
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate language list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/language');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'language.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($languages))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Clears generated language data.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/language');

		$file = DIR_APPLICATION . 'view/data/localisation/language.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
