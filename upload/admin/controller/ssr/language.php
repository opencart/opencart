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
	public function index(): void {
		$this->load->language('localisation/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Languages
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$file = DIR_CATALOG . 'view/data/localisation/language.json';

			if (file_put_contents($file, json_encode($languages))) {
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
