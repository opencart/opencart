<?php
class ModelUserUserGroup extends Model {
	public function addUserGroup($data) {
		$this->db->query("INSERT INTO user_group SET name = '" . $this->db->escape(@$data['name']) . "', permission = '" . serialize(@$data['permission']) . "'");
	}
	
	public function editUserGroup($user_group_id, $data) {
		$this->db->query("UPDATE user_group SET name = '" . $this->db->escape(@$data['name']) . "', permission = '" . serialize(@$data['permission']) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
	}
	
	public function deleteUserGroup($user_group_id) {
		$this->db->query("DELETE FROM user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function getUserGroup($user_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
		
		$user_group = array(
			'name'       => $query->row['name'],
			'permission' => @unserialize($query->row['permission'])
		);
		
		return $user_group;
	}
	
	public function getUserGroups($data = array()) {
		$sql = "SELECT * FROM user_group";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $this->db->escape($data['sort']);	
		} else {
			$sql .= " ORDER BY name";	
		}
			
		if (isset($data['order'])) {
			$sql .= " " . $this->db->escape($data['order']);
		} else {
			$sql .= " ASC";
		}
			
		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
			
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalUserGroups() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM user_group");
		
		return $query->row['total'];
	}	
}
?>