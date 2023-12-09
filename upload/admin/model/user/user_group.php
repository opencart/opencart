<?php
namespace Opencart\Admin\Model\User;
/**
 * Class User Group
 *
 * @package Opencart\Admin\Model\User
 */
class UserGroup extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addUserGroup(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user_group` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `permission` = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "'");
	
		return $this->db->getLastId();
	}

	/**
	 * @param int   $user_group_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editUserGroup(int $user_group_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `permission` = '" . (isset($data['permission']) ? $this->db->escape(json_encode($data['permission'])) : '') . "' WHERE `user_group_id` = '" . (int)$user_group_id . "'");
	}

	/**
	 * @param int $user_group_id
	 *
	 * @return void
	 */
	public function deleteUserGroup(int $user_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user_group` WHERE `user_group_id` = '" . (int)$user_group_id . "'");
	}

	/**
	 * @param int $user_group_id
	 *
	 * @return array
	 */
	public function getUserGroup(int $user_group_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "user_group` WHERE `user_group_id` = '" . (int)$user_group_id . "'");

		$user_group = [
			'name'       => $query->row['name'],
			'permission' => json_decode($query->row['permission'], true)
		];

		return $user_group;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getUserGroups(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "user_group` ORDER BY `name`";

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

	/**
	 * @return int
	 */
	public function getTotalUserGroups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "user_group`");

		return (int)$query->row['total'];
	}

	/**
	 * @param int    $user_group_id
	 * @param string $type
	 * @param string $route
	 *
	 * @return void
	 */
	public function addPermission(int $user_group_id, string $type, string $route): void {
		$user_group_query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "user_group` WHERE `user_group_id` = '" . (int)$user_group_id . "'");

		if ($user_group_query->num_rows) {
			$data = json_decode($user_group_query->row['permission'], true);

			$data[$type][] = $route;

			$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission` = '" . $this->db->escape(json_encode($data)) . "' WHERE `user_group_id` = '" . (int)$user_group_id . "'");
		}
	}

	/**
	 * @param int    $user_group_id
	 * @param string $type
	 * @param string $route
	 *
	 * @return void
	 */
	public function removePermission(int $user_group_id, string $type, string $route): void {
		$user_group_query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "user_group` WHERE `user_group_id` = '" . (int)$user_group_id . "'");

		if ($user_group_query->num_rows) {
			$data = json_decode($user_group_query->row['permission'], true);

			if (isset($data[$type])) {
				$data[$type] = array_diff($data[$type], [$route]);
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission` = '" . $this->db->escape(json_encode($data)) . "' WHERE `user_group_id` = '" . (int)$user_group_id . "'");
		}
	}
}
