<?php
class Modification {
	private $mod = array();
	private $content = array();
	
	public function addMod($modifcation) {
		$this->mod[] = $modifcation;
	}
		
	public function getFile($file) {
		if (file_exists($file)) {
			return DIR_MODIFICATION . str_replace('/', '_', $file);
		} else {
			return $file;
		}		
	}
		
	public function load($filename) {
		$file = DIR_MODIFICATION . '/' . $filename . '.php';

		if (file_exists($file)) {
			$xml = file_get_contents($file);

			$this->addMod($xml);
		} else {
			trigger_error('Error: Could not load modification ' . $filename . '!');
		}
	}

	public function write() {
		foreach ($this->mod as $xml) {
			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->loadXml($xml);
			
			$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');		
			
			foreach ($files as $file) {
				$files = glob($file->getAttribute('name'));
				$operations = $file->getElementsByTagName('operation');
				
				if ($files) {	
					foreach ($files as $file) {
						if (!isset($this->content[$file])) {
							$content = file_get_contents($file);
						} else {
							$content = $this->content[$file];
						}
						
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
						
						$this->content[$file] = $content;
					}
				}
			}
		}
		
		// Write all modifcation files
		foreach ($this->content as $key => $value) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}		
			}
			
			
						
			$file = DIR_MODIFICATION . $key . '.php';
			
			$handle = fopen($file, 'w');
	
			fwrite($handle, $value);
	
			fclose($handle);			
		}
	}
	
	public function clear() {
		$files = glob(DIR_MODIFICATION . '.*');

		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					unlink($file);
				}
			}
		}				
	}
}
?>