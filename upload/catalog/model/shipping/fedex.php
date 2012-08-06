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
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('fedex_weight_class_id'));
			$weight_code = strtoupper($this->weight->getUnit($this->config->get('fedex_weight_class_id')));
			
			$date = time();
			
			$day = date('l', $date);
			
			if ($day == 'Saturday') {
				$date += 172800;
			} elseif ($day == 'Sunday') {
				$date += 86400;
			}
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
					
			if (!$this->config->get('fedex_test')) {
				$url = 'https://gateway.fedex.com/web-services/';
			} else {
				$url = 'https://gatewaybeta.fedex.com/web-services/';
			}
			
			// Whoever introduced xml to shipping companies should be flogged
			$xml  = '<?xml version="1.0"?>';
			$xml .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://fedex.com/ws/rate/v10">';
			$xml .= '	<SOAP-ENV:Body>';
			$xml .= '		<ns1:RateRequest>';
			$xml .= '			<ns1:WebAuthenticationDetail>';
			$xml .= '				<ns1:UserCredential>';
			$xml .= '					<ns1:Key>' . $this->config->get('fedex_key') . '</ns1:Key>';
			$xml .= '					<ns1:Password>' . $this->config->get('fedex_password') . '</ns1:Password>';
			$xml .= '				</ns1:UserCredential>';
			$xml .= '			</ns1:WebAuthenticationDetail>';
			$xml .= '			<ns1:ClientDetail>';
			$xml .= '				<ns1:AccountNumber>' . $this->config->get('fedex_account') . '</ns1:AccountNumber>';
			$xml .= '				<ns1:MeterNumber>' . $this->config->get('fedex_meter') . '</ns1:MeterNumber>';
			$xml .= '			</ns1:ClientDetail>';
			$xml .= '			<ns1:Version>';
			$xml .= '				<ns1:ServiceId>crs</ns1:ServiceId>';
			$xml .= '				<ns1:Major>10</ns1:Major>';
			$xml .= '				<ns1:Intermediate>0</ns1:Intermediate>';
			$xml .= '				<ns1:Minor>0</ns1:Minor>';
			$xml .= '			</ns1:Version>';
			$xml .= '			<ns1:ReturnTransitAndCommit>true</ns1:ReturnTransitAndCommit>';
			$xml .= '			<ns1:RequestedShipment>';
			$xml .= '				<ns1:ShipTimestamp>' . date('c', $date) . '</ns1:ShipTimestamp>';
			$xml .= '				<ns1:DropoffType>' . $this->config->get('fedex_dropoff_type') . '</ns1:DropoffType>';						
			$xml .= '				<ns1:Shipper>';
			$xml .= '					<ns1:Address>';
			//$xml .= '						<ns1:StreetLines>10 Fed Ex Pkwy</ns1:StreetLines>';
			//$xml .= '						<ns1:City>Memphis</ns1:City>';
			//$xml .= '						<ns1:StateOrProvinceCode>TN</ns1:StateOrProvinceCode>';
			$xml .= '						<ns1:PostalCode>' . $this->config->get('fedex_postcode') . '</ns1:PostalCode>';
			$xml .= '						<ns1:CountryCode>' . $country_info['iso_code_2'] . '</ns1:CountryCode>';
			$xml .= '					</ns1:Address>';
			$xml .= '				</ns1:Shipper>';
			
			$xml .= '				<ns1:Recipient>';
			$xml .= '					<ns1:Address>';
			//$xml .= '						<ns1:StreetLines>13450 Farmcrest Ct</ns1:StreetLines>';
			//$xml .= '						<ns1:City>Herndon</ns1:City>';
			//$xml .= '						<ns1:StateOrProvinceCode>VA</ns1:StateOrProvinceCode>';
			$xml .= '						<ns1:PostalCode>' . $address['postcode'] . '</ns1:PostalCode>';
			$xml .= '						<ns1:CountryCode>' . $address['iso_code_2'] . '</ns1:CountryCode>';
			$xml .= '					</ns1:Address>';
			$xml .= '				</ns1:Recipient>';
			$xml .= '				<ns1:ShippingChargesPayment>';
			$xml .= '					<ns1:PaymentType>SENDER</ns1:PaymentType>';
			$xml .= '					<ns1:Payor>';
            $xml .= '						<ns1:AccountNumber>' . $this->config->get('fedex_account') . '</ns1:AccountNumber>';
            $xml .= '						<ns1:CountryCode>' . $country_info['iso_code_2'] . '</ns1:CountryCode>';
          	$xml .= '					</ns1:Payor>';
			$xml .= '				</ns1:ShippingChargesPayment>';
			$xml .= '				<ns1:RateRequestTypes>' . $this->config->get('fedex_rate_type') . '</ns1:RateRequestTypes>';
			$xml .= '				<ns1:PackageCount>1</ns1:PackageCount>';
			$xml .= '				<ns1:RequestedPackageLineItems>';
			$xml .= '					<ns1:SequenceNumber>1</ns1:SequenceNumber>';
        	$xml .= '					<ns1:GroupPackageCount>1</ns1:GroupPackageCount>';
			$xml .= '					<ns1:Weight>';
			$xml .= '						<ns1:Units>' . $weight_code . '</ns1:Units>';
			$xml .= '						<ns1:Value>' . $weight . '</ns1:Value>';
			$xml .= '					</ns1:Weight>';
			$xml .= '					<ns1:Dimensions>';
			$xml .= '						<ns1:Length>20</ns1:Length>';
			$xml .= '						<ns1:Width>20</ns1:Width>';
			$xml .= '						<ns1:Height>10</ns1:Height>';
			$xml .= '						<ns1:Units>IN</ns1:Units>';
			$xml .= '					</ns1:Dimensions>';
			$xml .= '				</ns1:RequestedPackageLineItems>';
			$xml .= '			</ns1:RequestedShipment>';
			$xml .= '		</ns1:RateRequest>';
			$xml .= '	</SOAP-ENV:Body>';
			$xml .= '</SOAP-ENV:Envelope>';		
						
			$curl = curl_init($url);  
			
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
			
			$response = curl_exec($curl);  
			
			curl_close($curl); 
			
			$this->log->write($response);
			
			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->loadXml($response);	
	
			if ($dom->getElementsByTagName('HighestSeverity')->item(0)->nodeValue != 'FAILURE' || $dom->getElementsByTagName('HighestSeverity')->item(0)->nodeValue != 'ERROR') {
				$error = $dom->getElementsByTagName('HighestSeverity')->item(0)->nodeValue;
			} else {
				$rates = $dom->getElementsByTagName('RateReplyDetails');
				
				foreach ($rates as $rate) { 
					$code = strtolower($rate->getElementsByTagName('ServiceType')->item(0)->nodeValue);
					
					if (in_array($code, $this->config->get('fedex_service'))) {
						$quote_data[$code] = array(
							'code'         => 'fedex.' . $code,
							'title'        => $this->language->get('text_' . $code),
							'cost'         => $this->currency->convert($cost, $currency, $this->config->get('config_currency')),
							'tax_class_id' => $this->config->get('ups_tax_class_id'),
							'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($cost, $currency, $this->currency->getCode()), $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')), $this->currency->getCode(), 1.0000000)
						);
					}
				}
			}
		}
		
		$method_data = array();
		
		if ($quote_data) {
			$method_data = array(
				'code'       => 'fedex',
				'title'      => $title,
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('fedex_sort_order'),
				'error'      => $error
			);
		}
	
		return $method_data;
	}
}		
?>