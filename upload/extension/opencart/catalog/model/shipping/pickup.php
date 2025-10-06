<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Shipping;
/**
 * Class Pickup
 *
 * Can be called from $this->load->model('extension/opencart/shipping/pickup');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Shipping
 */
class Pickup extends \Opencart\System\Engine\Model {
	/**
	 * Get Quote
	 *
	 * @param array<string, mixed> $address array of data
	 *
	 * @return array<string, mixed>
	 */
	public function getQuote(array $address): array {
		$this->load->language('extension/opencart/shipping/pickup');

		// Geo Zone
		$this->load->model('localisation/geo_zone');

		$results = $this->model_localisation_geo_zone->getGeoZone((int)$this->config->get('shipping_pickup_geo_zone_id'), (int)$address['country_id'], (int)$address['zone_id']);

		if (!$this->config->get('shipping_pickup_geo_zone_id')) {
			$status = true;
		} elseif ($results) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = [];

		if ($status) {
			$quote_data = [];

			$quote_data['pickup'] = [
				'code'         => 'pickup.pickup',
				'name'         => $this->language->get('text_description'),
				'cost'         => 0.00,
				'tax_class_id' => 0,
				'text'         => 0.00
			];

			$method_data = [
				'code'       => 'pickup',
				'name'       => $this->language->get('heading_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_pickup_sort_order'),
				'error'      => false
			];
		}

		return $method_data;
	}
}
