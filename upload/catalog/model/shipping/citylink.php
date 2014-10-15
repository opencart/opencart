<?php
class ModelShippingCitylink extends Model {
	function getQuote($address) {
		$this->load->language('shipping/citylink');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('citylink_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('citylink_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$cost = 0;
			$weight = $this->cart->getWeight();

			$rates = explode(',', $this->config->get('citylink_rate'));

			foreach ($rates as $rate) {
				$data = explode(':', $rate);

				if ($data[0] >= $weight) {
					if (isset($data[1])) {
						$cost = $data[1];
					}

					break;
				}
			}

			$quote_data = array();

			if ((float)$cost) {
				$quote_data['citylink'] = array(
					'code'         => 'citylink.citylink',
					'title'        => $this->language->get('text_title') . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
					'cost'         => $cost,
					'tax_class_id' => $this->config->get('citylink_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('citylink_tax_class_id'), $this->config->get('config_tax')))
				);

				$method_data = array(
					'code'       => 'citylink',
					'title'      => $this->language->get('text_title'),
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('citylink_sort_order'),
					'error'      => false
				);
			}
		}

		return $method_data;
	}
}