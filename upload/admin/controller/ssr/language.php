<?php
namespace Opencart\Admin\Controller\Ssr;
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
		$this->load->language('ssr/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_CATALOG . 'view/data/localisation/';

		if (!is_dir($directory) && !mkdir($directory, 0777)) {
			$json['error'] = sprintf($this->language->get('error_directory'), $directory);
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$file = $directory . 'language.json';

			if (file_put_contents($file, json_encode($languages))) {
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = sprintf($this->language->get('error_file'), $file);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('ssr/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$file = DIR_CATALOG . 'view/data/localisation/language.json';

			if  (is_file($file)) {
				unlink($file);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}