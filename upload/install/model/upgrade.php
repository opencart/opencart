<?php
class ModelUpgrade extends Model {
	public function mysql() {
		// Upgrade script to opgrade opencart to the latst version. 
		// Oldest version supported is 1.3.2
		
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
		
		$table_new_data = array();
				
		// Trim any spaces
		$string = trim($string);
		
		// Trim any ;
		$string = trim($string, ';');
			
		// Start reading each create statement
		$statements = explode(';', $string);
		
		foreach ($statements as $sql) {
			// Get all fields		
			$field_data = array();
			
			preg_match_all('#`(\w[\w\d]*)`\s+((tinyint|smallint|mediumint|bigint|int|tinytext|text|mediumtext|longtext|tinyblob|blob|mediumblob|longblob|varchar|char|datetime|date|float|double|decimal|timestamp|time|year|enum|set|binary|varbinary)(\((\d+)(,\s*(\d+))?\))?){1}\s*(collate (\w+)\s*)?(unsigned\s*)?((NOT\s*NULL\s*)|(NULL\s*))?(auto_increment\s*)?(default \'([^\']*)\'\s*)?#i', $sql, $match);

			foreach(array_keys($match[0]) as $key) {
				$field_data[] = array(
					'name'          => trim($match[1][$key]),
					'type'          => strtoupper(trim($match[3][$key])),
					'size'          => str_replace(array('(', ')'), '', trim($match[4][$key])),
					'sizeext'       => trim($match[8][$key]),
					'collation'     => trim($match[9][$key]),
					'unsigned'      => trim($match[10][$key]),
					'notnull'       => trim($match[11][$key]),
					'autoincrement' => trim($match[14][$key]),
					'default'       => trim($match[16][$key]),
				);
			}
						
			// Get primary keys
			$primary_data = array();
			
			preg_match('#primary\s*key\s*\([^)]+\)#i', $sql, $match);
			
			if (isset($match[0])) { 
				preg_match_all('#`(\w[\w\d]*)`#', $match[0], $match); 
			} else{ 
				$match = array();	
			}
			
			if ($match) {
				foreach($match[1] as $primary){
					$primary_data[] = $primary;
				}
			}
			
			// Get indexes
			$index_data = array();
			
			$indexes = array();
			
			preg_match_all('#key\s*`\w[\w\d]*`\s*\(.*\)#i', $sql, $match);

			foreach($match[0] as $key) {
				preg_match_all('#`(\w[\w\d]*)`#', $key, $match);
				
				$indexes[] = $match;
			}
			
			foreach($indexes as $index){
				$key = '';
				
				foreach($index[1] as $field) {
					if ($key == '') {
						$key = $field;
					} else{
						$index_data[$key][] = $field;
					}
				}
			}			
			
			// Table options
			$option_data = array();
			
			preg_match_all('#(\w+)=(\w+)#', $sql, $option);
			
			foreach(array_keys($option[0]) as $key) {
				$option_data[$option[1][$key]] = $option[2][$key];
			}

			// Get Table Name
			preg_match_all('#create\s*table\s*`(\w[\w\d]*)`#i', $sql, $table);
			
			if (isset($table[1][0])) {
				$table_new_data[] = array(
					'sql'     => $sql,
					'name'    => $table[1][0],
					'field'   => $field_data,
					'primary' => $primary_data,
					'index'   => $index_data,
					'option'  => $option_data
				);
			}
		}

		//print_r($table_new_data);

		//$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, 'test');

		// Get all current tables, fields, type, size, etc..
		$table_old_data = array();
		
		$table_query = $db->query("SHOW TABLES FROM `" . 'test' . "`");
				
		foreach ($table_query->rows as $table) {
			if (utf8_substr($table['Tables_in_' . 'test'], 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				$field_data = array(); 
				
				$field_query = $db->query("SHOW COLUMNS FROM `" . $table['Tables_in_' . 'test'] . "`");
				
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
				
				$table_old_data[$table['Tables_in_' . 'test']] = $field_data;
			}
		}
						
		foreach ($table_new_data as $table) {
			// If table is not found create it
			if (!isset($table_old_data[$table['name']])) {
				$db->query($table['sql']);
			} else {
				$i = 0;
				
				foreach ($table['field'] as $field) {
					// If field is not found create it
					if (!isset($table_old_data[$table['name']][$field['name']])) {
						$sql = "ALTER TABLE `" . $table['name'] . "` ADD `" . $field['name'] . "` " . $field['type'];
						
						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}
						
						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}
						 
						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}
						
						if ($field['default']) {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}
						
						if ($field['autoincrement']) {
							$sql .= " AUTO_INCREMENT";
						}
						
						if (isset($table['field'][$i - 1])) {
							$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
						} else {
							$sql .= " FIRST";
						}
						
						$db->query($sql);
					} else {
						$sql = "ALTER TABLE `" . $table['name'] . "` CHANGE `" . $field['name'] . "` `" . $field['name'] . "`";
						//`telephone` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
						$sql .= " " . strtoupper($field['type']);
								
						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}
							
						if ($field['collation']) {
							//$sql .= " " . $field['collation'];
						}
						
						$type_data = array(
							'CHAR',
							'VARCHAR',
							'TINYTEXT',
							'TEXT',
							'MEDIUMTEXT',
							'LONGTEXT',
							'TINYBLOB',
							'BLOB',
							'MEDIUMBLOB',
							'LONGBLOB',
							'ENUM',
							'SET',
							'BINARY',
							'VARBINARY'
						);
						
						if (in_array($field['type'], $type_data)) {
							$sql .= " CHARACTER SET utf8 COLLATE utf8_general_ci";
						}
						
						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}
						
						if ($field['default']) {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}
						
						if ($field['autoincrement']) {
							$sql .= " AUTO_INCREMENT";
						}
						
						if (isset($table['field'][$i - 1])) {
							$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
						} else {
							$sql .= " FIRST";
						}
												
						$db->query($sql);
					}
					
