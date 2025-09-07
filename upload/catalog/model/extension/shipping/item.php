<?php
class ModelExtensionShippingItem extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/item');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_item_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_item_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$items = 0;

			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$items += $product['quantity'];
				}
			}

			$quote_data = array();

			$quote_data['item'] = array(
				'code'         => 'item.item',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('shipping_item_cost') * $items,
				'tax_class_id' => $this->config->get('shipping_item_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('shipping_item_cost') * $items, $this->config->get('shipping_item_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'item',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_item_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}
