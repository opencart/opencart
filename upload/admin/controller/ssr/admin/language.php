<?php
namespace Opencart\Admin\Controller\Ssr\Admin;
/**
 * Class Language
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index() {
		$this->load->language('ssr/admin/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/admin/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				$base = DIR_APPLICATION . 'view/data/';
				$directory = $language['code'] . '/localisation/';
				$filename = 'language.json';

				if (!oc_directory_create($base . $directory, 0777)) {
					$json['error'] = sprintf($this->language->get('error_directory'), $directory);

					break;
				}

				$file = $base . $directory . $filename;

				if (!file_put_contents($file, json_encode($languages))) {
					$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

					break;
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('ssr/admin/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/admin/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/localisation/language.json';

				if (is_file($file)) {
					unlink($file);
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}