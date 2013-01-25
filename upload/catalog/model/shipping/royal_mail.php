<?php
class ModelShippingRoyalMail extends Model {
	function getQuote($address) {
		$this->language->load('shipping/royal_mail');
		
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
				
				$rates = explode(',', $this->config->get('royal_mail_1st_class_standard_insurance'));
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$insurance = $data[1];
						}
				
						break;
					}
				}
				
				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_standard');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
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
			
			// 1st Class Recorded	 
			if ($this->config->get('royal_mail_1st_class_recorded_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;
				
				$rates = explode(',', $this->config->get('royal_mail_1st_class_recorded_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}
				
						break;
					}
				}
				
				$rates = explode(',', $this->config->get('royal_mail_1st_class_recorded_insurance'));
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$insurance = $data[1];
						}
				
						break;
					}
				}
				
				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_recorded');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
					}		
		
					$quote_data['1st_class_recorded'] = array(
						'code'         => 'royal_mail.1st_class_recorded',
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
			
			// 2nd Class Recorded
			if ($this->config->get('royal_mail_2nd_class_recorded_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;
				
				$rates = explode(',', $this->config->get('royal_mail_2nd_class_recorded_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}
				
						break;
					}
				}
				
				$rates = explode(',', '39:0');
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$insurance = $data[1];
						}
				
						break;
					}
				}
				
				if ((float)$cost) {
					$title = $this->language->get('text_2nd_class_recorded');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
					}		
					
					$quote_data['2nd_class_recorded'] = array(
						'code'         => 'royal_mail.2nd_class_recorded',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
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
				
				$rates = explode(',', $this->config->get('royal_mail_special_delivery_500_insurance'));
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$insurance = $data[1];
						}
				
						break;
					}
				}
				
				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery_500');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
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
				
				$rates = explode(',', $this->config->get('royal_mail_special_delivery_1000_insurance'));
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$insurance = $data[1];
						}
				
						break;
					}
				}
				
				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery_1000');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
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
				
				$rates = explode(',', $this->config->get('royal_mail_special_delivery_2500_insurance'));
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$insurance = $data[1];
						}
				
						break;
					}
				}
				
				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery_2500');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
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
						
			// Standard Parcels
			if ($this->config->get('royal_mail_standard_parcels_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;
				
				$rates = explode(',', $this->config->get('royal_mail_standard_parcels_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}
				
						break;
					}
				}
				
				$rates = explode(',', $this->config->get('royal_mail_standard_parcels_insurance'));
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$insurance = $data[1];
						}
				
						break;
					}
				}				
				
				if ((float)$cost) {
					$title = $this->language->get('text_standard_parcels');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
					}		
									
					$quote_data['standard_parcels'] = array(
						'code'         => 'royal_mail.standard_parcels',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			// Airmail
			if ($this->config->get('royal_mail_airmail_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;
				
				$countries = explode(',', 'AL,AD,AM,AT,AZ,BY,BE,BA,BG,HR,CY,CZ,DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KG,LV,LI,LT,LU,MK,MT,MD,MC,NL,NO,PL,PT,RO,RU,SM,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_airmail_rate_1'));
				} else {
					$rates = explode(',', $this->config->get('royal_mail_airmail_rate_2'));
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
					$title = $this->language->get('text_airmail');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
					
					$quote_data['airmail'] = array(
						'code'         => 'royal_mail.airmail',
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
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_insurance_1'));
				} else {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_insurance_2'));
				}
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$insurance = $data[1];
						}
				
						break;
					}
				}				
				
				if ((float)$cost) {
					$title = $this->language->get('text_international_signed');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
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
			
			// Airsure
			if ($this->config->get('royal_mail_airsure_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;
				$insurance = 0;
				
				$rates = array();
				
				$countries = explode(',', 'AD,AT,BE,CH,DE,DK,ES,FO,FI,FR,IE,IS,LI,LU,MC,NL,PT,SE');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_airsure_rate_1'));
				} 
				
				$countries = explode(',', 'BR,CA,HK,MY,NZ,SG,US');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_airsure_rate_2'));
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
				
				$rates = array();
				
				$countries = explode(',', 'AD,AT,BE,CH,DE,DK,ES,FO,FI,FR,IE,IS,LI,LU,MC,NL,PT,SE');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_airsure_insurance_1'));
				} 
				
				$countries = explode(',', 'BR,CA,HK,MY,NZ,SG,US');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', $this->config->get('royal_mail_airsure_insurance_2'));
				}				
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$insurance = $data[1];
						}
				
						break;
					}
				}					
				
				if ((float)$cost) {
					$title = $this->language->get('text_airsure');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
					}		
		
					$quote_data['airsure'] = array(
						'code'         => 'royal_mail.airsure',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			// Surface
			if ($this->config->get('royal_mail_surface_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;
				$insurance = 0;
				
				$rates = explode(',', $this->config->get('royal_mail_surface_rate'));

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
					$title = $this->language->get('text_surface');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$insurance) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($insurance) . ')';
					}		
		
					$quote_data['surface'] = array(
						'code'         => 'royal_mail.surface',
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
?>