<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Shipping;
/**
 * Class Flat
 *
 * Can be called from $this->load->model('extension/opencart/shipping/flat');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Shipping
 */
class Flat extends \Opencart\System\Engine\Model {
	/**
	 * Get Quote
	 *
	 * @param array<string, mixed> $address array of data
	 *
	 * @return array<string, mixed>
	 */
	public function getQuote(array $address): array {
		$this->load->language('extension/opencart/shipping/flat');

		// Geo Zone
		$this->load->model('localisation/geo_zone');

		$results = $this->model_localisation_geo_zone->getGeoZone((int)$this->config->get('shipping_flat_geo_zone_id'), (int)$address['country_id'], (int)$address['zone_id']);

		if (!$this->config->get('shipping_flat_geo_zone_id')) {
			$status = true;
		} elseif ($results) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = [];

		if ($status) {
			$quote_data = [];

			$quote_data['flat'] = [
				'code'         => 'flat.flat',
				'name'         => $this->language->get('text_description'),
				'cost'         => (float)$this->config->get('shipping_flat_cost'),
				'tax_class_id' => $this->config->get('shipping_flat_tax_class_id'),
				'total'        => $this->tax->calculate($this->config->get('shipping_flat_cost'), $this->config->get('shipping_flat_tax_class_id'), $this->config->get('config_tax'))
			];

			$method_data = [
				'code'       => 'flat',
				'name'       => $this->language->get('heading_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_flat_sort_order'),
				'error'      => false
			];
		}

		return $method_data;
	}
}
