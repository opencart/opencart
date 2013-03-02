<?php
class Modification {
	private $data = array();	
	
	public function getFile() {
		if (isset($this->data[$filename])) {
			return $this->data[$filename];
		} else {
			return $filename;
		}		
	}
	
	public function load($filename) {
		$file = DIR_MODIFICATION . '/' . $filename . '.php';

		if (file_exists($file)) {
			$xml = file_get_contents($file);

			$this->write($xml);
		} else {
			trigger_error('Error: Could not load modification ' . $filename . '!');
		}
	}
	
	public function read($filename) {

	}

	public function write($xml) {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXml($xml);
		
		$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');		
		
		foreach ($files as $file) {
			$operations = $file->getElementsByTagName('operation');
			
			$files = glob($file->getAttribute('name'));
			
			if ($files) {	
				foreach ($files as $file) {
			
					if (!isset($this->data[$filename])) {
						$content = file_get_contents($filename);
					
						foreach ($operations as $operation) {
							$search = $operation->getElementsByTagName('search')->item(0)->nodeValue;
							$index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');
							$add = $operation->getElementsByTagName('add')->item(0)->nodeValue;
							$position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
								
							if (!$index) {
								$index = 1;
							}
							
							switch ($position) {
								default:
								case 'replace':
									$replace = $add;
									break;
								case 'before':
									$replace = $add . $search;
									break;
								case 'after':
									$replace = $search . $add;
									break;
							}
			
							$i = 0;
							$pos = -1;
							$result = array();
			
							while (($pos = strpos($content, $search, $pos + 1)) !== false) {
								$result[$i++] = $pos; 
							}
							
							// Only replace the occurance of the string that is equal to the index					
							if (isset($result[$index - 1])) {
								$content = substr_replace($content, $replace, $result[$index - 1], strlen($search));
							}
						}
						
						//$handle = fopen($filename);
					}
				}
			}	
		}
	}
}
?>