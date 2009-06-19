<?php
class ModelShippingUps extends Model {
	function getQuote() {
		$this->load->language('shipping/ups');
		
		if ($this->config->get('ups_status')) {
			$address = $this->customer->getAddress($this->session->data['shipping_address_id']);
			
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
			
			if ($this->config->get('ups_1dm')) {
				$ups = new UPS('1DM');
				$cost = $ups->getQuote();
				
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
		}
	
		return $method_data;
	}
	
	function ups($product) {
		switch ($this->config->get('ups_rate')) {
			case 'RDP':
				$rate = 'Regular+Daily+Pickup';
				break;
			case 'OCA':
				$rate = 'On+Call+Air';
				break;
			case 'OTP':
				$rate = 'One+Time+Pickup';
				break;
			case 'LC':
				$rate = 'Letter+Center';
				break;
			case 'CC':
				$rate = 'Customer+Counter';
				break;
		}
	  
		switch ($this->config->get('ups_packaging')) {
			case 'CP':            // Customer Packaging
				$container = '00';
				break;
			case 'ULE':        // UPS Letter Envelope
				$container = '01';        
				break;
			case 'UT':            // UPS Tube
				$container = '03';
				break;
			case 'UEB':            // UPS Express Box
				$container = '21';
				break;
			case 'UW25':        // UPS Worldwide 25 kilo
				$container = '24';
				break;
			case 'UW10':        // UPS Worldwide 10 kilo
				$container = '25';
				break;
		}
			
		switch ($this->config->get('ups_type')) {
			case 'RES':
				$type = '1';
				break;
			case 'COM':
				$type = '0';
				break;
		}

		$this->load->model('localisation/country');
			
		$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

		$url  = 'http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes';
		$url .= '&10_action=4';
		$url .= '&13_product=' . $product;
		$url .= '&14_origCountry=EU';
		$url .= '&15_origPostal=08033';
		$url .= '&19_destPostal=90210';
		$url .= '&22_destCountry=EU';
		$url .= '&23_weight=2';
		$url .= '&47_rateChart=Regular+Daily+Pickup';
		$url .= '&48_container=00';
		$url .= '&49_residential=1';
/*
$upsAction = "3"; //3 Price a Single Product OR 4 Shop entire UPS product range 
$upsProduct = "GND"; //set UPS Product Code See Chart Above 
$OriginPostalCode = "08053"; //zip code from where the client will ship from 
$DestZipCode = "08055"; //set where product is to be sent  
$PackageWeight = "5"; //weight of product 
$OrigCountry = "US"; //country where client will ship from 
$DestCountry = "US"; //set to country whaere product is to be sent 
$RateChart = "Regular+Daily+Pickup"; //set to how customer wants UPS to collect the product 
$Container = "00"; //Set to Client Shipping package type 
$ResCom = "1"; //See ResCom Table
*/			
		$fp = fopen($url, 'r');
			
		while (!feof($fp)) {
			$result = fgets($fp, 500);
				
			$result = explode('%', $result);
			
			$errcode = substr($result[0], -1);
			
			switch($errcode){ 
        		case 3: 
           			$returnval = $result[8]; 
               		break; 
        		case 4: 
           			$returnval = $result[8]; 
           			break; 
        		case 5: 
           			$returnval = $result[1]; 
           			break; 
        		case 6: 
          			$returnval = $result[1]; 
           			break;
			}
		}
			
		fclose($fp);
			
		if (!$returnval) {
			$returnval = 'error';
		}
		
		return $returnval;	
	}
}
?>