<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Shipping;
class Pickup extends \Opencart\System\Engine\Model {
	function getQuote(array $address): array {
		$this->load->language('extension/opencart/shipping/pickup');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('shipping_pickup_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

		if (!$this->config->get('shipping_pickup_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = [];

		if ($status) {
			$quote_data = [];

			$quote_data['pickup'] = [
				'code'         => 'pickup.pickup',
				'title'        => $this->language->get('text_description'),
				'cost'         => 0.00,
				'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00, $this->session->data['currency'])
			];

			$method_data = [
				'code'       => 'pickup',
				'title'      => $this->language->get('heading_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_pickup_sort_order'),
				'error'      => false
			];
		}

		return $method_data;
	}
}
