<?php
namespace Opencart\Admin\Controller\Ssr\Catalog;
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
	 *
	 */
	public function index() {
		$this->load->language('ssr/catalog/currency');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/catalog/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$stores = [];

			$stores[] = [
				'store_id' => 0,
				'url'      => HTTP_CATALOG
			];

			$this->load->model('setting/store');

			$stores = array_merge($stores, $this->model_setting_store->getStores());

			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$this->load->model('localisation/currency');

			$currencies = $this->model_localisation_currency->getCurrencies();

			foreach ($stores as $store) {
				$store_url = parse_url($store['url'], PHP_URL_HOST);

				foreach ($languages as $language) {
					$base = DIR_CATALOG . 'view/data/';
					$directory = $store_url . '/' . $language['code'] . '/localisation/';
					$filename = 'currency.json';

					if (!oc_directory_create($base . $directory, 0777)) {
						$json['error'] = sprintf($this->language->get('error_directory'), $directory);

						break;
					}

					$currency_data = [];

					$this->language->load('default', '', $language['code']);

					foreach ($currencies as $currency) {
						$currency_data[$currency['code']] = $currency + [
							'decimal_point'  => $this->language->get('decimal_point'),
							'thousand_point' => $this->language->get('thousand_point')
						];
					}

					if (!file_put_contents($base . $directory . $filename, json_encode($currency_data))) {
						$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

						break;
					}
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('ssr/catalog/currency');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/catalog/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$stores = [];

			$stores[] = [
				'store_id' => 0,
				'url'      => HTTP_CATALOG
			];

			$this->load->model('setting/store');

			$stores = array_merge($stores, $this->model_setting_store->getStores());

			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($stores as $store) {
				$store_url = parse_url($store['url'], PHP_URL_HOST);

				foreach ($languages as $language) {
					$file = DIR_CATALOG . 'view/data/' . $store_url . '/' . $language['code'] . '/localisation/currency.json';

					if (is_file($file)) {
						unlink($file);
					}
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}