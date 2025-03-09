<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Payment;
/**
 * Class COD
 *
 * Can be called from $this->load->model('extension/opencart/payment/cod');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Payment
 */
class COD extends \Opencart\System\Engine\Model {
	/**
	 * Get Methods
	 *
	 * @param array<string, mixed> $address array of data
	 *
	 * @return array<string, mixed>
	 */
	public function getMethods(array $address = []): array {
		$this->load->language('extension/opencart/payment/cod');

		if ($this->cart->hasSubscription()) {
			$status = false;
		} elseif (!$this->cart->hasShipping()) {
			$status = false;
		} elseif (!$this->config->get('config_checkout_payment_address')) {
			$status = true;
		} elseif (!$this->config->get('payment_cod_geo_zone_id')) {
			$status = true;
		} else {
			// Geo Zone
			$this->load->model('localisation/geo_zone');

			$results = $this->model_localisation_geo_zone->getGeoZone((int)$this->config->get('payment_cod_geo_zone_id'), (int)$address['country_id'], (int)$address['zone_id']);

			if ($results) {
				$status = true;
			} else {
				$status = false;
			}
		}

		$method_data = [];

		if ($status) {
			$option_data['cod'] = [
				'code' => 'cod.cod',
				'name' => $this->language->get('heading_title')
			];

			$method_data = [
				'code'       => 'cod',
				'name'       => $this->language->get('heading_title'),
				'option'     => $option_data,
				'sort_order' => $this->config->get('payment_cod_sort_order')
			];
		}

		return $method_data;
	}
}
