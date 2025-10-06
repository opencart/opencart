<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Language
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate language list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/language');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$base = DIR_APPLICATION . 'view/data/';
			$directory = $language['code'] . '/localisation/';
			$filename = 'language.json';

			if (!oc_directory_create($base . $directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($base . $directory . $filename, json_encode($languages))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => $this->language->get('text_success')];
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

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/localisation/language.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
