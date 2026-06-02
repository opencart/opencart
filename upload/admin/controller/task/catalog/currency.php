<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Currency
 *
 * Generates currency information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Currency extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate currency task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/currency');

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$store_info = [
				'store_id' => 0,
				'name'     => $this->config->get('config_name'),
				'url'      => HTTP_CATALOG
			];

			if ($args['store_id']) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

				if (!$store_info) {
					return ['error' => $this->language->get('error_store')];
				}
			}

			$currency_data = [];

			$this->load->model('setting/setting');
			$this->load->model('localisation/currency');

			$currencies = (array)$this->model_setting_setting->getValue('config_currency_list', $store_info['store_id']);

			foreach ($currencies as $code) {
				$currency_info = $this->model_localisation_currency->getCurrencyByCode((string)$code);

				if ($currency_info && $currency_info['status']) {
					$currency_data[$currency_info['code']] = [
						'title'         => $currency_info['title'],
						'code'          => $currency_info['code'],
						'symbol_left'   => $currency_info['symbol_left'],
						'symbol_right'  => $currency_info['symbol_right'],
						'decimal_place' => $currency_info['decimal_place'],
						'value'         => $currency_info['value']
					];
				}
			}

			$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
			$filename = 'currency.yaml';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, oc_yaml_encode($currency_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => $this->language->get('text_list')];
	}
}
