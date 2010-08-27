<?php
final class Language {
  	private $directory;
	private $data = array();
 
	public function __construct($directory) {
		$this->directory = $directory;
	}
	
  	public function get($key) {
   		return (isset($this->data[$key]) ? $this->data[$key] : $key);
  	}
	
	public function load($filename) {
		$_ = array();

		$default = DIR_LANGUAGE . 'english/' . $filename . '.php';

		if (file_exists($default)) {
			require($default);
		}

		$file = DIR_LANGUAGE . $this->directory . '/' . $filename . '.php';

    	if (file_exists($file) && $file != $default) {
	  		require($file);
		}

	  	$this->data = array_merge($this->data, $_);

		return $this->data;
  	}
}
?>