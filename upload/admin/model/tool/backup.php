<?php
class ModelToolBackup extends Model {
	public function restore($sql) {
		foreach (explode(";\n", $sql) as $sql) {
    		$sql = trim($sql);
    		
			if ($sql) {
      			$result = mysql_query($sql);
      			
				if (!$result) {
        			exit('Error: ' . mysql_error() . '<br />Error No: ' . mysql_errno() . '<br />' . $sql);
      			}
    		}
  		}
	}
	
	public function backup() {
		$output = '';
		
		$list_tables = mysql_list_tables(DB_DATABASE);

		while ($row = mysql_fetch_row($list_tables)) {
			if (DB_PREFIX) {
				if (strpos($row[0], DB_PREFIX) === FALSE) {
					$status = FALSE;
				} else {
					$status = TRUE;
				}
			} else {
				$status = TRUE;
			}
			
			if ($status) {
				$output .= 'TRUNCATE TABLE `' . $row[0] . '`;' . "\n\n";
			
				$query = $this->db->query("SELECT * FROM `" . $row[0] . "`");
				
				foreach ($query->rows as $result) {
					$fields = '';
					
					foreach (array_keys($result) as $value) {
						$fields .= '`' . $value . '`, ';
					}
					
					$values = '';
					
					foreach (array_values($result) as $value) {
						$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
						$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
						$value = str_replace('\\', '\\\\',	$value);
						$value = str_replace('\'', '\\\'',	$value);
						$value = str_replace('\\\n', '\n',	$value);
						$value = str_replace('\\\r', '\r',	$value);
						$value = str_replace('\\\t', '\t',	$value);			
						
						$values .= '\'' . $value . '\', ';
					}
					
					$output .= 'INSERT INTO `' . $row[0] . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
				}
				
				$output .= "\n\n";
			}
		}

		return $output;	
	}
}
?>