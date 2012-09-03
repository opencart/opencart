<?php
class ModelInstall extends Model {
	public function mysql($data) {
		$this->db = new DB('mysql', DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
				
		$file = DIR_APPLICATION . 'opencart.sql';
		
		if (!file_exists($file)) { 
			exit('Could not load sql file: ' . $file); 
		}
		
		$lines = file($file);
		
		if ($lines) {
			$query = '';

			foreach($lines as $line) {
				if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
					$query .= $line;
  
					if (preg_match('/;\s*$/', $line)) {
						$query = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . $data['db_prefix'], $query);
						$query = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . $data['db_prefix'], $query);
						$query = str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $query);
						
						$this->db->query($query);
	
						$query = '';
					}
				}
			}
			
			$this->db->query("SET CHARACTER SET utf8");
	
			$this->db->query("SET @@session.sql_mode = 'MYSQL40'");
		
			$this->db->query("DELETE FROM `" . $data['db_prefix'] . "user` WHERE user_id = '1'");
		
			$this->db->query("INSERT INTO `" . $data['db_prefix'] . "user` SET user_id = '1', user_group_id = '1', username = '" . $this->db->escape($data['username']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '1', email = '" . $this->db->escape($data['email']) . "', date_added = NOW()");

			$this->db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_email'");
			$this->db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_email', value = '" . $this->db->escape($data['email']) . "'");
			
			$this->db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_url'");
			$this->db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_url', value = '" . $this->db->escape(HTTP_OPENCART) . "'");
			
			$this->db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_encryption'");
			$this->db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_encryption', value = '" . $this->db->escape(md5(mt_rand())) . "'");
			
			$this->db->query("UPDATE `" . $data['db_prefix'] . "product` SET `viewed` = '0'");
		}		
	}	
}
?>