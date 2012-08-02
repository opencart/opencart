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
		
		$quote_data = array();
		
		if ($status) {
			if ($this->config->get('fedex_test')) {
				$url = 'https://gateway.fedex.com/GatewayDC';
			} else {
				$url = 'https://gatewaybeta.fedex.com/GatewayDC';
			}
			
			$curl = curl_init();
			
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			
			$response = curl_exec($curl);
			
			curl_close($curl);
			
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