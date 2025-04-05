<?php
namespace Opencart\Admin\Controller\Ssr;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Currency extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index() {
		$this->load->language('localisation/currency');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_CATALOG . 'view/data/localisation/';

		if (!is_dir($directory) && !mkdir($directory, 0777)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Currency
			$this->load->model('localisation/currency');

			$currencies = $this->model_localisation_currency->getCurrencies();

			$file = $directory . 'currency.json';

			if (file_put_contents($file, json_encode($currencies))) {
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}