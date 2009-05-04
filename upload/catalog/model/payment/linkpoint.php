<?php 
class ModelPaymentLinkPoint extends Model {
  	public function getMethod() {
		$this->load->language('payment/linkpoint');
		
		if ($this->config->get('linkpoint_status')) {
			$address = $this->customer->getAddress($this->session->data['payment_address_id']);
			
      		$query = $this->db->query("SELECT * FROM zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('linkpoint_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if (!$this->config->get('linkpoint_geo_zone_id')) {
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
      		$method_data = array( 
        		'id'         => 'linkpoint',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('linkpoint_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>