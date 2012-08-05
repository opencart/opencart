<?php
class ModelShippingFedex extends Model {
	function getQuote($address) {
		$this->load->language('shipping/fedex');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('fedex_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
	
		if (!$this->config->get('fedex_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	
		
		$error = '';
		
		$quote_data = array();
		
		if ($status) {
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('ups_weight_class_id'));
			$weight_code = strtoupper($this->weight->getUnit($this->config->get('ups_weight_class_id')));
	
			if ($weight_code == 'KG') {
				$weight_code = 'KGS';
			} elseif ($weight_code == 'LB') {
				$weight_code = 'LBS';
			}
			
			$date = time();
			
			$day = date('l', $date);
			
			if ($day == 'Saturday') {
				$date += 172800;
			} elseif ($day == 'Sunday') {
				$date += 86400;
			}
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
					
			if ($this->config->get('fedex_test')) {
				$url = 'https://gateway.fedex.com/GatewayDC';
			} else {
				$url = 'https://gatewaybeta.fedex.com/GatewayDC';
			}
			
			$data = array(
				'WebAuthenticationDetail' => array (
					'UserCredential' => array(
						'Key' 		=> $this->config->get('fedex_key'),
						'Password'	=> $this->config->get('fedex_password')
					)
				),
				'ClientDetail' => array(
					'AccountNumber'	=> $this->config->get('fedex_account'),
					'MeterNumber'	=> $this->config->get('fedex_meter')
				),
				'Version' => array(
					'ServiceId'		=> 'crs',
					'Major' 		=> '7',
					'Intermediate'	=> '0',
					'Minor' 		=> '0'
				),
				'ReturnTransitAndCommit' => true,
				'RequestedShipment' => array(
					'Shipper' => array(
						'Address' => array(
							'StreetLines'         => array('1755 Purina Way'), // Origin details
							'City'                => 'Sparks',
							'StateOrProvinceCode' => 'NV',						
							'CountryCode'         => $country_info['iso_code_2'],
							'PostalCode'          => $this->config->get('fedex_postcode')
						)
					),
					'Recipient' => array(
						'Address' => array(
							'StreetLines'         => array('1755 Purina Way'), // Origin details
							'City'                => 'Sparks',
							'StateOrProvinceCode' => 'NV',						
							'CountryCode'	      => $address['iso_code_2'],
							'PostalCode'	      => $address['postcode'],
							'Residential'	      => ($this->config->get('destination_type') == 'residential'),
						)
					),
					'ShippingChargesPayment' => array(
						'PaymentType' => 'SENDER'
					),
					'RateRequestTypes' 	        => $this->config->get('fedex_rate_type'),
					'PackageCount'		        => 1,
					'PackageDetail'		        => 'INDIVIDUAL_PACKAGES',
					'PackagingType'		        => $this->config->get('fedex_packaging_type'),
					'DropoffType'		        => $this->config->get('fedex_dropoff_type'),
					'ShipTimestamp'		        => date('c', $date),
					'RequestedPackageLineItems' => array(
						'Weight' => array(
							'Units' => $weight_code,
							'Value' => $weight
						)
					),
				)
			);
			
			//$client = new nusoap_client(DIR_APPLICATION . 'model/shipping/fedex_rates_v7.wsdl', 'wsdl');
			//$result = $client->call('getRates', $data);
			
			foreach ($response->RateReplyDetails as $result) {
				
			}     			
		}
		
		$method_data = array();
		
		if ($quote_data) {
			$method_data = array(
				'code'       => 'fedex',
				'title'      => $title,
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('fedex_sort_order'),
				'error'      => $error_msg
			);
		}
	
		return $method_data;
	}
}		
?>