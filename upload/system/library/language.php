<?php
final class Language {
  	private $code;
  	private $languages = array();
	private $data = array();
 
	public function __construct() {
		$this->config  = Registry::get('config');
		$this->db = Registry::get('db');
		$this->request = Registry::get('request');
		$this->session = Registry::get('session');

    	$query = $this->db->query("SELECT * FROM language");

    	foreach ($query->rows as $result) {
      		$this->languages[$result['code']] = array(
        		'language_id' => $result['language_id'],
        		'name'        => $result['name'],
        		'code'        => $result['code'],
				'locale'      => $result['locale'],
				'directory'   => $result['directory'],
				'filename'    => $result['filename']
      		);
    	}
 		
    	if (array_key_exists(@$this->session->data['language'], $this->languages)) {
      		$this->set($this->session->data['language']);
    	} elseif (array_key_exists(@$this->request->cookie['language'], $this->languages)) {
      		$this->set($this->request->cookie['language']);
    	} elseif ($browser = $this->detect()) {
	    	$this->set($browser);
	  	} else {
        	$this->set($this->config->get('config_language'));
		}
	
		$this->load($this->languages[$this->code]['filename']);		
	}

	public function set($language) {
		if (isset($this->languages[$language])) {
    		$this->code = $language;
		}
		
    	if ((!isset($this->session->data['language'])) || ($this->session->data['language'] != $this->code)) {
      		$this->session->data['language'] = $this->code;
    	}

    	if ((!isset($this->request->cookie['language'])) || ($this->request->cookie['language'] != $this->code)) {	  
	  		setcookie('language', $this->code, time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
    	}	
	}
	
  	public function get($key) {
   		return (isset($this->data[$key]) ? $this->data[$key] : $key);
  	}
	
	public function load($filename, $code = FALSE) {
		if (isset($this->languages[$code])) {
			$file = DIR_LANGUAGE . $this->languages[$code]['directory'] . '/' . $filename . '.php';
		} else {
			$file = DIR_LANGUAGE . $this->languages[$this->code]['directory'] . '/' . $filename . '.php';
		}

    	if (file_exists($file)) {
	  		$_ = array();
	  
	  		require($file);
	  
      		$this->data = array_merge($this->data, $_);
    	} else {
      		exit('Error: Could not load language ' . $filename . '!');
    	}
  	}

	private function detect() {
    	if (@$this->request->server['HTTP_ACCEPT_LANGUAGE']) { 
      		$browser_languages = explode(',', @$this->request->server['HTTP_ACCEPT_LANGUAGE']);
			
      		foreach ($browser_languages as $browser_language) {
        		foreach ($this->languages as $key => $language) {
					$locale = explode(',', $language['locale']);

					if (in_array($browser_language, $locale)) {
						return $key;
					}
        		}
      		}
    	}

    	return FALSE;			
	}

	public function getId() {
    	return $this->languages[$this->code]['language_id'];
  	}

  	public function getCode() {
    	return $this->code;
  	}  	
}
?>