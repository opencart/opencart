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
			/*
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
			*/
		}
		
		//$this->ups(90210, 03, 5, 5, 5, 5);
		
		return $method_data;
	}
}
?>