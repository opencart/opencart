<?php
class ModelShippingUps extends Model {
	function getQuote($country_id, $zone_id, $postcode = '') {
		$this->load->language('shipping/ups');
		
		if ($this->config->get('ups_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ups_geo_zone_id') . "' AND country_id = '" . (int)$country_id . "' AND (zone_id = '" . (int)$zone_id . "' OR zone_id = '0')");
		
      		if (!$this->config->get('ups_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else { 
			$status = FALSE;
		}

		//$cost = $this->getRate($this->config->get('ups_postcode'), $address['postcode']);

		$method_data = array();

		if ($status) {
			$quote_data = array();
			
			$cost = $this->getRate();
/*				
			if ($this->config->get('ups_1dm')) {
				
				
				
				if ($cost) {
      				$quote_data['ups'] = array(
        				'id'           => 'ups.1dm',
        				'title'        => $this->language->get('text_1dm'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}		
			}
*/				
			if ($quote_data) {
      			$method_data = array(
        			'id'         => 'ups',
        			'title'      => $this->language->get('text_title'),
        			'quote'      => $quote_data,
					'sort_order' => $this->config->get('ups_sort_order'),
        			'error'      => FALSE
      			);
			}

		}
		
		return $method_data;
	}
	
	private function getRate($postcode, $dest_zip, $service, $length, $width, $height, $weight) {
		$xml  = '<?xml version="1.0"?>';  
		$xml .= '<AccessRequest xml:lang="en-US">';  
		$xml .= '	<AccessLicenseNumber>' . $this->license . '</AccessLicenseNumber>';
		$xml .= '	<UserId>' . $this->user . '</UserId>';
		$xml .= '	<Password>' . $this->password . '</Password>';
		$xml .= '</AccessRequest>';
		$xml .= '<?xml version="1.0"?>';
		$xml .= '<RatingServiceSelectionRequest xml:lang="en-US">';
		$xml .= '	<Request>';  
		$xml .= '		<TransactionReference>'; 
		$xml .= '			<CustomerContext>Bare Bones Rate Request</CustomerContext>';  
		$xml .= '			<XpciVersion>1.0001</XpciVersion>';  
		$xml .= '		</TransactionReference>'; 
		$xml .= '		<RequestAction>Rate</RequestAction>';  
		$xml .= '		<RequestOption>Rate</RequestOption>';  
		$xml .= '	</Request>';  
		$xml .= '	<PickupType>';  
		$xml .= '		<Code>01</Code>';  
		$xml .= '	</PickupType>';  
		$xml .= '	<Shipment>';  
		$xml .= '		<Shipper>';  
		$xml .= '			<Address>';  
		$xml .= '				<PostalCode>' . $postcode . '</PostalCode>'; 
		$xml .= '				<CountryCode>US</CountryCode>'; 
		$xml .= '			</Address>'; 
		$xml .= '			<ShipperNumber>' . $this->ShipperNumber . '</ShipperNumber>'; 
		$xml .= '		</Shipper>'; 
		$xml .= '		<ShipTo>'; 
		$xml .= '			<Address>'; 
		$xml .= '				<PostalCode>' . $dest_zip . '</PostalCode>';  
		$xml .= '				<CountryCode>US</CountryCode>'; 
		$xml .= '				<ResidentialAddressIndicator />'; 
		$xml .= '			</Address>'; 
		$xml .= '		</ShipTo>'; 
		$xml .= '		<ShipFrom>'; 
		$xml .= '			<Address>'; 
		$xml .= '				<PostalCode>' . $postcode . '</PostalCode>';  
		$xml .= '				<CountryCode>US</CountryCode>'; 
		$xml .= '			</Address>'; 
		$xml .= '		</ShipFrom>'; 
		$xml .= '		<Service>'; 
		$xml .= '			<Code>' . $service . '</Code>';  
		$xml .= '		</Service>'; 
		$xml .= '		<Package>'; 
		$xml .= '			<PackagingType>'; 
		$xml .= '				<Code>02</Code>'; 
		$xml .= '			</PackagingType>'; 
		$xml .= '			<Dimensions>'; 
		$xml .= '				<UnitOfMeasurement>'; 
		$xml .= '					<Code>IN</Code>'; 
		$xml .= '				</UnitOfMeasurement>'; 
		$xml .= '				<Length>' . $length . '</Length>';  
		$xml .= '				<Width>' . $width . '</Width>'; 
		$xml .= '				<Height>' . $height . '</Height>';  
		$xml .= '			</Dimensions>'; 
		$xml .= '			<PackageWeight>'; 
		$xml .= '				<UnitOfMeasurement>';  
		$xml .= '					<Code>LBS</Code>'; 
		$xml .= '				</UnitOfMeasurement>';  
		$xml .= '				<Weight>' . $weight . '</Weight>';  
		$xml .= '			</PackageWeight>';   
		$xml .= '		</Package>'; 
		$xml .= '	</Shipment>';   
		$xml .= '</RatingServiceSelectionRequest>';  
			
		$ch = curl_init('https://www.ups.com/ups.app/xml/Rate');  
		
		curl_setopt($ch, CURLOPT_HEADER, 1);  
		curl_setopt($ch,CURLOPT_POST, 1);  
		curl_setopt($ch,CURLOPT_TIMEOUT, 60);  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
		
		$result = curl_exec($ch);  
		
		curl_close($ch); 
		
		echo '<pre>';
		print_r($result);
		echo '</pre>';
		/*
		$data = strstr($result, '<?');  
		
		$xml_parser = xml_parser_create();  
		
		xml_parse_into_struct($xml_parser, $data, $vals, $index);  
		xml_parser_free($xml_parser);  
		
		$params = array();  
		
		$level = array();  
		
		foreach ($vals as $xml_elem) {  
			if ($xml_elem['type'] == 'open') {  
				if (array_key_exists('attributes', $xml_elem)) {  
					list($level[$xml_elem['level']], $extra) = array_values($xml_elem['attributes']);  
				} else {
					$level[$xml_elem['level']] = $xml_elem['tag'];
				}
			}
			
			if ($xml_elem['type'] == 'complete') {
				$start_level = 1;
				
				$php_stmt = '$params';
				
				while($start_level < $xml_elem['level']) {
					$php_stmt .= '[$level['.$start_level.']]';
					
					$start_level++;
				}
				
				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				
				eval($php_stmt);
			}
		}  
		
		return $params['RATINGSERVICESELECTIONRESPONSE']['RATEDSHIPMENT']['TOTALCHARGES']['MONETARYVALUE'];  
		*/
	}
}
?>