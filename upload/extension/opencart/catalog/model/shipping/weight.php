<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Shipping;
/**
 * Class Weight
 *
 * @package
 */
class Weight extends \Opencart\System\Engine\Model {
	/**
	 * @param array $address
	 *
	 * @return array
	 */
	public function getQuote(array $address): array {
		$this->load->language('extension/opencart/shipping/weight');

		$quote_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "geo_zone` ORDER BY `name`");

		$weight = $this->cart->getWeight();

		foreach ($query->rows as $result) {
			if ($this->config->get('shipping_weight_' . $result['geo_zone_id'] . '_status')) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$result['geo_zone_id'] . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

				if ($query->num_rows) {
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
						'name'         => $result['name'] . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('shipping_weight_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('shipping_weight_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
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
