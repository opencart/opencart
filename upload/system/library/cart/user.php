<?php
namespace Opencart\System\Library\Cart;
class User {
	private int $user_id = 0;
	private string $username = '';
	private int $user_group_id = 0;
	private string $email = '';
	private array $permission = [];

	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE `user_id` = '" . (int)$this->session->data['user_id'] . "' AND `status` = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];
				$this->user_group_id = $user_query->row['user_group_id'];
				$this->email = $user_query->row['email'];

				$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE `user_id` = '" . (int)$this->session->data['user_id'] . "'");

				$user_group_query = $this->db->query("SELECT `permission` FROM `" . DB_PREFIX . "user_group` WHERE `user_group_id` = '" . (int)$user_query->row['user_group_id'] . "'");

				$permissions = json_decode($user_group_query->row['permission'], true);

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login(string $username, string $password): bool {
		$user_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE `username` = '" . $this->db->escape($username) . "' AND `status` = '1'");

		if ($user_query->num_rows) {
			if (password_verify($password, $user_query->row['password'])) {
				$rehash = password_needs_rehash($user_query->row['password'], PASSWORD_DEFAULT);
			} elseif (isset($user_query->row['salt']) && $user_query->row['password'] == sha1($user_query->row['salt'] . sha1($user_query->row['salt'] . sha1($password)))) {
				$rehash = true;
			} elseif ($user_query->row['password'] == md5($password)) {
				$rehash = true;
			} else {
				return false;
			}

			if ($rehash) {
				$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `password` = '" . $this->db->escape(password_hash($password, PASSWORD_DEFAULT)) . "' WHERE `user_id` = '" . (int)$user_query->row['user_id'] . "'");
			}

			$this->session->data['user_id'] = $user_query->row['user_id'];

			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];
			$this->user_group_id = $user_query->row['user_group_id'];
			$this->email = $user_query->row['email'];

			$user_group_query = $this->db->query("SELECT `permission` FROM `" . DB_PREFIX . "user_group` WHERE `user_group_id` = '" . (int)$user_query->row['user_group_id'] . "'");

			$permissions = json_decode($user_group_query->row['permission'], true);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} else {
			return false;
		}
	}

	public function logout(): void {
		unset($this->session->data['user_id']);

		$this->user_id = 0;
		$this->username = '';
		$this->user_group_id = 0;
		$this->email = '';
	}

	public function hasPermission(string $key, mixed $value): bool {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}

	public function isLogged(): bool {
		return $this->user_id ? true : false;
	}

	public function getId(): int {
		return $this->user_id;
	}

	public function getUserName(): string {
		return $this->username;
	}

	public function getGroupId(): int {
		return $this->user_group_id;
	}

	public function getEmail(): string {
		return $this->email;
	}
}
