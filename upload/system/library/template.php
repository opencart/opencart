<?php
final class Template {
	protected $data = array();
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function fetch($filename) {
		$file = DIR_TEMPLATE . $filename;
    
		if (file_exists($file)) {
			extract($this->data);
			
      		ob_start();
      
	  		include($file);
      
	  		return ob_end_flush();
    	} else {
      		exit('Error: Could not load template ' . $file . '!');
    	}	
	}
}
?>