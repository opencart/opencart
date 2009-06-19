<?php
final class Tax {
	private $taxes = array();
	
	public function __construct() {
		$this->config = Registry::get('config');	
		$this->db = Registry::get('db');	
		$this->session = Registry::get('session');
			
		$tax_class_query = $this->db->query("SELECT country_id, zone_id FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)@$this->session->data['shipping_address_id'] . "' AND customer_id = '" . (int)@$this->session->data['customer_id'] . "'");
		
		if ($tax_class_query->num_rows) {
			$country_id = $tax_class_query->row['country_id'];
			$zone_id = $tax_class_query->row['zone_id'];
		} else {
			$country_id = $this->config->get('config_country_id');
			$zone_id = $this->config->get('config_zone_id');
		}
		
		$tax_rate_query = $this->db->query("SELECT tr.tax_class_id, SUM(tr.rate) AS rate, tr.description FROM " . DB_PREFIX . "tax_rate tr LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE (z2gz.country_id = '0' OR z2gz.country_id = '" . (int)$country_id . "') AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$zone_id . "') GROUP BY tr.tax_class_id");
	
		foreach ($tax_rate_query->rows as $result) {
      		$this->taxes[$result['tax_class_id']] = array(
        		'rate'        => $result['rate'],
        		'description' => $result['description']
      		);
    	}	
  	}
	
  	public function calculate($value, $tax_class_id, $calculate = TRUE) {	
		if (($calculate) && (isset($this->taxes[$tax_class_id])))  {
      		return $value + ($value * $this->taxes[$tax_class_id]['rate'] / 100);
    	} else {
      		return $value;
    	}
  	}
        
  	public function getRate($tax_class_id) {
    	return (isset($this->taxes[$tax_class_id]) ? $this->taxes[$tax_class_id]['rate'] : NULL);
  	}
  
  	public function getDescription($tax_class_id) {
		return (isset($this->taxes[$tax_class_id]) ? $this->taxes[$tax_class_id]['description'] : NULL);
  	}
  
  	public function has($tax_class_id) {
		return isset($this->taxes[$tax_class_id]);
  	}
}
?>