<?php
final class Weight {
	private $weights = array();
	
	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');

		$weight_class_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
    
    	foreach ($weight_class_query->rows as $result) {
      		$this->weights[strtolower($result['unit'])] = array(
        		'weight_class_id' => $result['weight_class_id'],
        		'title'           => $result['title'],
				'unit'            => $result['unit'],
				'value'           => $result['value']
      		); 
    	}
  	}
	  
  	public function convert($value, $from, $to) {
		if ($from == $to) {
      		return $value;
		}
		
		if (!isset($this->weights[strtolower($from)]) || !isset($this->weights[strtolower($to)])) {
			return $value;
		} else {			
			$from = $this->weights[strtolower($from)]['value'];
			$to = $this->weights[strtolower($to)]['value'];
		
			return $value * ($to / $from);
		}
  	}

	public function format($value, $unit, $decimal_point = '.', $thousand_point = ',') {
		if (isset($this->weights[strtolower($unit)])) {
    		return number_format($value, 2, $decimal_point, $thousand_point) . $this->weights[strtolower($unit)]['unit'];
		} else {
			return number_format($value, 2, $decimal_point, $thousand_point);
		}
	}
}
?>