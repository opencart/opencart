<?php
class ModelUpgrade extends Model {
	public function mysql() {
		// Upgrade script to opgrade opencart to the latst version. 
		// Oldest version supported is 1.3.2
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		
		// Settings
		$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' ORDER BY store_id ASC");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$settings[$setting['key']] = $setting['value'];
			} else {
				$settings[$setting['key']] = unserialize($setting['value']);
			}
		}
		/*
		// Get all current tables, fields, type, size, etc..
		$table_data = array();
		
		$table_query = $db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");
		
		foreach ($table_query->rows as $table) {
			if (utf8_substr($table['Tables_in_' . DB_DATABASE], 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				$field_data = array(); 
				
				$field_query = $db->query("SHOW COLUMNS FROM `" . $table['Tables_in_' . DB_DATABASE] . "`");
				
				foreach ($field_query->rows as $field) {
					preg_match('/\((.*)\)/', $field['Type'], $match);
					
					$field_data[$field['Field']] = array(
						'name'    => $field['Field'],
						'type'    => preg_replace('/\(.*\)/', '', $field['Type']),
						'size'    => isset($match[1]) ? $match[1] : '',
						'null'    => $field['Null'],
						'key'     => $field['Key'],
						'default' => $field['Default'],
						'extra'   => $field['Extra']
					);
				}
				
				$table_data[$table['Tables_in_' . DB_DATABASE]] = $field_data;
			}
		}		
		*/
		
		// Load the sql file
		$file = DIR_APPLICATION . 'opencart.sql';
		
		if (!file_exists($file)) { 
			exit('Could not load sql file: ' . $file); 
		}
		
		$string = '';	
		
		$lines = file($file);
		
		$status = false;	
		
		// Get only the create statements
		foreach($lines as $line) {
			// Set any prefix
			$line = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . DB_PREFIX, $line);
			
			// If line begins with create table we want to start recording
			if (substr($line, 0, 12) == 'CREATE TABLE') {
				$status = true;	
			}
			
			if ($status) {
				$string .= $line;
			}
			
			// If line contains with ; we want to stop recording
			if (preg_match('/;/', $line)) {
				$status = false;
			}
		}
			
		// Start reading each create statement
		$queries = explode(';', $string);
		
		foreach ($queries as $query) {
			//echo $query . "\n";
			
			// Get all fields		
			$field_data = array();
			
			preg_match_all('#`(\w[\w\d]*)`\s+((tinyint|smallint|mediumint|int|bigint|tinytext|text|mediumtext|longtext|tinyblob|blob|mediumblob|longblob|varchar|char|date|datetime|float|double|decimal|timestamp|time|year|enum|set|binary|varbinary)(\((\d+)(,\s*(\d+))?\))?){1}\s*(collate (\w+)\s*)?(unsigned\s*)?((NOT\s*NULL\s*)|(NULL\s*))?(auto_increment\s*)?(default \'([^\']*)\'\s*)?#i', $query, $fields);

			foreach($fields[0] as $key => $fielddata){
				$field_data[$fields[1][$key]] = array(
					'name'          => trim($fields[1][$key]),
					'type'          => trim($fields[3][$key]),
					'size'          => trim($fields[5][$key]),
					'sizeext'       => trim($fields[8][$key]),
					'collation'     => trim($fields[9][$key]),
					'unsigned'      => trim($fields[10][$key]),
					'notnull'       => trim($fields[11][$key]),
					'autoincrement' => trim($fields[14][$key]),
					'default'       => trim($fields[16][$key]),
				);
			}
						
			// Get primary keys
			$primary_data = array();
			
			preg_match('#primary\s*key\s*\([^)]+\)#i', $query, $primarykeydefinition);
			
			if (isset($primarykeydefinition[0])) { 
				preg_match_all('#`(\w[\w\d]*)`#', $primarykeydefinition[0], $primarykeydefinition); 
			} else{ 
				$primarykeydefition = array();
			}
			
			if (count($primarykeydefinition) > 0){
				foreach($primarykeydefinition[1] as $fieldkey => $field){
					$primary_data[] = $field;
				}
			}
			
			// Get indexes
			$index_data = array();
			
			$keys = array();
			
			preg_match_all('#key\s*`\w[\w\d]*`\s*\(.*\)#i', $query, $keydefinition);

			foreach($keydefinition[0] as $key){
				preg_match_all('#`(\w[\w\d]*)`#', $key, $keydef);
				
				$keys[] = $keydef;
			}
			
			foreach($keys as $keycollection){
				$indexkey = '';
				
				foreach($keycollection[1] as $key => $name){
					if ($indexkey == ''){
						$indexkey = $name;
					} else{
						$index_data[$indexkey][] = $name;
					}
				}
			}			
			
			// Table options
			$option_data = array();
			
			preg_match_all('#(\w+)=(\w+)#', $query, $option);
			
			foreach($option[0] as $key => $optiondata){
				$option_data[$option[1][$key]] = $option[2][$key];
			}

			// Get Table Name
			preg_match_all('#create\s*table\s*`(\w[\w\d]*)`#i', $query, $tablename);
			
			if (isset($tablename[1][0])) {
				$table_data[] = array(
					'name'    => $tablename[1][0],
					'field'   => $field_data,
					'primary' => $primary_data,
					'index'   => $index_data,
					'option'  => $option_data,
				);
			}
		}
		
		//print_r($table_data);
			
		
		
		// We can do all the SQL changes here
		
		// ALTER TABLE  `oc_custom_field_value_description` ADD PRIMARY KEY (`custom_field_value_id`, `language_id`);
		// ALTER TABLE `oc_product` MODIFY `shipping` tinyint(1) NOT NULL DEFAULT '1' COMMENT '';
		// ALTER TABLE oc_customer ADD token varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER approved;
		// ALTER TABLE oc_product_tag ADD INDEX product_id (product_id);
				
		//preg_match_all('/`(.+)` (\w+)\(? ?(\d*) ?\)?/', $string, $match);
		//preg_match_all('/CREATE\sTABLE\s`(.+)`\s^\((.+)\)\sENGINE\=MyISAM\sDEFAULT\sCHARSET\=utf8\sCOLLATE\=utf8_bin;/i', $string, $matches);
				
		// Sort the categories to take advantage of the nested set model
		//$this->path(0, 0);
	}
	
	protected function path($category_id = 0, $level) {
		$this->db->query("UPDATE " . DB_PREFIX . "category SET `left` = '" . (int)$level++ . "' WHERE category_id = '" . (int)$category_id . "'");
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$category_id . "' ORDER BY sort_order");
		
		foreach ($query->rows as $result) {
			$level = $this->path($result['category_id'], $level);
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "category SET `right` = '" . (int)$level++ . "' WHERE category_id = '" . (int)$category_id . "'");
	
		return $level;
	}	
}
?>