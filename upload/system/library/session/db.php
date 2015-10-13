<?php
/*

*/
namespace Session;
final class DB {
	public $data = array();
	
	public function __construct($registry) {
		
		
		session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'));
		
		register_shutdown_function('session_write_close');
	}
	
	public function open($path, $name) {
	
	}
	
	public function close() {
	
	}
	
	public function read($session_id) {
		$query = $this->db->query("SELECT `data` FROM `" . DB_PREFIX . "session` WHERE session_id = '" . $this->db->escape($session_id) . "' AND expire > " . time());
		
		if ($query->num_rows) {
			return true;
		} else {
			return false;
		}
	}
	
	public function write($session_id, $data) {
		$this->db->query("REPLACE INTO SET `data` = '" . $this->db->escape($data) . "', expire = NOW() + 1 FROM `" . DB_PREFIX . "session` WHERE session_id = '" . $this->db->escape($session_id) . "' AND expire > " . time());
		
		return true;
	}
	
	public function destroy($session_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "session` WHERE session_id = '" . $this->db->escape($session_id) . "'");
		
		return true;
	}
	
	public function gc($maxlifetime) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "session` WHERE date_added > " . time());
		
		return true;
	}
}
