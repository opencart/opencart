<?php
final class Currency {
  	private $code;
  	private $currencies = array();
  
  	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->language = $registry->get('language');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		
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
		
		if (isset($this->request->get['currency']) && (array_key_exists($this->request->get['currency'], $this->currencies))) {
			$this->set($this->request->get['currency']);
    	} elseif ((isset($this->session->data['currency'])) && (array_key_exists($this->session->data['currency'], $this->currencies))) {
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
		if ($currency && $this->has($currency)) {
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
		
    	$string .= number_format(round($value, (int)$decimal_place), (int)$decimal_place, $decimal_point, $thousand_point);

    	if (($symbol_right) && ($format)) {
      		$string .= $symbol_right;
    	}

    	return $string;
  	}
	
  	public function convert($value, $from, $to) {
		if (isset($this->currencies[$from])) {
			$from = $this->currencies[$from]['value'];
		} else {
			$from = 0;
		}
		
		if (isset($this->currencies[$to])) {
			$to = $this->currencies[$to]['value'];
		} else {
			$to = 0;
		}		
		
		return $value * ($to / $from);
  	}
	
  	public function getId($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['currency_id'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['currency_id'];
		} else {
			return 0;
		}
  	}
	
	public function getSymbolLeft($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['symbol_left'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_left'];
		} else {
			return '';
		}
  	}
	
	public function getSymbolRight($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['symbol_right'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_right'];
		} else {
			return '';
		}
  	}
	
	public function getDecimalPlace($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['decimal_place'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['decimal_place'];
		} else {
			return 0;
		}
  	}
	
  	public function getCode() {
    	return $this->code;
  	}
  
  	public function getValue($currency = '') {
		if (!$currency) {
			return $this->currencies[$this->code]['value'];
		} elseif ($currency && isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['value'];
		} else {
			return 0;
		}
  	}
    
  	public function has($currency) {
    	return isset($this->currencies[$currency]);
  	}
}
?>