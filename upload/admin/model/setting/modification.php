<?php
class ModelSettingModification extends Model {
	public function addModification($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "modification SET name = '" . $this->db->escape($data['name']) . "', author = '" . $this->db->escape($data['author']) . "', version = '" . $this->db->escape($data['version']) . "', link = '" . $this->db->escape($data['link']) . "', code = '" . $this->db->escape($data['code']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}
	
	public function deleteModification($modification_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int)$modification_id . "'");
	}

	public function enableModification($modification_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "modification SET status = '1' WHERE modification_id = '" . (int)$modification_id . "'");
	}
	
	public function disableModification($modification_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "modification SET status = '0' WHERE modification_id = '" . (int)$modification_id . "'");
	}
	
	public function getModification($modification_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int)$modification_id . "'");
		
		return $query->row;
	}
			
	public function getModifications($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "modification";
								
		$sort_data = array(
			'name',
			'author',
			'version',
			'status',
			'date_added'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
		}	
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}	
	
	public function getTotalModifications() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "modification");
		
		return $query->row['total'];
	}
	
	public function refresh() {
		$xml = array();
		
		// Load the default modification XML
		$xml[] = file_get_contents(DIR_SYSTEM . 'modification.xml');

		// Get the default
		$results = $this->getModifications();

		foreach ($results as $result) {
			if ($result['status']) {
				$xml[] = $result['code'];
			}
		}
		
		$modification = array();
		
		foreach ($xml as $xml) {
			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->loadXml($xml);
			
			$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');		
			
			foreach ($files as $file) {
				$directory = '';
				
				if (substr($file->getAttribute('name'), 0, 7) == 'catalog') {
					$directory = DIR_CATALOG . substr($file->getAttribute('name'), 8);
				} elseif (substr($file->getAttribute('name'), 0, 5) == 'admin') {
					$directory = DIR_APPLICATION . substr($file->getAttribute('name'), 6);
				} elseif (substr($file->getAttribute('name'), 0, 6) == 'system') {
					$directory = DIR_SYSTEM . substr($file->getAttribute('name'), 7);
				}
				
				echo $directory . '<br />';
				echo $file->getAttribute('name') . '<br />';
				
				$files = glob($directory);
				
				$operations = $file->getElementsByTagName('operation');
				
				if ($files) {
					foreach ($files as $file) {
						$modfile = 
						
						if (!isset($modification[$file])) {
							$modification[$file] = file_get_contents($file);
						}
						
						foreach ($operations as $operation) {
							$search = trim($operation->getElementsByTagName('search')->item(0)->nodeValue);
							$index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');
							$add = trim($operation->getElementsByTagName('add')->item(0)->nodeValue);
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

							//echo 'File ' . $file . '<br />';
							//echo 'Search ' . $search . '<br />';
							//echo 'Replace ' . $replace . '<br />';			
							//echo 'Position ' . strpos($modification[$file], $search) . '<br />';
			
							//echo str_replace('DIR_SYSTEM . \'engine/action.php\'', $replace, $modification[$file]) . '<br />';

							$i = 0;
							$pos = -1;
							$result = array();
							
							while (($pos = strpos($modification[$file], $search, $pos + 1)) !== false) {
								$result[$i++] = $pos; 
							}
							
							// Only replace the occurance of the string that is equal to the index					
							if (isset($result[$index - 1])) {
								$modification[$file] = substr_replace($modification[$file], $replace, $result[$index - 1], strlen($search));
							}

							//echo $modification[$file] . '<br />';
						}
					}
				}
			}
		}
		
		// Write all modification files
		foreach ($modification as $key => $value) {
			if (substr($key, 0, 7) == 'catalog') {
				$directory = DIR_CATALOG . substr($key, 8);
			} elseif (substr($key, 0, 5) == 'admin') {
				$directory = DIR_APPLICATION . substr($key, 6);
			} elseif (substr($key, 0, 6) == 'system') {
				$directory = DIR_SYSTEM . substr($key, 7);
			}			
			
			$file = DIR_MODIFICATION . str_replace('/', '_', substr($key, strlen($this->directory)));
			
			echo $file . '<br>';
			/*
			$handle = fopen($file, 'w');
	
			fwrite($handle, $value);
	
			fclose($handle);
			*/			
		}
	}
	
	public function clear() {
		$files = glob(DIR_MODIFICATION . '{*.php,*.tpl}', GLOB_BRACE);

		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					unlink($file);
				}
			}
		}
	}				
}