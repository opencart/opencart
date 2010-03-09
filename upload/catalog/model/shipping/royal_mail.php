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

		$method_data = array();
	
		if ($status) {
			$cost = 0;
			$compensation = 0;
			$weight = $this->cart->getWeight();
			$sub_total = $this->cart->getSubTotal();

			$rates = explode(',', $this->config->get('royal_mail_rate'));
			
			foreach ($rates as $rate) {
  				$data = explode(':', $rate);
  					
				if ($data[0] >= $weight) {
					if (isset($data[1])) {
    					$cost = $data[1];
					}
					
   					break;
  				}
			}

			$rates = explode(',', $this->config->get('royal_mail_compensation'));
			
			foreach ($rates as $rate) {
  				$data = explode(':', $rate);
  				
				if ($data[0] >= $sub_total) {
					if (isset($data[1])) {
    					$compensation = $data[1];
					}
					
   					break;
  				}
			}
			
			$quote_data = array();
			
			if ((float)$cost) {
				$text = $this->language->get('text_description') . ' : ';
			
				if ($this->config->get('royal_mail_display_weight')) {
					$text .= ' Weight ' . $this->weight->format($weight, $this->config->get('config_weight_class_id'));
				}
			
				if ($this->config->get('royal_mail_display_insurance') && (int)$compensation) {
					$text .= ' (' . sprintf($this->language->get('text_insurance'), $this->currency->format($compensation));
				}		

				if ($this->config->get('royal_mail_display_time')) {
					$text .= ' ' . $this->language->get('text_time');
				}	
				
				if ($this->config->get('royal_mail_1st_class_standard')) {
      				$quote_data['1st_class_standard'] = array(
        				'id'           => 'royal_mail.1st_class_standard',
        				'title'        => $this->language->get('text_1st_class_standard'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
				 
				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}

				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}

				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}

				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}

				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}

				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}

				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}

				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}

				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
				
				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
				
				if ($this->config->get('royal_mail_1st_class_standard')) {
					$quote_data['royal_mail'] = array(
						'id'           => 'royal_mail.royal_mail',
						'title'        => $this->language->get('text_1st_class_standard'),
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')))
					);
				}
				
      			$method_data = array(
        			'id'         => 'royal_mail',
        			'title'      => $this->language->get('text_title'),
        			'quote'      => $quote_data,
					'sort_order' => $this->config->get('royal_mail_sort_order'),
        			'error'      => FALSE
      			);
			}
		}
	
		return $method_data;
	}
}
?>