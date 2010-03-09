<?php
class ModelShippingRoyalMail extends Model {
	function getQuote($address) {
		$this->load->language('shipping/royal_mail');
		
		if ($this->config->get('royal_mail_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('royal_mail_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
      		if (!$this->config->get('royal_mail_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}

		$quote_data = array();
	
		if ($status) {
			$weight = $this->cart->getWeight();
			$sub_total = $this->cart->getSubTotal();
			
			if ($this->config->get('royal_mail_1st_class_standard') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$compensation = 0;
				
				$rates = explode(',', '.1:1.28,.25:1.62,.5:2.14,.75:2.65,1:3.25,1.25:4.45,1.5:5.15,1.75:5.85,2:6.55,4:8.22,6:11.02,8:13.82,10:16.62');
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}
				
						break;
					}
				}
				
				$rates = explode(',', '39:0,100:1,250:2.25,500:3.5');
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$compensation = $data[1];
						}
				
						break;
					}
				}
				
				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_standard');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$compensation) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($compensation) . ')';
					}		
		
					if ($this->config->get('royal_mail_display_time')) {
						$title .= ' (' . $this->language->get('text_eta') . ')';
					}	
					
					$quote_data['1st_class_standard'] = array(
						'id'           => 'royal_mail.1st_class_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			 
			if ($this->config->get('royal_mail_1st_class_recorded') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$compensation = 0;
				
				$rates = explode(',', '.1:2.03,.25:2.37,.5:2.89,.75:3.4,1:4,1.25:5.2,1.5:5.9,1.75:6.6,2:7.3,4:8.97,6:11.77,8:14.57,10:17.37');

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
							$compensation = $data[1];
						}
				
						break;
					}
				}
				
				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_recorded');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$compensation) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($compensation) . ')';
					}		
		
					if ($this->config->get('royal_mail_display_time')) {
						$title .= ' (' . $this->language->get('text_eta') . ')';
					}	
						
					$quote_data['1st_class_recorded'] = array(
						'id'           => 'royal_mail.1st_class_recorded',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}

			if ($this->config->get('royal_mail_2nd_class_standard') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				
				$rates = explode(',', '.1:1.08,.25:1.41,.5:1.85,.75:2.24,1:2.7');

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
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
					}
		
					if ($this->config->get('royal_mail_display_time')) {
						$title .= ' (' . $this->language->get('text_eta') . ')';
					}					
					
					$quote_data['2nd_class_standard'] = array(
						'id'           => 'royal_mail.2nd_class_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			if ($this->config->get('royal_mail_2nd_class_recorded') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$compensation = 0;
				
				$rates = explode(',', '.1:1.83,.25:2.16,.5:2.6,.75:2.99,1:3.45');

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
							$compensation = $data[1];
						}
				
						break;
					}
				}
				
				if ((float)$cost) {
					$title = $this->language->get('text_2nd_class_recorded');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$compensation) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($compensation) . ')';
					}		
		
					if ($this->config->get('royal_mail_display_time')) {
						$title .= ' (' . $this->language->get('text_eta') . ')';
					}						
					
					$quote_data['2nd_class_recorded'] = array(
						'id'           => 'royal_mail.2nd_class_recorded',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			if ($this->config->get('royal_mail_standard_parcels') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$compensation = 0;
				
				$rates = explode(',', '2:4.41,4:7.06,6:9.58,8:11.74,10:12.61,20:14.69');

				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}
				
						break;
					}
				}
				
				$rates = explode(',', '39:0,100:1,250:2.25,500:3.5');
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$compensation = $data[1];
						}
				
						break;
					}
				}				
				
				if ((float)$cost) {
					$title = $this->language->get('text_standard_parcels');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$compensation) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($compensation) . ')';
					}		
		
					if ($this->config->get('royal_mail_display_time')) {
						$title .= ' (' . $this->language->get('text_eta') . ')';
					}						
									
					$quote_data['standard_parcels'] = array(
						'id'           => 'royal_mail.standard_parcels',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			if ($this->config->get('royal_mail_airmail')) {
				$cost = 0;
				
				$countries = explode(',', 'AL,AD,AM,AT,AZ,BY,BE,BA,BG,HR,CY,CZ,DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KG,LV,LI,LT,LU,MK,MT,MD,MC,NL,NO,PL,PT,RO,RU,SM,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', '.1:1.21,.12:1.31,.14:1.45,.16:1.57,.18:1.7,.2:1.82,.22:1.95,.24:2.06,.26:2.18,.28:2.31,.3:2.44,.34:2.66,.38:2.88,.42:3.1,.46:3.32,.5:3.54,.56:3.87,.62:4.2,.68:4.53,.74:4.86,.8:5.19,.9:5.74,1:6.29,1.2:7.29,1.4:8.29,1.6:9.29,1.8:10.29,2:11.29');
				} else {
					$rates = explode(',', '.1:1.68,.12:1.93,.14:2.19,.16:2.44,.18:2.7,.2:2.94,.22:3.18,.24:3.41,.26:3.64,.28:3.88,.3:4.11,.34:4.59,.38:5.07,.42:5.55,.46:6.03,.5:6.51,.56:7.2,.62:7.89,.68:8.58,.74:9.27,.8:9.96,.9:11.11,1:12.26,1.2:14.46,1.4:16.66,1.6:18.86,1.8:21.06,2:23.26');
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
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
					}
		
					if ($this->config->get('royal_mail_display_time')) {
						$title .= ' (' . $this->language->get('text_eta') . ')';
					}
					
					$quote_data['airmail'] = array(
						'id'           => 'royal_mail.airmail',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			if ($this->config->get('royal_mail_international_signed')) {
				$cost = 0;
				$compensation = 0;
				
				$countries = explode(',', 'AL,AD,AM,AT,AZ,BY,BE,BA,BG,HR,CY,CZ,DK,EE,FO,FI,FR,GE,DE,GI,GR,GL,HU,IS,IE,IT,KZ,KG,LV,LI,LT,LU,MK,MT,MD,MC,NL,NO,PL,PT,RO,RU,SM,SK,SI,ES,SE,CH,TJ,TR,TM,UA,UZ,VA');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', '.1:4.91,.12:5.01,.14:5.15,.16:5.27,.18:5.4,.2:5.52,.22:5.65,.24:5.76,.26:5.88,.28:6.01,.3:6.14,.34:6.36,.38:6.58,.42:6.8,.46:7.02,.5:7.24,.56:7.57,.62:7.9,.68:8.23,.74:8.56,.8:8.89,.9:9.44,1:9.99,1.2:10.99,1.4:11.99,1.6:12.99,1.8:13.99,2:14.99');
				} else {
					$rates = explode(',', '.1:5.38,.12:5.63,.14:5.89,.16:6.14,.18:6.4,.2:6.64,.22:6.88,.24:7.11,.26:7.34,.28:7.58,.3:7.81,.34:8.29,.38:8.77,.42:9.25,.46:9.73,.5:10.21,.56:10.9,.62:11.59,.68:12.28,.74:12.97,.8:13.66,.9:14.81,1:15.96,1.2:18.16,1.4:20.36,1.6:22.56,1.8:24.76,2:26.96');
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
					$rates = explode(',', '39:0,250:2.20');
				} else {
					$rates = explode(',', '39:0,250:2.20');
				}
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$compensation = $data[1];
						}
				
						break;
					}
				}				
				
				if ((float)$cost) {
					$title = $this->language->get('text_international_signed');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$compensation) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($compensation) . ')';
					}		
		
					if ($this->config->get('royal_mail_display_time')) {
						$title .= ' (' . $this->language->get('text_eta') . ')';
					}	
					
					$quote_data['international_signed'] = array(
						'id'           => 'royal_mail.international_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			if ($this->config->get('royal_mail_airsure')) {
				$cost = 0;
				$compensation = 0;
				
				$rates = array();
				
				$countries = explode(',', 'AD,AT,BE,CH,DE,DK,ES,FO,FI,FR,IE,IS,LI,LU,MC,NL,PT,SE');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', '.1:5.71,.12:5.81,.14:5.95,.16:6.07,.18:6.2,.2:6.32,.22:6.45,.24:6.56,.26:6.68,.28:6.81,.3:6.94,.34:7.16,.38:7.38,.42:7.6,.46:7.82,.5:8.04,.56:8.37,.62:8.7,.68:9.03,.74:9.36,.8:9.69,.9:10.24,1:10.79,1.2:11.79,1.4:12.79,1.6:13.79,1.8:14.79,2:15.79');
				} 
				
				$countries = explode(',', 'BR,CA,HK,MY,NZ,SG,US');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', '.1:6.18,.12:6.43,.14:6.69,.16:6.94,.18:7.2,.2:7.44,.22:7.68,.24:7.91,.26:8.14,.28:8.38,.3:8.61,.34:9.09,.38:9.57,.42:10.05,.46:10.53,.5:11.01,.56:11.7,.62:12.39,.68:13.08,.74:13.77,.8:14.46,.9:15.61,1:16.76,1.2:18.96,1.4:21.16,1.6:23.36,1.8:25.56,2:27.76');
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
					$rates = explode(',', '39:0,500:2.2');
				} 
				
				$countries = explode(',', 'BR,CA,HK,MY,NZ,SG,US');
				
				if (in_array($address['iso_code_2'], $countries)) {
					$rates = explode(',', '39:0,500:2.2');
				}				
				
				foreach ($rates as $rate) {
					$data = explode(':', $rate);
				
					if ($data[0] >= $sub_total) {
						if (isset($data[1])) {
							$compensation = $data[1];
						}
				
						break;
					}
				}					
				
				if ((float)$cost) {
					$title = $this->language->get('text_airsure');
					
					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$compensation) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($compensation) . ')';
					}		
		
					if ($this->config->get('royal_mail_display_time')) {
						$title .= ' (' . $this->language->get('text_eta') . ')';
					}	
					
					$quote_data['airsure'] = array(
						'id'           => 'royal_mail.airsure',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
			}
			
			if ($this->config->get('royal_mail_surface')) {
				$cost = 0;
				$compensation = 0;
				
				$rates = explode(',', '.1:0.91,.15:1.22,.2:1.53,.25:1.84,.3:2.14,.35:2.44,.4:2.76,.45:3.06,.5:3.36,.55:3.67,.6:3.98,.65:4.28,.7:4.59,.75:4.89,.8:5.2,.85:5.5,.9:5.81,1:6.42,1.1:7.03,1.2:7.65,1.3:8.25,1.4:8.87,1.5:9.48,1.6:10.09,1.7:10.61,1.8:11.13,1.9:11.65,2:12.17');

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
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
					}
				
					if ($this->config->get('royal_mail_display_insurance') && (float)$compensation) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format($compensation) . ')';
					}		
		
					if ($this->config->get('royal_mail_display_time')) {
						$title .= ' (' . $this->language->get('text_eta') . ')';
					}	
					
					$quote_data['airsure'] = array(
						'id'           => 'royal_mail.surface',
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
				'id'         => 'royal_mail',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('royal_mail_sort_order'),
				'error'      => FALSE
			);
		}
			
		return $method_data;
	}
}
?>