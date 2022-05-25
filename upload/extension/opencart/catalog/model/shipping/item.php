<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Shipping;
class Item extends \Opencart\System\Engine\Model {
	function getQuote(array $address): array {
		$this->load->language('extension/opencart/shipping/item');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('shipping_item_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

		if (!$this->config->get('shipping_item_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
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
				'title'        => $this->language->get('text_description'),
				'cost'         => $cost * $items,
				'tax_class_id' => $tax_class_id,
				'text'         => $this->currency->format($this->tax->calculate($cost * $items, $tax_class_id, $this->config->get('config_tax')), $this->session->data['currency'])
			];

			$method_data = [
				'code'       => 'item',
				'title'      => $this->language->get('heading_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_item_sort_order'),
				'error'      => false
			];
		}

		return $method_data;
	}
}
