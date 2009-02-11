<?php
final class Currency {
  	private $id;
	private $code;
  	private $currencies = array();
  
  	public function __construct() {
		$this->config = Registry::get('config');
		$this->db = Registry::get('db');
		$this->language = Registry::get('language');
		$this->request = Registry::get('request');
		$this->session = Registry::get('session');
		
		$query = $this->db->query("SELECT * FROM currency");

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

    	if (array_key_exists(@$this->session->data['currency'], $this->currencies)) {
      		$this->set($this->session->data['currency']);
    	} elseif (array_key_exists(@$this->request->cookie['currency'], $this->currencies)) {
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

    	if ((!isset($this->request->cookie['currency'])) || (@$this->request->cookie['currency'] != $currency)) {
	  		setcookie('currency', $currency, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
    	}
  	}

  	public function format($number, $currency = NULL, $value = NULL, $format = TRUE) {
		if ($currency) {
      		$symbol_left   = $this->currencies[$currency]['symbol_left'];
      		$symbol_right  = $this->currencies[$currency]['symbol_right'];
      		$decimal_place = $this->currencies[$currency]['decimal_place'];
    	} else {
      		$symbol_left   = $this->currencies[$this->code]['symbol_left'];
      		$symbol_right  = $this->currencies[$this->code]['symbol_right'];
      		$decimal_place = $this->currencies[$this->code]['decimal_place'];
    	}

    	if ($value) {
      		$value = $value;
    	} else {
      		$value = $this->currencies[$this->code]['value'];
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

    	$string .= number_format(round($value, $decimal_place), $decimal_place, (($format) ? $this->language->get('decimal_point') : '.'), $format ? ',' : '');

    	if (($symbol_right) && ($format)) {
      		$string .= $symbol_right;
    	}

    	return $string;
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