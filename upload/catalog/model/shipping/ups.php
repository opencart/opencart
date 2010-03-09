<?php
class ModelShippingUps extends Model {
	function getQuote($address) {
		$this->load->language('shipping/ups');
		
		if ($this->config->get('ups_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ups_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
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

		$method_data = array();

		if ($status) {
			$quote_data = array();
			/*
			$this->service_codes = array(
				// US Origin
				'US Origin' => array(
					'01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_01,
					'02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_02,
					'03' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_03,
					'07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_07,
					'08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_08,
					'11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_11,
					'12' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_12,
					'13' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_13,
					'14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_14,
					'54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_54,
					'59' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_59,
					'65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_65
				),
				// Canada Origin
				'Canada Origin' => array(
					'01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_01,
					'02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_02,
					'07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_07,
					'08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_08,
					'11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_11,
					'12' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_12,
					'13' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_13,
					'14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_14,
					'54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_54,
					'65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_65
				),
				// European Union Origin
				'European Union Origin' => array(
					'07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_07,
					'08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_08,
					'11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_11,
					'54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_54,
					'65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_65,
					// next five services Poland domestic only
					'82' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_82,
					'83' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_83,
					'84' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_84,
					'85' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_85,
					'86' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_86
				),
				// Puerto Rico Origin
				'Puerto Rico Origin' => array(
					'01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_01,
					'02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_02,
					'03' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_03,
					'07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_07,
					'08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_08,
					'14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_14,
					'54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_54,
					'65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_65
				),
				// Mexico Origin
				'Mexico Origin' => array(
					'07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_07,
					'08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_08,
					'54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_54,
					'65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_65
				),
				// All other origins
				'All other origins' => array(
					// service code 7 seems to be gone after January 2, 2007
					'07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_07,
					'08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_08,
					'11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_11,
					'54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_54,
					'65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_65
				)
			);
			*/
			$xml  = '<?xml version="1.0"?>';  
			$xml .= '<AccessRequest xml:lang="en-US">';  
			$xml .= '	<AccessLicenseNumber>' . $this->config->get('ups_key') . '</AccessLicenseNumber>';
			$xml .= '	<UserId>' . $this->config->get('ups_username') . '</UserId>';
			$xml .= '	<Password>' . $this->config->get('ups_password') . '</Password>';
			$xml .= '</AccessRequest>';
			$xml .= '<?xml version="1.0"?>';
			$xml .= '<RatingServiceSelectionRequest xml:lang="en-US">';
			$xml .= '	<Request>';  
			$xml .= '		<TransactionReference>'; 
			$xml .= '			<CustomerContext>Bare Bones Rate Request</CustomerContext>';  
			$xml .= '			<XpciVersion>1.0001</XpciVersion>';  
			$xml .= '		</TransactionReference>'; 
			$xml .= '		<RequestAction>Rate</RequestAction>';  
			$xml .= '		<RequestOption>shop</RequestOption>';  
			$xml .= '	</Request>';  
			
			if ($this->config->get('ups_country') == 'US') {
				$xml .= '   <PickupType>';
				$xml .= '       <Code>' . $this->config->get('ups_pickup') . '</Code>';
				$xml .= '   </PickupType>';
				$xml .= '   <CustomerClassification>';
				$xml .= '       <Code>' . $this->config->get('ups_classification') . '</Code>';
				$xml .= '   </CustomerClassification>';		
			}
			
			$xml .= '	<Shipment>';  
			$xml .= '		<Shipper>';  
			
			if ($this->config->get('ups_negotiated_rates')) {
				$xml .= '			<ShipperNumber>1</ShipperNumber>'; 
			}
			
			$xml .= '			<Address>';  
			$xml .= '				<City>' . $this->config->get('ups_city') . '</City>';
			$xml .= '				<StateProvinceCode>'. $this->config->get('ups_state') . '</StateProvinceCode>';
			$xml .= '				<CountryCode>' . $this->config->get('ups_country') . '</CountryCode>';
			$xml .= '				<PostalCode>' . $this->config->get('ups_postcode') . '</PostalCode>';
			$xml .= '			</Address>'; 
			$xml .= '		</Shipper>'; 
			$xml .= '		<ShipTo>'; 
			$xml .= '			<Address>'; 
			$xml .= ' 				<City>' . $address['city'] . '</City>';
			$xml .= '				<StateProvinceCode>' . $address['zone_code'] . '</StateProvinceCode>';
			$xml .= '				<CountryCode>' . $address['iso_code_2'] . '</CountryCode>';
			$xml .= '				<PostalCode>' . $address['postcode'] . '</PostalCode>';
			
			if ($this->config->get('ups_quote_type') == 'residential') {
				 $xml .= '				<ResidentialAddressIndicator />';
			}
			
			$xml .= '			</Address>'; 
			$xml .= '		</ShipTo>';

			$xml .= '		<Package>';
			$xml .= '			<PackagingType>';
			$xml .= '				<Code>' . $this->config->get('ups_packaging') . '</Code>';
			$xml .= '			</PackagingType>';
			/*
			if ($this->dimensions_support > 0 && ($this->item_length[$i] > 0 ) && ($this->item_width[$i] > 0 ) && ($this->item_height[$i] > 0)) {
				$xml .= '           <Dimensions>';
				$xml .= '               <UnitOfMeasurement>';
				$xml .= '                   <Code>' . $this->unit_length . '</Code>';
				$xml .= '              </UnitOfMeasurement>';
				$xml .= '               <Length>' . $this->item_length[$i] . '</Length>';
				$xml .= '               <Width>' . $this->item_width[$i] . '</Width>';
				$xml .= '				<Height>' . $this->item_height[$i] . '</Height>';
				$xml .= '           </Dimensions>';
			}
			*/
			$xml .= '			<PackageWeight>';
			$xml .= '				<UnitOfMeasurement>';
			$xml .= '					<Code>' . $this->weight->getCode($this->config->get('config_weight_id')) . '</Code>';
			$xml .= '				</UnitOfMeasurement>';
			$xml .= '				<Weight>' . $this->cart->getWeight() . '</Weight>';
			$xml .= '			</PackageWeight>';
			/*
			if ($this->insure_package == true) {
				$xml .= '           <PackageServiceOptions>';
				$xml .= '               <InsuredValue>';
				$xml .= '                   <CurrencyCode>' . MODULE_SHIPPING_UPSXML_CURRENCY_CODE . '</CurrencyCode>';
				$xml .= '                   <MonetaryValue>' . $this->item_price[$i] . '</MonetaryValue>';
				$xml .= '               </InsuredValue>';
				$xml .= '           </PackageServiceOptions>';
			}
			*/
			$xml .= '		</Package>';
	
			if ($this->config->get('ups_negotiated_rates')) { 
				$xml .= '		<RateInformation>';
				$xml .= '			<NegotiatedRatesIndicator/>';
				$xml .= '		</RateInformation>';
			}
		
        	
			$xml .= '	</Shipment>';

			if ($this->config->get('ups_country') == 'US') {
				$xml .= '	<CustomerClassification>';
				$xml .= '		<Code>' . $this->config->get('ups_classification') . '</Code>';
				$xml .= '	</CustomerClassification>';
			}
				 
			$xml .= '</RatingServiceSelectionRequest>';

			if (!$this->config->get('ups_test')) {
				$url = 'https://www.ups.com/ups.app/xml/Rate';
			} else {
				$url = 'https://wwwcie.ups.com/ups.app/xml/Rate';
			}
			
			$ch = curl_init($url);  
			
			curl_setopt($ch, CURLOPT_HEADER, 1);  
			curl_setopt($ch, CURLOPT_POST, 1);  
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);  
			
			$result = curl_exec($ch);  
			
			curl_close($ch); 
			/*
			echo '<pre>';
			print_r($result);
			echo '</pre>';
		
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
				
			if ($quote_data) {
      			$method_data = array(
        			'id'         => 'ups',
        			'title'      => $this->language->get('text_title'),
        			'quote'      => $quote_data,
					'sort_order' => $this->config->get('ups_sort_order'),
        			'error'      => FALSE
      			);
			}
*/
		}
		
		return $method_data;
	}
}
?>