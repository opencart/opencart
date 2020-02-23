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
	private $expire;

	public function __construct($registry) {
		$this->db = $registry->get('db');

		if (ini_get('session.gc_maxlifetime')) {
			$gc_maxlifetime = ini_get('session.gc_maxlifetime');
		} else {
			$gc_maxlifetime = 3600;
		}

		$this->expire = $gc_maxlifetime;
	}

	public function exists($session_id) {
		$query = $this->db->query("SELECT `session_id` FROM `" . DB_PREFIX . "session` WHERE session_id = '" . $this->db->escape($session_id) . "'");

		if ($query->num_rows) {
			return true;
		}

		return false;
	}

	public function read($session_id) {
		$query = $this->db->query("SELECT `data` FROM `" . DB_PREFIX . "session` WHERE session_id = '" . $this->db->escape($session_id) . "' AND expire > NOW()");

		if ($query->num_rows) {
			return json_decode($query->row['data'], true);
		}

		return array();
	}

	public function write($session_id, $data) {
		if ($session_id) {
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "session` SET session_id = '" . $this->db->escape($session_id) . "', `data` = '" . $this->db->escape(json_encode($data)) . "', expire = DATE_ADD(NOW(), INTERVAL ". $this->db->escape($this->expire) ." SECOND)");
		}

		return true;
	}

	public function destroy($session_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "session` WHERE session_id = '" . $this->db->escape($session_id) . "'");

		return true;
	}
	
	public function gc($expire) {
		if (ini_get('session.gc_divisor')) {
			$gc_divisor = ini_get('session.gc_divisor');
		} else {
			$gc_divisor = 1;
		}

		if (ini_get('session.gc_probability')) {
			$gc_probability = ini_get('session.gc_probability');
		} else {
			$gc_probability = 1;
		}

		if ((rand() % $gc_divisor) < $gc_probability) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "session` WHERE expire < NOW()");
		}

		return true;
	}
}