					$i++;
				}
				
				foreach ($table['field'] as $field) {
				
				}
				
				// Drop primary keys and indexes.
				$query = $db->query("SHOW INDEXES FROM `" . $table['name'] . "`");
				
				foreach ($query->rows as $result) {
					if ($result['Key_name'] != 'PRIMARY') {
						$db->query("ALTER TABLE `" . $table['name'] . "` DROP INDEX `" . $result['Key_name'] . "`");
					} else {
						
					}
				}				
				
				//$db->query("ALTER TABLE `" . $table['name'] . "` DROP PRIMARY KEY");
				
				//print_r($query->rows);
				// Just drop the curent primary key and add new ones.
				$primary_data = array();

				foreach ($table['primary'] as $primary) {
					$primary_data[] = "`" . $primary . "`";
				}

				if ($primary_data) {
					//$db->query("ALTER TABLE `" . $table['name'] . "` DROP PRIMARY KEY, ADD PRIMARY KEY(" . implode(',', $primary_data) . ")");
				}
				
				//unset($table['sql']);
								
				//echo 'New DB' . "\n";
				
				
				//echo 'Old DB' . "\n";
				//print_r($table_old_data[$table['name']]);
				
				// Drop indexes

				
				// Add the new indexes				
				foreach ($table['index'] as $index) {
					$index_data = array();
					
					foreach ($index as $key) {
						$index_data[] = '`' . $key . '`';
					}
					
					if ($index_data) {
						$db->query("ALTER TABLE `" . $table['name'] . "` ADD INDEX (" . implode(',', $index_data) . ")");			
					}	
					
									
				}

				// Change DB engine
				// ALTER TABLE  `oc_coupon_description` ENGINE = INNODB				
			}
		}
		
		/*
		// Settings
		$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' ORDER BY store_id ASC");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$settings[$setting['key']] = $setting['value'];
			} else {
				$settings[$setting['key']] = unserialize($setting['value']);
			}
		}
		*/
				
		// We can do all the SQL changes here
				
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