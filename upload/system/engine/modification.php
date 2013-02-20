<?php


final class Modification {
	private $data = array();
	private $error = array();
	
	public function load($file) {
		if (file_exists($file)) { 
			$xml = file_get_contents($file);
			
			$this->parse($xml);
		} else {
			trigger_error('Error: Could not load modification ' . $file . '!');
			exit();
		}
	}
	
/* 
New XML Modifcation Standard 

<modification>
	<id><![CDATA[Test]]></id>
	<version><![CDATA[1.0]]></version>
	<author><![CDATA[http://www.opencart.com]]></author>
	<file name="catalog/controller/product/product.php" error="log|skip|abort">
		<operation>
			<search index="1" error="log|skip|abort"><![CDATA[
			
			code
			
			]]></search>
			
			<add position="replace|before|after"><![CDATA[
			
			code
			
			]]></add>
		</operation>
	</file>	
</modification>
*/	
	public function parse($xml) {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXml($xml);
		
		$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');		
		
		foreach ($files as $file) {
			$filename = $file->getAttribute('name');
		
			if (!isset($this->data[$filename])) {
				if (file_exists($filename)) {
					$content = file_get_contents($filename);
				} else {
					trigger_error('Error: Could not load language ' . $filename . '!');
					exit();
				}			
			} else {
				$content = $this->data[$filename];
			}

			$operations = $file->getElementsByTagName('operation');
		
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

			echo '<pre>';
			print_r($result);
			echo $content;
			echo '</pre>';			
		}
	}
}
?>