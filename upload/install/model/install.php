<?php
class ModelInstall extends Model {
	protected $db = null;
	protected $dbPrefix = null;

	public function database($data) {
		$this->dbPrefix = $data['db_prefix'];
		$this->db = new DB($data['db_driver'], $data['db_hostname'], $data['db_username'], $data['db_password'], $data['db_database']);

		if ($data['db_driver'] == 'postgre') {
			$this->buildPostgreDatabase($data);
		} else {
			$this->buildStandardDatabase($data);
		}
	}

	protected function buildStandardDatabase($data) {
		$file = DIR_APPLICATION . 'opencart.sql';
		$this->populateDatabase($file);

		$this->db->query("SET CHARACTER SET utf8");
		$this->db->query("SET @@session.sql_mode = 'MYSQL40'");

		$this->db->query("DELETE FROM `" . $this->dbPrefix . "user` WHERE user_id = '1'");
	
		$this->db->query("INSERT INTO `" . $this->dbPrefix . "user` SET user_id = '1', user_group_id = '1', username = '" . $this->db->escape($data['username']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '1', email = '" . $this->db->escape($data['email']) . "', date_added = NOW()");

		$this->db->query("DELETE FROM `" . $this->dbPrefix . "setting` WHERE `key` = 'config_email'");
		$this->db->query("INSERT INTO `" . $this->dbPrefix . "setting` SET `group` = 'config', `key` = 'config_email', value = '" . $this->db->escape($data['email']) . "'");
		
		$this->db->query("DELETE FROM `" . $this->dbPrefix . "setting` WHERE `key` = 'config_url'");
		$this->db->query("INSERT INTO `" . $this->dbPrefix . "setting` SET `group` = 'config', `key` = 'config_url', value = '" . $this->db->escape(HTTP_OPENCART) . "'");
		
		$this->db->query("DELETE FROM `" . $this->dbPrefix . "setting` WHERE `key` = 'config_encryption'");
		$this->db->query("INSERT INTO `" . $this->dbPrefix . "setting` SET `group` = 'config', `key` = 'config_encryption', value = '" . $this->db->escape(md5(mt_rand())) . "'");
		
		$this->db->query("UPDATE `" . $this->dbPrefix . "product` SET `viewed` = '0'");
	}	

	protected function buildPostgreDatabase($data) {
		$file = DIR_APPLICATION . 'opencart-postgre.sql';
		$this->populateDatabase($file, false);

		$this->db->query("DELETE FROM " . $this->dbPrefix . "user WHERE user_id = '1'");
	
		$this->db->query("INSERT INTO " . $this->dbPrefix . "user (user_id, user_group_id, username, salt, password, status, email, date_added) VALUES ('1', '1', '" . $this->db->escape($data['username']) . "', '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', TRUE, '" . $this->db->escape($data['email']) . "', NOW())");

		$this->db->query("DELETE FROM " . $this->dbPrefix . "setting WHERE key = 'config_email'");
		$this->db->query("INSERT INTO " . $this->dbPrefix . "setting (\"group\", key, value, serialized) VALUES ('config', 'config_email', '" . $this->db->escape($data['email']) . "', FALSE)");
		
		$this->db->query("DELETE FROM " . $this->dbPrefix . "setting WHERE key = 'config_url'");
		$this->db->query("INSERT INTO " . $this->dbPrefix . "setting (\"group\", key, value, serialized) VALUES ('config', 'config_url', '" . $this->db->escape(HTTP_OPENCART) . "', FALSE)");
		
		$this->db->query("DELETE FROM " . $this->dbPrefix . "setting WHERE key = 'config_encryption'");
		$this->db->query("INSERT INTO " . $this->dbPrefix . "setting (\"group\", key, value, serialized) VALUES ('config', 'config_encryption', '" . $this->db->escape(md5(mt_rand())) . "', FALSE)");
		
		$this->db->query("UPDATE " . $this->dbPrefix . "product SET viewed = '0'");
	}

	protected function populateDatabase($dumpFile, $withBacktick = true) {

		$backtick = $withBacktick ? '`' : '';

		if (!file_exists($dumpFile)) { 
			exit('Could not load sql file: ' . $dumpFile); 
		}
		
		$lines = file($dumpFile);
		
		if ($lines) {
			$sql = '';

			$search = array(
				'drop' => sprintf("DROP TABLE IF EXISTS %soc_", $backtick),
				'create_table' => sprintf("CREATE TABLE %soc_", $backtick),
				'insert' => sprintf("INSERT INTO %soc_", $backtick),
				'create_index' => sprintf(" ON %soc_", $backtick)
			);
			$replace = array(
				'drop' => sprintf("DROP TABLE IF EXISTS %s%s", $backtick, $this->dbPrefix),
				'create_table' => sprintf("CREATE TABLE %s%s", $backtick, $this->dbPrefix),
				'insert' => sprintf("INSERT INTO %s%s", $backtick, $this->dbPrefix),
				'create_index' => sprintf(" ON %s%s", $backtick, $this->dbPrefix)
			);

			foreach($lines as $line) {
				if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
					$sql .= $line;
  
					if (preg_match('/;\s*$/', $line)) {
						$sql = str_replace($search, $replace, $sql);
						$this->db->query($sql);
						$sql = '';
					}
				}
			}
		}
	}
}
