<?php
namespace Opencart\System\Library\Cart;
/**
 * Class User
 *
 * @package Opencart\System\Library\Cart
 */
class User {
	/**
	 * @var object
	 */
	private object $db;
	/**
	 * @var object
	 */
	private object $request;
	/**
	 * @var object
	 */
	private object $session;
	/**
	 * @var int
	 */
	private int $user_id = 0;
	/**
	 * @var string
	 */
	private string $username = '';
	/**
	 * @var string
	 */
	private string $firstname = '';
	/**
	 * @var string
	 */
	private string $lastname = '';
	/**
	 * @var string
	 */
	private string $email = '';
	/**
	 * @var int
	 */
	private int $user_group_id = 0;
	/**
	 * @var array<string, array<int, string>>
	 */
	private array $permission = [];

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE `user_id` = '" . (int)$this->session->data['user_id'] . "' AND `status` = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];
				$this->firstname = $user_query->row['firstname'];
				$this->lastname = $user_query->row['lastname'];
				$this->email = $user_query->row['email'];
				$this->user_group_id = $user_query->row['user_group_id'];

				$this->db->query("UPDATE `" . DB_PREFIX . "user` SET `ip` = '" . $this->db->escape(oc_get_ip()) . "' WHERE `user_id` = '" . (int)$this->session->data['user_id'] . "'");

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

	/**
	 * Login
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $login = $this->user->login($username, $password);
	 */
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
			$this->firstname = $user_query->row['firstname'];
			$this->lastname = $user_query->row['lastname'];
			$this->email = $user_query->row['email'];
			$this->user_group_id = $user_query->row['user_group_id'];

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

	/**
	 * Logout
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->user->logout();
	 */
	public function logout(): void {
		unset($this->session->data['user_id']);

		$this->user_id = 0;
		$this->username = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->user_group_id = 0;
	}

	/**
	 * Has Permission
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $permission = $this->user->hasPermission();
	 */
	public function hasPermission(string $key, string $value): bool {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}

	/**
	 * Is Logged
	 *
	 * @return bool
	 *
	 * @example
	 *
	 * $logged = $this->user->isLogged();
	 */
	public function isLogged(): bool {
		return $this->user_id ? true : false;
	}

	/**
	 * Get Id
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $user_id = $this->user->getId();
	 */
	public function getId(): int {
		return $this->user_id;
	}

	/**
	 * Get User Name
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $username = $this->user->getUserName();
	 */
	public function getUserName(): string {
		return $this->username;
	}

	/**
	 * Get First Name
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $firstname = $this->user->getFirstName();
	 */
	public function getFirstName(): string {
		return $this->firstname;
	}

	/**
	 * Get Last Name
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $lastname = $this->user->getLastName();
	 */
	public function getLastName(): string {
		return $this->lastname;
	}

	/**
	 * Get Email
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $user = $this->user->getEmail();
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * Get Group Id
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $group_id = $this->user->getGroupId();
	 */
	public function getGroupId(): int {
		return $this->user_group_id;
	}
}
