<?php
namespace Opencart\Admin\Model\User;
class User extends \Opencart\System\Engine\Model {
	public function addUser(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET `username` = '" . $this->db->escape((string)$data['username']) . "', `user_group_id` = '" . (int)$data['user_group_id'] . "', `password` = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `status` = '" . (bool)$data['status'] . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	public function editUser(int $user_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `username` = '" . $this->db->escape((string)$data['username']) . "', `user_group_id` = '" . (int)$data['user_group_id'] . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `status` = '" . (bool)$data['status'] . "' WHERE `user_id` = '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `password` = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "' WHERE `user_id` = '" . (int)$user_id . "'");
		}
	}

	public function editPassword(int $user_id, $password): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `password` = '" . $this->db->escape(password_hash(html_entity_decode($password, ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', `code` = '' WHERE `user_id` = '" . (int)$user_id . "'");
	}

	public function editCode(string $email, string $code): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `code` = '" . $this->db->escape($code) . "' WHERE LCASE(`email`) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function deleteUser(int $user_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE `user_id` = '" . (int)$user_id . "'");
	}

	public function getUser(int $user_id): array {
		$query = $this->db->query("SELECT *, (SELECT ug.`name` FROM `" . DB_PREFIX . "user_group` ug WHERE ug.`user_group_id` = u.`user_group_id`) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.`user_id` = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getUserByUsername(string $username): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE `username` = '" . $this->db->escape($username) . "'");

		return $query->row;
	}

	public function getUserByEmail(string $email): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "user` WHERE LCASE(`email`) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getUserByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE `code` = '" . $this->db->escape($code) . "' AND `code` != ''");

		return $query->row;
	}

	public function getUsers(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "user`";

		$sort_data = [
			'username',
			'status',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `username`";
		}

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

	public function getTotalUsers(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "user`");

		return (int)$query->row['total'];
	}

	public function getTotalUsersByGroupId(int $user_group_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "user` WHERE `user_group_id` = '" . (int)$user_group_id . "'");

		return (int)$query->row['total'];
	}

	public function getTotalUsersByEmail(string $email): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "user` WHERE LCASE(`email`) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return (int)$query->row['total'];
	}
}
