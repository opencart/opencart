<?php
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Currency extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('ssr/admin/currency');


		if ($route == 'model/setting/setting/editSetting' && $args[0] == 'config' && isset($args[1]['config_currency'])) {
			$currency = $args[1]['config_currency'];
		} else {
			$currency = $this->config->get('config_currency');
		}

		// Extension
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('currency', $this->config->get('config_currency_engine'));

		if ($extension_info) {
			$this->load->controller('extension/' . $extension_info['extension'] . '/currency/' . $extension_info['code'] . '.currency', $currency);
		}





		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/admin/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$this->load->model('localisation/currency');

			$currencies = $this->model_localisation_currency->getCurrencies();

			foreach ($languages as $language) {
				$base = DIR_APPLICATION . 'view/data/';
				$directory = $language['code'] . '/localisation/';
				$filename = 'currency.json';

				if (!oc_directory_create($base . $directory, 0777)) {
					$json['error'] = sprintf($this->language->get('error_directory'), $directory);

					break;
				}

				$file = $base . $directory . $filename;

				if (!file_put_contents($file, json_encode($currencies))) {
					$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

					break;
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear(): void {
		$this->load->language('ssr/admin/currency');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/admin/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/localisation/currency.json';

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
