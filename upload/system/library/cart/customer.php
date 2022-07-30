<?php
namespace Opencart\System\Library\Cart;
use \Opencart\System\Helper as Helper;
class Customer {
	private int $customer_id = 0;
	private string $firstname = '';
	private string $lastname = '';
	private int $customer_group_id = 0;
	private string $email = '';
	private string $telephone = '';
	private bool $newsletter = false;

	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['customer_id'])) {
			$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `customer_id` = '" . (int)$this->session->data['customer_id'] . "' AND `status` = '1'");

			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->newsletter = $customer_query->row['newsletter'];

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `language_id` = '" . (int)$this->config->get('config_language_id') . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE `customer_id` = '" . (int)$this->customer_id . "'");
			} else {
				$this->logout();
			}
		}
	}

	public function login(string $email, string $password, bool $override = false): bool {
		$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE LOWER(`email`) = '" . $this->db->escape(Helper\Utf8\strtolower($email)) . "' AND `status` = '1'");

		if ($customer_query->row) {
			if (!$override) {
				if (password_verify($password, $customer_query->row['password'])) {
					$rehash = password_needs_rehash($customer_query->row['password'], PASSWORD_DEFAULT);
				} elseif (isset($customer_query->row['salt']) && $customer_query->row['password'] == sha1($customer_query->row['salt'] . sha1($customer_query->row['salt'] . sha1($password)))) {
					$rehash = true;
				} elseif ($customer_query->row['password'] == md5($password)) {
					$rehash = true;
				} else {
					return false;
				}

				if ($rehash) {
					$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `password` = '" . $this->db->escape(password_hash($password, PASSWORD_DEFAULT)) . "' WHERE `customer_id` = '" . (int)$customer_query->row['customer_id'] . "'");
				}
			}

			$this->session->data['customer_id'] = $customer_query->row['customer_id'];

			$this->customer_id = $customer_query->row['customer_id'];
			$this->firstname = $customer_query->row['firstname'];
			$this->lastname = $customer_query->row['lastname'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->newsletter = $customer_query->row['newsletter'];

			$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `language_id` = '" . (int)$this->config->get('config_language_id') . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE `customer_id` = '" . (int)$this->customer_id . "'");

			return true;
		} else {
			return false;
		}
	}

	public function logout(): void {
		unset($this->session->data['customer_id']);

		$this->customer_id = 0;
		$this->firstname = '';
		$this->lastname = '';
		$this->customer_group_id = 0;
		$this->email = '';
		$this->telephone = '';
		$this->newsletter = false;
	}

	public function isLogged(): bool {
		return $this->customer_id ? true : false;
	}

	public function getId(): int {
		return $this->customer_id;
	}

	public function getFirstName(): string {
		return $this->firstname;
	}

	public function getLastName(): string {
		return $this->lastname;
	}

	public function getGroupId(): int {
		return $this->customer_group_id;
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function getTelephone(): string {
		return $this->telephone;
	}

	public function getNewsletter(): bool {
		return $this->newsletter;
	}

	public function getAddressId(): int {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "address` WHERE `customer_id` = '" . (int)$this->customer_id . "' AND `default` = '1'");

		if ($query->num_rows) {
			return (int)$query->row['address_id'];
		} else {
			return 0;
		}
	}

	public function getBalance(): float {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$this->customer_id . "'");

		return (float)$query->row['total'];
	}

	public function getRewardPoints(): float {
		$query = $this->db->query("SELECT SUM(`points`) AS `total` FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$this->customer_id . "'");

		return (float)$query->row['total'];
	}
}
