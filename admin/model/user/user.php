<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		$this->db->query("INSERT INTO `user` SET username = '" . $this->db->escape(@$data['username']) . "', password = '" . $this->db->escape(md5(@$data['password'])) . "', firstname = '" . $this->db->escape(@$data['firstname']) . "', lastname = '" . $this->db->escape(@$data['lastname']) . "', email = '" . $this->db->escape(@$data['email']) . "', user_group_id = '" . (int)@$data['user_group_id'] . "', status = '" . (int)@$data['status'] . "', date_added = NOW()");
	}
	
	public function editUser($user_id, $data) {
		$this->db->query("UPDATE `user` SET username = '" . $this->db->escape(@$data['username']) . "', firstname = '" . $this->db->escape(@$data['firstname']) . "', lastname = '" . $this->db->escape(@$data['lastname']) . "', email = '" . $this->db->escape(@$data['email']) . "', user_group_id = '" . (int)@$data['user_group_id'] . "', status = '" . (int)@$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");
		
		if (@$data['password']) {
			$this->db->query("UPDATE `user` SET password = '" . $this->db->escape(md5(@$data['password'])) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}
	
	public function deleteUser($user_id) {
		$this->db->query("DELETE FROM `user` WHERE user_id = '" . (int)$user_id . "'");
	}
	
	public function getUser($user_id) {
		$query = $this->db->query("SELECT * FROM `user` WHERE user_id = '" . (int)$user_id . "'");
	
		return $query->row;
	}
	
	public function getUsers($data = array()) {
		$sql = "SELECT * FROM `user`";
			
		$sort_data = array(
			'username',
			'status',
			'date_added'
		);	
			
		if (in_array(@$data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY username";	
		}
			
		if (@$data['order'] == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
			
		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
			
		$query = $this->db->query($sql);
	
		return $query->rows;
	}

	public function getTotalUsers() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `user`");
		
		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `user` WHERE user_group_id = '" . (int)$user_group_id . "'");
		
		return $query->row['total'];
	}
}
?>