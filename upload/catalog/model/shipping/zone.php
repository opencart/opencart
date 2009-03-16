<?php 
class ModelShippingZone extends Model {    
  	public function getQuote() {
		$this->load->language('shipping/zone');
		
		$quote_data = array();

		if ($this->config->get('zone_status')) {
			$query = $this->db->query("SELECT * FROM geo_zone ORDER BY name");
		
			$address = $this->customer->getAddress($this->session->data['shipping_address_id']);
		
			foreach ($query->rows as $result) {
   				if ($this->config->get('zone_' . $result['geo_zone_id'] . '_status')) {
   					$query = $this->db->query("SELECT * FROM zone_to_geo_zone WHERE geo_zone_id = '" . (int)$result['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
				
					if ($query->num_rows) {
       					$status = TRUE;
   					} else {
       					$status = FALSE;
   					}
				} else {
					$status = FALSE;
				}
			
				if ($status) {
					$cost = 0;
				
					$rates = explode(',', $this->config->get('zone_' . $result['geo_zone_id'] . '_cost'));

					foreach ($rates as $rate) {
  						$data = explode(':', $rate);
  					
						if ($this->cart->getWeight() <= $data[0]) {
    						$cost = @$data[1];
						
   							break;
  						}
					}
			
      				$quote_data['zone_' . $result['geo_zone_id']] = array(
        				'id'           => 'zone.zone_' . $result['geo_zone_id'],
        				'title'        => $result['name'],
        				'cost'         => $cost,
						'tax_class_id' => $this->config->get('zone_tax_class_id'),
        				'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('zone_tax_class_id'), $this->config->get('config_tax')))
      				);			
				}
			}
		}
		
		$method_data = array();
	
		if ($quote_data) {
      		$method_data = array(
        		'id'         => 'zone',
        		'title'      => $this->language->get('text_title'),
        		'quote'      => $quote_data,
				'sort_order' => $this->config->get('zone_sort_order'),
        		'error'      => FALSE
      		);
		}
	
		return $method_data;
  	}
}
?>