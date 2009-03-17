<?php
final class Tax {
	private $taxes = array();
	
	public function __construct() {
		$this->config = Registry::get('config');	
		$this->db = Registry::get('db');	
		$this->session = Registry::get('session');
			
		$query = $this->db->query("SELECT country_id, zone_id FROM address WHERE address_id = '" . (int)@$this->session->data['shipping_address_id'] . "' AND customer_id = '" . (int)@$this->session->data['customer_id'] . "'");
		
		if ($query->num_rows) {
			$country_id = $query->row['country_id'];
			$zone_id    = $query->row['zone_id'];
		} else {
			$country_id = $this->config->get('config_country_id');
			$zone_id    = $this->config->get('config_zone_id');
		}
		
		$query = $this->db->query("SELECT tr.tax_class_id, SUM(tr.rate) AS rate, tr.description FROM tax_rate tr LEFT JOIN zone_to_geo_zone z2gz ON (tr.geo_zone_id = z2gz.geo_zone_id) LEFT JOIN geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE (z2gz.country_id = '0' OR z2gz.country_id = '" . (int)$country_id . "') AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$zone_id . "') GROUP BY tr.tax_class_id");
	
		foreach ($query->rows as $result) {
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