<?php
final class Length {
	private $lengths = array();
	
	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');

		$length_class_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class mc LEFT JOIN " . DB_PREFIX . "length_class_description mcd ON (mc.length_class_id = mcd.length_class_id) WHERE mcd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
    
    	foreach ($length_class_query->rows as $result) {
      		$this->lengths[strtolower($result['unit'])] = array(
				'length_class_id' => $result['length_class_id'],
        		'unit'            => $result['unit'],
        		'title'           => $result['title'],
				'value'           => $result['value']
      		);
    	}
  	}
	  
  	public function convert($value, $from, $to) {
		if ($from == $to) {
      		return $value;
		}
		
		if (isset($this->lengths[strtolower($from)])) {
			$from = $this->lengths[strtolower($from)]['value'];
		} else {
			$from = 0;
		}
		
		if (isset($this->lengths[strtolower($to)])) {
			$to = $this->lengths[strtolower($to)]['value'];
		} else {
			$to = 0;
		}		
		
      	return $value * ($to / $from);
  	}

	public function format($value, $unit, $decimal_point = '.', $thousand_point = ',') {
    	return number_format($value, 2, $decimal_point, $thousand_point) . $this->lengths[$unit]['unit'];
  	}
}
?>