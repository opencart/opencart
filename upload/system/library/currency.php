<?php
final class Currency {
  	private $code;
  	private $currencies = array();
  
  	public function __construct() {
		$this->config = Registry::get('config');
		$this->db = Registry::get('db');
		$this->language = Registry::get('language');
		$this->request = Registry::get('request');
		$this->session = Registry::get('session');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency");

    	foreach ($query->rows as $result) {
      		$this->currencies[$result['code']] = array(
        		'currency_id'   => $result['currency_id'],
        		'title'         => $result['title'],
        		'symbol_left'   => $result['symbol_left'],
        		'symbol_right'  => $result['symbol_right'],
        		'decimal_place' => $result['decimal_place'],
        		'value'         => $result['value']
      		); 
    	}

    	if ((isset($this->session->data['currency'])) && (array_key_exists($this->session->data['currency'], $this->currencies))) {
      		$this->set($this->session->data['currency']);
    	} elseif ((isset($this->request->cookie['currency'])) && (array_key_exists($this->request->cookie['currency'], $this->currencies))) {
      		$this->set($this->request->cookie['currency']);
    	} else {
      		$this->set($this->config->get('config_currency'));
    	}
  	}
	
  	public function set($currency) {
    	$this->code = $currency;

    	if ((!isset($this->session->data['currency'])) || ($this->session->data['currency'] != $currency)) {
      		$this->session->data['currency'] = $currency;
    	}

    	if ((!isset($this->request->cookie['currency'])) || ($this->request->cookie['currency'] != $currency)) {
	  		setcookie('currency', $currency, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
    	}
  	}

  	public function format($number, $currency = '', $value = '', $format = TRUE) {
		if ($currency) {
      		$symbol_left   = $this->currencies[$currency]['symbol_left'];
      		$symbol_right  = $this->currencies[$currency]['symbol_right'];
      		$decimal_place = $this->currencies[$currency]['decimal_place'];
    	} else {
      		$symbol_left   = $this->currencies[$this->code]['symbol_left'];
      		$symbol_right  = $this->currencies[$this->code]['symbol_right'];
      		$decimal_place = $this->currencies[$this->code]['decimal_place'];
			
			$currency = $this->code;
    	}

    	if ($value) {
      		$value = $value;
    	} else {
      		$value = $this->currencies[$currency]['value'];
    	}

    	if ($value) {
      		$value = $number * $value;
    	} else {
      		$value = $number;
    	}

    	$string = '';

    	if (($symbol_left) && ($format)) {
      		$string .= $symbol_left;
    	}

		if ($format) {
			$decimal_point = $this->language->get('decimal_point');
		} else {
			$decimal_point = '.';
		}
		
		if ($format) {
			$thousand_point = $this->language->get('thousand_point');
		} else {
			$thousand_point = '';
		}
		
    	$string .= number_format(round($value, $decimal_place), $decimal_place, $decimal_point, $thousand_point);

    	if (($symbol_right) && ($format)) {
      		$string .= $symbol_right;
    	}

    	return $string;
  	}
	
  	public function convert($number, $from, $to) {
		$value = $this->currency->getValue($from) / $this->currency->getValue($to);
		
		return $number * $value;
  	}
	
  	public function getId() {
		return $this->currencies[$this->code]['currency_id'];
  	}
	
  	public function getCode() {
    	return $this->code;
  	}
  
  	public function getValue($currency) {
    	return (isset($this->currencies[$currency]) ? $this->currencies[$currency]['value'] : NULL);
  	}
    
  	public function has($currency) {
    	return isset($this->currencies[$currency]);
  	}
}
?>