<?php
class ModelShippingRoyalMail extends Model {
	function getQuote($address) {
		$this->load->language('shipping/royal_mail');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('royal_mail_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('royal_mail_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$quote_data = array();

		if ($status) {
			$weight = $this->cart->getWeight();
			$sub_total = $this->cart->getSubTotal();
			
			// Special Delivery > 500
			if ($this->config->get('royal_mail_special_delivery_500_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_special_delivery_500_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					if ($this->config->get('royal_mail_display_insurance')) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format(500) . ')';
					}

					$quote_data['special_delivery_500'] = array(
						'code'         => 'royal_mail.special_delivery_500',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// Special Delivery > 1000
			if ($this->config->get('royal_mail_special_delivery_1000_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_special_delivery_1000_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					if ($this->config->get('royal_mail_display_insurance')) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format(1000) . ')';
						
					}

					$quote_data['special_delivery_1000'] = array(
						'code'         => 'royal_mail.special_delivery_1000',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// Special Delivery > 2500
			if ($this->config->get('royal_mail_special_delivery_2500_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_special_delivery_2500_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					if ($this->config->get('royal_mail_display_insurance')) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format(2500) . ')';
					}

					$quote_data['special_delivery_2500'] = array(
						'code'         => 'royal_mail.special_delivery_2500',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// 1st Class Signed
			if ($this->config->get('royal_mail_1st_class_signed_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_1st_class_signed_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['1st_class_signed'] = array(
						'code'         => 'royal_mail.1st_class_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// 2nd Class Signed
			if ($this->config->get('royal_mail_2nd_class_signed_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_2nd_class_signed_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_2nd_class_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['2nd_class_signed'] = array(
						'code'         => 'royal_mail.2nd_class_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// 1st Class Standard
			if ($this->config->get('royal_mail_1st_class_standard_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_1st_class_standard_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_standard');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['1st_class_standard'] = array(
						'code'         => 'royal_mail.1st_class_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// 2nd Class Standard
			if ($this->config->get('royal_mail_2nd_class_standard_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_2nd_class_standard_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_2nd_class_standard');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['2nd_class_standard'] = array(
						'code'         => 'royal_mail.2nd_class_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// International Standard
			if ($this->config->get('royal_mail_international_standard_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;

				$countries = explode(',', 'AL,AD,AM,AT,AZ,BY,BE,BA,BG,HR,CY,CZ,DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KG,LV,LI,LT,LU,MK,MT,MD,MC,NL,NO,PL,PT,RO,RU,SM,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA');

				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_international_standard_rate_1'));
				} else {
					$rates = explode(',', $this->config->get('royal_mail_international_standard_rate_2'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_standard');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_standard'] = array(
						'code'         => 'royal_mail.international_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			// International Tracked & Signed
			if ($this->config->get('royal_mail_international_tracked_signed_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;
				$insurance = 0;

				$countries = explode(',', 'AL,AD,AM,AT,AZ,BY,BE,BA,BG,HR,CY,CZ,DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KG,LV,LI,LT,LU,MK,MT,MD,MC,NL,NO,PL,PT,RO,RU,SM,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA');

				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_signed_rate_1'));
				} else {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_signed_rate_2'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_tracked_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_tracked_signed'] = array(
						'code'         => 'royal_mail.international_tracked_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// International Tracked
			if ($this->config->get('royal_mail_international_tracked_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = array();

				$countries = explode(',', 'AD,AT,BE,CH,DE,DK,ES,FO,FI,FR,IE,IS,LI,LU,MC,NL,PT,SE');

				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_rate_1'));
				}

				$countries = explode(',', 'BR,CA,HK,MY,NZ,SG,US');
 
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_rate_2'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_tracked');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_tracked'] = array(
						'code'         => 'royal_mail.international_tracked',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			// International Signed
			if ($this->config->get('royal_mail_international_signed_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;
				$insurance = 0;

				$countries = explode(',', 'AL,AD,AM,AT,AZ,BY,BE,BA,BG,HR,CY,CZ,DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KG,LV,LI,LT,LU,MK,MT,MD,MC,NL,NO,PL,PT,RO,RU,SM,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA');

				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_rate_1'));
				} else {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_rate_2'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_signed'] = array(
						'code'         => 'royal_mail.international_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			// Economy
			if ($this->config->get('royal_mail_international_economy_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_international_economy_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_economy');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['surface'] = array(
						'code'         => 'royal_mail.international_economy',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
		}

		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'royal_mail',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('royal_mail_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}