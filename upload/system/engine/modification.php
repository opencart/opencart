<?php
final class Modification {
	private $data = array();
	
	public function __construct() {
	
	}
	
	public function search() {
		
	}
	
	public function save() {
		
	}
	
	public function load($filename) {
		$file = DIR_CONFIG . $filename . '.php';
		
		if (file_exists($file)) { 
			$contents = file_get_contents($file);

			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->loadXml($contents);
			
			$modification = $dom->getElementsByTagName('modification')->item(0);
			
			
			
		} else {
			trigger_error('Error: Could not load modification ' . $filename . '!');
			exit();
		}
	}	
}
?>