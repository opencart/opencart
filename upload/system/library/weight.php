<?php
final class Weight {
	private $classes = array();
	private $rules = array();
	
	public function __construct() {
    	$this->config = Registry::get('config');
		$this->db = Registry::get('db');
		$this->language = Registry::get('language');

		$weight_class_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class WHERE language_id = '" . (int)$this->language->getId() . "'");
    
    	foreach ($weight_class_query->rows as $result) {
      		$this->classes[$result['weight_class_id']] = array(
        		'unit'  => $result['unit'],
        		'title' => $result['title']
      		);
    	}
		
    	$weight_rule_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_rule");
	
    	foreach ($weight_rule_query->rows as $result) {
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

	public function format($value, $weight_class_id, $decimal_point = '.', $thousand_point = ',') {
    	return number_format($value, 2, $decimal_point, $thousand_point) . $this->classes[$weight_class_id]['unit'];
  	}
}
?>