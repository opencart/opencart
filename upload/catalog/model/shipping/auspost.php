<?php
class ModelShippingAuspost extends Model {
	public function getQuote($address) {
		$this->load->language('shipping/auspost');

		if ($this->config->get('auspost_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('auspost_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
      		if (!$this->config->get('auspost_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}
		
		$weight = intval($this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), 2));
		
		$method_data = array();
		
		if ($this->config->get('auspost_status') && ($this->config->get('auspost_standard') || $this->config->get('auspost_express')) && $address['iso_code_2'] == 'AU') {
			$quote_data = array();
		
			$error = FALSE; 
		
			if (!preg_match('/^[0-9]{4}$/', $address['postcode'])) {
				$error = 'Your postcode is not valid in Australia';
			} else {
				if ($this->config->get('auspost_standard')) {
					$ch = curl_init();
					
					curl_setopt($ch, CURLOPT_URL, 'http://drc.edeliver.com.au/ratecalc.asp?pickup_postcode=' . $this->config->get('auspost_postcode') . '&destination_postcode=' . $address['postcode'] . '&height=70&width=70&length=70&country=AU&service_type=standard&quantity=1&weight=' . $weight);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
					$get_standard = curl_exec($ch);
					
					curl_close($ch); 
				
					if (strstr($get_standard, 'err_msg=OK') == FALSE) {
						$error = 'Error interfacing with Australia Post (connection)';
					} else {
						$get_standard_charge = preg_match('/^charge=([0-9]{1,3}\.?[0-9]{0,2})/', $get_standard, $post_charge_standard);
		
						if (!isset($post_charge_standard[1])) {
							$error = 'Error interfacing with Australia Post (charge)';
						} else {
							$post_charge_standard = sprintf('%.2f', $post_charge_standard[1]);
		
							if (preg_match('/^[0-9]{1,2}\.[0-9]{2,2}$/', $this->config->get('auspost_handling')) && $this->config->get('auspost_handling') > 0) {
								$post_charge_standard = sprintf('%.2f', $post_charge_standard + $this->config->get('auspost_handling'));
							}
		
							$get_days_standard = preg_match('/days=([0-9]{1,2})/', $get_standard, $post_days_standard);
		
							$post_days_standard_append = '';
		
							if ($this->config->get('auspost_display_estimate') && isset($post_days_standard[1])) {
								if (is_numeric($post_days_standard[1])) {
									if($post_days_standard[1] == 1) {
										$post_days_standard_append = ' (est. ' . $post_days_standard[1] . ' day delivery)';
									} else {
										$post_days_standard_append = ' (est. ' . $post_days_standard[1] . ' days delivery)';
									}
								}
							}
		
							$quote_data['auspost_standard'] = array(
								'id'           => 'auspost.auspost_standard',
								'title'        => $this->language->get('text_standard') . $post_days_standard_append,
								'cost'         => $post_charge_standard,
								'tax_class_id' => 0,
								'text'         => '$' . $post_charge_standard
							);
						}
					}
				}
		
				if ($this->config->get('auspost_express') && $error == FALSE) {
					$ch = curl_init();
					
					curl_setopt($ch, CURLOPT_URL, 'http://drc.edeliver.com.au/ratecalc.asp?pickup_postcode=' . $this->config->get('auspost_postcode') . '&destination_postcode=' . $address['postcode'] . '&height=70&width=70&length=70&country=AU&service_type=express&quantity=1&weight=' . $weight);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
					$get_express = curl_exec($ch);
					
					curl_close($ch); 
					
					if (strstr($get_express, 'err_msg=OK') == FALSE) {
						$error = 'Error interfacing with Australia Post';
					} else {
						$get_express_charge = preg_match('/^charge=([0-9]{1,3}\.?[0-9]{0,2})/', $get_express, $post_charge_express);
		
						if (!isset($post_charge_express[1])) {
							$error = 'Error interfacing with Australia Post (charge)';
						} else {
							$post_charge_express = sprintf('%.2f', $post_charge_express[1]);
		
							if (preg_match('/^[0-9]{1,2}\.[0-9]{2,2}$/', $this->config->get('auspost_handling')) && $this->config->get('auspost_handling') > 0) {
								$post_charge_express = sprintf('%.2f', $post_charge_express + $this->config->get('auspost_handling'));
							}
		
							$get_days_express = preg_match('/days=([0-9]{1,2})/', $get_express, $post_days_express);
							$post_days_express_append = '';
		
							if ($this->config->get('auspost_display_estimate') && isset($post_days_express[1])) {
								if (is_numeric($post_days_express[1])) {
									if ($post_days_express[1] == 1) {
										$post_days_express_append = ' (est. ' . $post_days_express[1] . ' day delivery)';
									} else {
										$post_days_express_append = ' (est. ' . $post_days_express[1] . ' days delivery)';
									}
								}
							}
		
							$quote_data['auspost_express'] = array(
								'id'           => 'auspost.auspost_express',
								'title'        => $this->language->get('text_express') . $post_days_express_append,
								'cost'         => $post_charge_express,
								'tax_class_id' => 0,
								'text'         => '$' . $post_charge_express
							);
						}
					}
				}
			}
		
			$method_data = array(
				'id'         => 'auspost_express',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('auspost_sort_order'),
				'error'      => $error 
			);
		}
		
		return $method_data;
	}
}
?>