<?php
final class Measurement {
	private $classes = array();
	private $rules = array();
	
	public function __construct() {
		$this->db = Registry::get('db');
		$this->config = Registry::get('config');

		$measurement_class_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "measurement_class WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");
    
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