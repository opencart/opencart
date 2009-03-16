<?php
final class User {
	private $user_id;
	private $username;
  	private $permission = array();

  	public function __construct() {
		$this->db = Registry::get('db');
		$this->request = Registry::get('request');
		$this->session  = Registry::get('session');
		
    	if (isset($this->session->data['user_id'])) {
			$query = $this->db->query("SELECT * FROM user WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");
			
			if ($query->num_rows) {
				$this->user_id = $query->row['user_id'];
				$this->username = $query->row['username'];
				
      			$this->db->query("UPDATE user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

      			$query = $this->db->query("SELECT DISTINCT ug.permission FROM user u LEFT JOIN user_group ug ON u.user_group_id = ug.user_group_id WHERE u.user_id = '" . (int)$this->session->data['user_id'] . "'");
				
	  			foreach (unserialize($query->row['permission']) as $key => $value) {
	    			$this->permission[$key] = $value;
	  			}
			} else {
				$this->logout();
			}
    	}
  	}
		
  	public function login($username, $password) {
    	$query = $this->db->query("SELECT * FROM user WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape(md5($password)) . "'");

    	if ($query->num_rows) {
			$this->session->data['user_id'] = $query->row['user_id'];
			
			$this->user_id = $query->row['user_id'];
			$this->username = $query->row['username'];			

      		$query = $this->db->query("SELECT DISTINCT ug.permission FROM user u LEFT JOIN user_group ug ON u.user_group_id = ug.user_group_id WHERE u.user_id = '" . (int)$query->row['user_id'] . "'");

	  		foreach (unserialize($query->row['permission']) as $key => $value) {
	    		$this->permissions[$key] = $value;
	  		}
				
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	public function logout() {
		unset($this->session->data['user_id']);
	
		$this->user_id = '';
		$this->username = '';
  	}

  	public function hasPermission($key, $value) {
    	if (isset($this->permission[$key])) {
	  		return in_array($value, $this->permission[$key]);
		} else {
	  		return FALSE;
		}
  	}
  
  	public function isLogged() {
    	return $this->user_id;
  	}
  
  	public function getId() {
    	return $this->user_id;
  	}
	
  	public function getUserName() {
    	return $this->username;
  	}	
}
?>