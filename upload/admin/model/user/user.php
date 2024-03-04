<?php
namespace Opencart\Admin\Model\User;
/**
 * Class User
 *
 * @package Opencart\Admin\Model\User
 */
class User extends \Opencart\System\Engine\Model {
	/**
	 * Add User
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addUser(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET `username` = '" . $this->db->escape((string)$data['username']) . "', `user_group_id` = '" . (int)$data['user_group_id'] . "', `password` = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Edit User
	 *
	 * @param int                  $user_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editUser(int $user_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `username` = '" . $this->db->escape((string)$data['username']) . "', `user_group_id` = '" . (int)$data['user_group_id'] . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `image` = '" . $this->db->escape((string)$data['image']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `user_id` = '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `password` = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "' WHERE `user_id` = '" . (int)$user_id . "'");
		}
	}

	/**
	 * Edit Password
	 *
	 * @param int    $user_id
	 * @param string $password
	 *
	 * @return void
	 */
	public function editPassword(int $user_id, $password): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `password` = '" . $this->db->escape(password_hash(html_entity_decode($password, ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', `code` = '' WHERE `user_id` = '" . (int)$user_id . "'");
	}

	/**
	 * Edit Code
	 *
	 * @param string $email
	 * @param string $code
	 *
	 * @return void
	 */
	public function editCode(string $email, string $code): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `code` = '" . $this->db->escape($code) . "' WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");
	}

	/**
	 * Delete User
	 *
	 * @param int $user_id
	 *
	 * @return void
	 */
	public function deleteUser(int $user_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE `user_id` = '" . (int)$user_id . "'");

		$this->deleteAuthorizes($user_id);
		$this->deleteLogins($user_id);
	}

	/**
	 * Get User
	 *
	 * @param int $user_id
	 *
	 * @return array<string, mixed>
	 */
	public function getUser(int $user_id): array {
		$query = $this->db->query("SELECT *, (SELECT ug.`name` FROM `" . DB_PREFIX . "user_group` ug WHERE ug.`user_group_id` = u.`user_group_id`) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.`user_id` = '" . (int)$user_id . "'");

		return $query->row;
	}

	/**
	 * Get User By Username
	 *
	 * @param string $username
	 *
	 * @return array<string, mixed>
	 */
	public function getUserByUsername(string $username): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE `username` = '" . $this->db->escape($username) . "'");

		return $query->row;
	}

	/**
	 * Get User By Email
	 *
	 * @param string $email
	 *
	 * @return array<string, mixed>
	 */
	public function getUserByEmail(string $email): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "user` WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");

		return $query->row;
	}

	/**
	 * Get User By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getUserByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE `code` = '" . $this->db->escape($code) . "' AND `code` != ''");

		return $query->row;
	}

	/**
	 * Get Users
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getUsers(array $data = []): array {
		$sql = "SELECT *, CONCAT(`u`.`firstname`, ' ', `u`.`lastname`) AS `name`, (SELECT `ug`.`name` FROM `" . DB_PREFIX . "user_group` `ug` WHERE `ug`.`user_group_id` = `u`.`user_group_id`) AS user_group FROM `" . DB_PREFIX . "user` `u`";

		$implode = [];

		if (!empty($data['filter_username'])) {
			$implode[] = "LCASE(`u`.`username`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_username']) . '%') . "'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(`u`.`firstname`, ' ', `u`.`lastname`)) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(`u`.`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email']) . '%') . "'";
		}

		if (!empty($data['filter_user_group_id'])) {
			$implode[] = "`u`.`user_group_id` = '" . (int)$data['filter_user_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "`u`.`user_id` IN (SELECT `user_id` FROM `" . DB_PREFIX . "user_login` WHERE `ip` LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_ip']) . '%') . "')";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`u`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			'username',
			'name',
			'u.email',
			'user_group',
			'status',
			'ip',
			'u.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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

	/**
	 * Get Total Users
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function getTotalUsers(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "user` `u` ";

		$implode = [];

		if (!empty($data['filter_username'])) {
			$implode[] = "LCASE(`u`.`username`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_username']) . '%') . "'";
		}

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(`u`.`firstname`, ' ', `u`.`lastname`)) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(`u`.`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email']) . '%') . "'";
		}

		if (!empty($data['filter_user_group_id'])) {
			$implode[] = "`u`.`user_group_id` = '" . (int)$data['filter_user_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "`u`.`user_id` IN (SELECT `user_id` FROM `" . DB_PREFIX . "user_login` WHERE `ip` LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_ip']) . '%') . "')";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`u`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Users By Group ID
	 *
	 * @param int $user_group_id
	 *
	 * @return int
	 */
	public function getTotalUsersByGroupId(int $user_group_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "user` WHERE `user_group_id` = '" . (int)$user_group_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Users By Email
	 *
	 * @param string $email
	 *
	 * @return int
	 */
	public function getTotalUsersByEmail(string $email): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "user` WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Login
	 *
	 * @param int                  $user_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addLogin(int $user_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user_login` SET `user_id` = '" . (int)$user_id . "', `ip` = '" . $this->db->escape($data['ip']) . "', `user_agent` = '" . $this->db->escape($data['user_agent']) . "', `date_added` = NOW()");
	}

	/**
	 * Delete User Logins
	 *
	 * @param int $user_id
	 *
	 * @return void
	 */
	public function deleteLogins(int $user_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user_login` WHERE `user_id` = '" . (int)$user_id . "'");
	}

	/**
	 * Get Logins
	 *
	 * @param int $user_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getLogins(int $user_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_login` WHERE `user_id` = '" . (int)$user_id . "' LIMIT " . (int)$start . "," . (int)$limit);

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return [];
		}
	}

	/**
	 * Get Total Logins
	 *
	 * @param int $user_id
	 *
	 * @return int
	 */
	public function getTotalLogins(int $user_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "user_login` WHERE `user_id` = '" . (int)$user_id . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * Add Authorize
	 *
	 * @param int                  $user_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addAuthorize(int $user_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user_authorize` SET `user_id` = '" . (int)$user_id . "', `token` = '" . $this->db->escape($data['token']) . "', `ip` = '" . $this->db->escape($data['ip']) . "', `user_agent` = '" . $this->db->escape($data['user_agent']) . "', `date_added` = NOW()");
	}

	/**
	 * Edit Authorize Status
	 *
	 * @param int  $user_authorize_id
	 * @param bool $status
	 *
	 * @return void
	 */
	public function editAuthorizeStatus(int $user_authorize_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user_authorize` SET `status` = '" . (bool)$status . "' WHERE `user_authorize_id` = '" . (int)$user_authorize_id . "'");
	}

	/**
	 * Edit Authorize Total
	 *
	 * @param int $user_authorize_id
	 * @param int $total
	 *
	 * @return void
	 */
	public function editAuthorizeTotal(int $user_authorize_id, int $total): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user_authorize` SET `total` = '" . (int)$total . "' WHERE `user_authorize_id` = '" . (int)$user_authorize_id . "'");
	}

	/**
	 * Reset User Authorizes
	 *
	 * @param int $user_id
	 * @param int $total
	 *
	 * @return void
	 */
	public function editAuthorizeTotalByUserId(int $user_id, int $total): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "user_authorize` SET `total` = '" . (int)$total . "' WHERE `user_id` = '" . (int)$user_id . "'");
	}

	/**
	 * Delete User Authorizes
	 *
	 * @param int $user_id
	 * @param int $user_authorize_id
	 *
	 * @return void
	 */
	public function deleteAuthorizes(int $user_id, int $user_authorize_id = 0): void {
		$sql = "DELETE FROM `" . DB_PREFIX . "user_authorize` WHERE `user_id` = '" . (int)$user_id . "'";

		if ($user_authorize_id) {
			$sql .= " AND `user_authorize_id` = '" . (int)$user_authorize_id . "'";
		}

		$this->db->query($sql);
	}

	/**
	 * Get Authorize
	 *
	 * @param int $user_authorize_id
	 *
	 * @return array<string, mixed>
	 */
	public function getAuthorize(int $user_authorize_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_authorize` WHERE `user_authorize_id` = '" . (int)$user_authorize_id . "'");

		return $query->row;
	}

	/**
	 * Get Authorize By Token
	 *
	 * @param int    $user_id
	 * @param string $token
	 *
	 * @return array<string, mixed>
	 */
	public function getAuthorizeByToken(int $user_id, string $token): array {
		$query = $this->db->query("SELECT *, (SELECT SUM(`total`) FROM `" . DB_PREFIX . "user_authorize` WHERE `user_id` = '" . (int)$user_id . "') AS `attempts` FROM `" . DB_PREFIX . "user_authorize` WHERE `user_id` = '" . (int)$user_id . "' AND `token` = '" . $this->db->escape($token) . "'");

		return $query->row;
	}

	/**
	 * Get Authorizes
	 *
	 * @param int $user_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getAuthorizes(int $user_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_authorize` WHERE `user_id` = '" . (int)$user_id . "' LIMIT " . (int)$start . "," . (int)$limit);

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return [];
		}
	}

	/**
	 * Get Total Authorizes
	 *
	 * @param int $user_id
	 *
	 * @return int
	 */
	public function getTotalAuthorizes(int $user_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "user_authorize` WHERE `user_id` = '" . (int)$user_id . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}
}
