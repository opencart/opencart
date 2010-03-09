<?php
class ModelShippingFedex extends Model {
	function getQuote($address) {
		$this->load->language('shipping/fedex');
		
		if ($this->config->get('fedex_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('fedex_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
      		if (!$this->config->get('fedex_geo_zone_id')) {
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
			if (!$this->config->get('fedex_test')) {
				$url = 'gateway.fedex.com/GatewayDC';
			} else {
				$url = 'gatewaybeta.fedex.com/GatewayDC';
			}
				
			$quote_data = array();

			$xml  = '<?xml version="1.0" encoding="UTF-8" ?>';
			$xml .= '<FDXRateRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="FDXRateRequest.xsd">';
			$xml .= '	<RequestHeader>';
			$xml .= '		<CustomerTransactionIdentifier>Express Rate</CustomerTransactionIdentifier>';
			$xml .= '		<AccountNumber>' . $this->config->get('fedex_account') . '</AccountNumber>';
			$xml .= '		<MeterNumber>' . $this->config->get('fedex_meter') . '</MeterNumber>';
			$xml .= '		<CarrierCode>' . 'FDXG' . '</CarrierCode>';
			$xml .= '	</RequestHeader>';
			$xml .= '	<DropoffType>' . 'REGULARPICKUP' . '</DropoffType>';
			$xml .= '	<Service>' . 'FEDEXGROUND' . '</Service>';
			$xml .= '	<Packaging>' . 'YOURPACKAGING' . '</Packaging>';
			$xml .= '	<WeightUnits>' . $this->currency->getCode($this->config->get('config_weight_class_id')) . '</WeightUnits>';
			$xml .= '	<Weight>' . number_format($this->cart->getWeight(), 1, '.', '') . '</Weight>';
			$xml .= '	<OriginAddress>';
			$xml .= '		<StateOrProvinceCode>' . 'LANCS' . '</StateOrProvinceCode>';
			$xml .= '		<PostalCode>' . 'FY5 4NN' . '</PostalCode>';
			$xml .= '		<CountryCode>' . 'UK' . '</CountryCode>';
			$xml .= '	</OriginAddress>';
			$xml .= '	<DestinationAddress>';
			$xml .= '		<StateOrProvinceCode>' . $address['zone_code'] . '</StateOrProvinceCode>';
			$xml .= '		<PostalCode>' . $address['postcode'] . '</PostalCode>';
			$xml .= '		<CountryCode>' . $address['iso_code_2'] . '</CountryCode>';
			$xml .= '	</DestinationAddress>';
			$xml .= '	<Payment>';
			$xml .= '		<PayorType>' . 'SENDER' . '</PayorType>';
			$xml .= '	</Payment>';
			$xml .= '	<PackageCount>' . ceil(bcdiv(number_format($this->cart->getWeight(), 1, '.', ''), '150', 3)) . '</PackageCount>';
			$xml .= '</FDXRateRequest>';
		   
		    $header = array();
			
			$header[] = 'Host: ' . $url;
			$header[] = 'MIME-Version: 1.0';
			$header[] = 'Content-type: multipart/mixed; boundary=----doc';
			$header[] = 'Accept: text/xml';
			$header[] = 'Content-length: '. strlen($xml);
			$header[] = 'Cache-Control: no-cache';
			$header[] = 'Connection: close' . "\r\n";
			$header[] = $xml;

			$ch = curl_init();
			//Disable certificate check.
			// uncomment the next line if you get curl error 60: error setting certificate verify locations
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			// uncommenting the next line is most likely not necessary in case of error 60
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			//-------------------------
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			//curl_setopt($ch, CURLOPT_CAINFO, "c:/ca-bundle.crt");
			//-------------------------
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				
			$response = curl_exec($ch); 
			
			//echo $response;
			
			//echo curl_errno($ch);
			
			curl_close($ch);


      		$quote_data['fedex'] = array(
        		'id'           => 'fedex.fedex',
        		'title'        => $this->language->get('text_description'),
        		'cost'         => $this->config->get('fedex_cost'),
        		'tax_class_id' => $this->config->get('fedex_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('fedex_cost'), $this->config->get('fedex_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'id'         => 'fedex',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('fedex_sort_order'),
        		'error'      => FALSE
      		);
		}
	
		return $method_data;
	}
}
?>