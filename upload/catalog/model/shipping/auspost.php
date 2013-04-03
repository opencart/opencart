<?php
class ModelShippingAusPost extends Model {
	public function getQuote($address) {
		$this->language->load('shipping/auspost');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('auspost_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!$this->config->get('auspost_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$error = '';
		
		$quote_data = array();
		
		if ($status) {
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('auspost_weight_class_id'));
		
			if ($this->config->get('auspost_standard') && $address['iso_code_2'] == 'AU') {
				$curl = curl_init();
		
				curl_setopt($curl, CURLOPT_URL, 'http://drc.edeliver.com.au/ratecalc.asp?pickup_postcode=' . urlencode($this->config->get('auspost_postcode')) . '&destination_postcode=' . urlencode($address['postcode']) . '&height=70&width=70&length=70&country=AU&service_type=standard&quantity=1&weight=' . urlencode($weight));
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				
				$response = curl_exec($curl);
				
				curl_close($curl);
				
				if ($response) {
					$response_info = array();
					
					$parts = explode("\n", trim($response));
					
					foreach ($parts as $part) {
						list($key, $value) = explode('=', $part);
						
						$response_info[$key] = $value;
					}
					
					if ($response_info['err_msg'] != 'OK') {
						$error = $response_info['err_msg'];
					} else {
						$title = $this->language->get('text_standard');
					
						if ($this->config->get('auspost_display_time')) {
							$title .= ' (' . $response_info['days'] . ' ' . $this->language->get('text_eta') . ')';
						}	
			
						$quote_data['standard'] = array(
							'code'         => 'auspost.standard',
							'title'        => $title,
							'cost'         => $this->currency->convert($response_info['charge'], 'AUD', $this->config->get('config_currency')),
							'tax_class_id' => $this->config->get('auspost_tax_class_id'),
							'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($response_info['charge'], 'AUD', $this->currency->getCode()), $this->config->get('auspost_tax_class_id'), $this->config->get('config_tax')), $this->currency->getCode(), 1.0000000)
						);
					}
				}
			}
	
			if ($this->config->get('auspost_express') && $address['iso_code_2'] == 'AU') {
				$curl = curl_init();
				
				curl_setopt($curl, CURLOPT_URL, 'http://drc.edeliver.com.au/ratecalc.asp?pickup_postcode=' . urlencode($this->config->get('auspost_postcode')) . '&destination_postcode=' . urlencode($address['postcode']) . '&height=70&width=70&length=70&country=AU&service_type=express&quantity=1&weight=' . urlencode($weight));
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				
				$response = curl_exec($curl);
				
				curl_close($curl); 
				
				if ($response) {
					$response_info = array();
					
					$parts = explode("\n", trim($response));
					
					foreach ($parts as $part) {
						list($key, $value) = explode('=', $part);
						
						$response_info[$key] = $value;
					}
								
					if ($response_info['err_msg'] != 'OK') {
						$error = $response_info['err_msg'];
					} else {
						$title = $this->language->get('text_express');
						
						if ($this->config->get('auspost_display_time')) {
							$title .= ' (' . $response_info['days'] . ' ' . $this->language->get('text_eta') . ')';
						}	
		
						$quote_data['express'] = array(
							'code'         => 'auspost.express',
							'title'        => $title,
							'cost'         => $this->currency->convert($response_info['charge'], 'AUD', $this->config->get('config_currency')),
							'tax_class_id' => $this->config->get('auspost_tax_class_id'),
							'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($response_info['charge'], 'AUD', $this->currency->getCode()), $this->config->get('auspost_tax_class_id'), $this->config->get('config_tax')), $this->currency->getCode(), 1.0000000)
						);
					}
				}
			}
		}
		
		$method_data = array();
		
		if ($quote_data) {
			$method_data = array(
				'code'       => 'auspost',
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