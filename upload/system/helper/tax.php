<?php
final class HelperTax {
	private $taxes = array();
	
	public function __construct() {
		$this->config = Registry::get('config');
		$this->db = Registry::get('db');	
		$this->session = Registry::get('session');
		
		if (isset($this->session->data['shipping_address_id']) && isset($this->session->data['customer_id'])) {
			$address_query = $this->db->query("SELECT country_id, zone_id FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$this->session->data['shipping_address_id'] . "' AND customer_id = '" . (int)$this->session->data['customer_id'] . "'");
		
			if ($address_query->num_rows) {
				$country_id = $address_query->row['country_id'];
				$zone_id = $address_query->row['zone_id'];
			} else {
				$country_id = $this->config->get('config_country_id');
				$zone_id = $this->config->get('config_zone_id');
			}			
		} else {
			$country_id = $this->config->get('config_country_id');
			$zone_id = $this->config->get('config_zone_id');
		}		
		
		$tax_rate_query = $this->db->query("SELECT tr.tax_class_id, SUM(tr.rate) AS rate, tr.description, tr.priority FROM " . DB_PREFIX . "tax_rate tr LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE (z2gz.country_id = '0' OR z2gz.country_id = '" . (int)$country_id . "') AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$zone_id . "') GROUP BY tr.priority ORDER BY tr.priority ASC");
	
		foreach ($tax_rate_query->rows as $result) {
      		$this->taxes[$result['tax_class_id']][] = array(
        		'rate'        => $result['rate'],
        		'description' => $result['description'],
				'priority'    => $result['priority']
      		);
    	}	
  	}
	
  	public function calculate($value, $tax_class_id, $calculate = TRUE) {	
		if (($calculate) && (isset($this->taxes[$tax_class_id])))  {
			$rate = $this->getRate($tax_class_id);
			
      		return $value + ($value * $rate / 100);
    	} else {
      		return $value;
    	}
  	}
        
  	public function getRate($tax_class_id) {
		if (isset($this->taxes[$tax_class_id])) {
			$rate = 0;
			
			foreach ($this->taxes[$tax_class_id] as $tax_rate) {
				$rate += $tax_rate['rate'];
			}		
			
			return $rate;
		} else {
    		return 0;
		}
	}
  
  	public function getDescription($tax_class_id) {
		return (isset($this->taxes[$tax_class_id]) ? $this->taxes[$tax_class_id] : array());
  	}
  
  	public function has($tax_class_id) {
		return isset($this->taxes[$tax_class_id]);
  	}
}
?>