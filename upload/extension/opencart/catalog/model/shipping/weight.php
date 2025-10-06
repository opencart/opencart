<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Shipping;
/**
 * Class Weight
 *
 * Can be called from $this->load->model('extension/opencart/shipping/weight');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Shipping
 */
class Weight extends \Opencart\System\Engine\Model {
	/**
	 * Get Quote
	 *
	 * @param array<string, mixed> $address array of data
	 *
	 * @return array<string, mixed>
	 */
	public function getQuote(array $address): array {
		$this->load->language('extension/opencart/shipping/weight');

		$quote_data = [];

		// Geo Zone
		$this->load->model('localisation/geo_zone');

		$results = $this->model_localisation_geo_zone->getGeoZones();

		$weight = $this->cart->getWeight();

		foreach ($results as $result) {
			if ($this->config->get('shipping_weight_' . $result['geo_zone_id'] . '_status')) {
				$results = $this->model_localisation_geo_zone->getGeoZone($result['geo_zone_id'], $address['country_id'], $address['zone_id']);

				if ($results) {
					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}

			if ($status) {
				$cost = '';

				$rates = explode(',', $this->config->get('shipping_weight_' . $result['geo_zone_id'] . '_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}
						break;
					}
				}

				if ((string)$cost != '') {
					$quote_data['weight_' . $result['geo_zone_id']] = [
						'code'         => 'weight.weight_' . $result['geo_zone_id'],
						'name'         => $result['name'] . ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('shipping_weight_tax_class_id'),
						'text'         => $this->tax->calculate((float)$cost, $this->config->get('shipping_weight_tax_class_id'), $this->config->get('config_tax'))
					];
				}
			}
		}

		$method_data = [];

		if ($quote_data) {
			$method_data = [
				'code'       => 'weight',
				'name'       => $this->language->get('heading_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_weight_sort_order'),
				'error'      => false
			];
		}

		return $method_data;
	}
}
