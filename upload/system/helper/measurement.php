<?php
final class HelperMeasurement {
	private $classes = array();
	private $rules = array();
	
	public function __construct() {
		$this->db = Registry::get('db');
		$this->language = Registry::get('language');

		$measurement_class_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "measurement_class WHERE language_id = '" . (int)$this->language->getId() . "'");
    
    	foreach ($measurement_class_query->rows as $result) {
      		$this->classes[$result['measurement_class_id']] = array(
        		'unit'  => $result['unit'],
        		'title' => $result['title']
      		);
    	}
		
    	$measurement_rule_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "measurement_rule");
	
    	foreach ($measurement_rule_query->rows as $result) {
      		$this->rules[$result['from_id']][$result['to_id']] = $result['rule'];
    	}
  	}
	  
  	public function convert($value, $from, $to) {
    	if ($from == $to) {
      		return $value;
    	} else {
      		return $value * (float)$this->rules[$from][$to];
    	}
  	}

	public function format($value, $measurement_class_id, $decimal_point = '.', $thousand_point = ',') {
    	return number_format($value, 2, $decimal_point, $thousand_point) . $this->classes[$measurement_class_id]['unit'];
  	}
}
?>