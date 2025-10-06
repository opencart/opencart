<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Shipping;
/**
 * Class Item
 *
 * Can be called from $this->load->model('extension/opencart/shipping/item');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Shipping
 */
class Item extends \Opencart\System\Engine\Model {
	/**
	 * Get Quote
	 *
	 * @param array<string, mixed> $address array of data
	 *
	 * @return array<string, mixed>
	 */
	public function getQuote(array $address): array {
		$this->load->language('extension/opencart/shipping/item');

		// Geo Zone
		$this->load->model('localisation/geo_zone');

		$results = $this->model_localisation_geo_zone->getGeoZone((int)$this->config->get('shipping_item_geo_zone_id'), (int)$address['country_id'], (int)$address['zone_id']);

		if (!$this->config->get('shipping_item_geo_zone_id')) {
			$status = true;
		} elseif ($results) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = [];

		if ($status) {
			$items = 0;

			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$items += $product['quantity'];
				}
			}

			$cost = (float)$this->config->get('shipping_item_cost');
			$tax_class_id = (int)$this->config->get('shipping_item_tax_class_id');

			$quote_data = [];

			$quote_data['item'] = [
				'code'         => 'item.item',
				'name'         => $this->language->get('text_description'),
				'cost'         => $cost * $items,
				'tax_class_id' => $tax_class_id,
				'text'         => $this->tax->calculate($cost * $items, $tax_class_id, $this->config->get('config_tax'))
			];

			$method_data = [
				'code'       => 'item',
				'name'       => $this->language->get('heading_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_item_sort_order'),
				'error'      => false
			];
		}

		return $method_data;
	}
}
