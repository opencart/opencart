<?php
class ModelUserUserGroup extends Model {
	public function addUserGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "user_group SET name = '" . $this->db->escape((string)$data['name']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "'");
	
		return $this->db->getLastId();
	}

	public function editUserGroup($user_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "user_group SET name = '" . $this->db->escape((string)$data['name']) . "', permission = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function deleteUserGroup($user_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");
	}

	public function getUserGroup($user_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		$user_group = array(
			'name'       => $query->row['name'],
			'permission' => json_decode($query->row['permission'], true)
		);

		return $user_group;
	}

	public function getUserGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "user_group";

		$sql .= " ORDER BY name";

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalUserGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_group");

		return $query->row['total'];
	}

	public function addPermission($user_group_id, $type, $route) {
		$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		if ($user_group_query->num_rows) {
			$data = json_decode($user_group_query->row['permission'], true);

			$data[$type][] = $route;

			$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
		}
	}

	public function removePermission($user_group_id, $type, $route) {
		$user_group_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_group_id . "'");

		if ($user_group_query->num_rows) {
			$data = json_decode($user_group_query->row['permission'], true);

			$data[$type] = array_diff($data[$type], array($route));

			$this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $this->db->escape(json_encode($data)) . "' WHERE user_group_id = '" . (int)$user_group_id . "'");
		}
	}
}