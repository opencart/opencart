<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Payment;
/**
 * Class Cheque
 *
 * Can be called from $this->load->model('extension/opencart/payment/cheque');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Payment
 */
class Cheque extends \Opencart\System\Engine\Model {
	/**
	 * Get Methods
	 *
	 * @param array<string, mixed> $address array of data
	 *
	 * @return array<string, mixed>
	 */
	public function getMethods(array $address = []): array {
		$this->load->language('extension/opencart/payment/cheque');

		if ($this->cart->hasSubscription()) {
			$status = false;
		} elseif (!$this->config->get('config_checkout_payment_address')) {
			$status = true;
		} elseif (!$this->config->get('payment_cheque_geo_zone_id')) {
			$status = true;
		} else {
			// Geo Zone
			$this->load->model('localisation/geo_zone');

			$results = $this->model_localisation_geo_zone->getGeoZone((int)$this->config->get('payment_cheque_geo_zone_id'), (int)$address['country_id'], (int)$address['zone_id']);

			if ($results) {
				$status = true;
			} else {
				$status = false;
			}
		}

		$method_data = [];

		if ($status) {
			$option_data['cheque'] = [
				'code' => 'cheque.cheque',
				'name' => $this->language->get('heading_title')
			];

			$method_data = [
				'code'       => 'cheque',
				'name'       => $this->language->get('heading_title'),
				'option'     => $option_data,
				'sort_order' => $this->config->get('payment_cheque_sort_order')
			];
		}

		return $method_data;
	}
}
