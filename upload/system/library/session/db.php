<?php
/*
CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(32) NOT NULL,
  `data` text NOT NULL,
  `expire` datetime NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
*/
namespace Session;
final class DB {
	public $data = array();
	public $expire = array();
	
	public function __construct($registry) {
		$this->db = $registry->get('db');
		
		register_shutdown_function('session_write_close');
		
		$this->expire = ini_get('session.gc_maxlifetime');
	}
	
	public function __open() {
		if ($this->db){
			return true;
		} else {
			return false;
		}
	}
	
	public function __close() {
		return true;
	}
	
	public function __read($session_id) {
		$query = $this->db->query("SELECT `data` FROM `" . DB_PREFIX . "session` WHERE session_id = '" . $this->db->escape($session_id) . "' AND expire > " . (int)time());
		
		if ($query->num_rows) {
			return $query->row['data'];
		} else {
			return false;
		}
	}
	
	public function __write($session_id, $data) {
		$this->db->query("REPLACE INTO SET `data` = '" . $this->db->escape($data) . "', expire = '" . $this->db->escape(date('Y-m-d H:i:s', time() + $this->expire)) . "' FROM `" . DB_PREFIX . "session` WHERE session_id = '" . $this->db->escape($session_id) . "' AND expire > " . (int)time());
		
		return true;
	}
	
	public function __destroy($session_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "session` WHERE session_id = '" . $this->db->escape($session_id) . "'");
		
		return true;
	}
	
	public function __gc($expire) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "session` WHERE expire < " . ((int)time() + $expire));
		
		return true;
	}
}
