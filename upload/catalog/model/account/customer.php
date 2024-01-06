<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Customer
 *
 * @package Opencart\Catalog\Model\Account
 */
class Customer extends \Opencart\System\Engine\Model {
	/**
	 * Add Customer
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addCustomer(array $data): int {
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = (int)$data['customer_group_id'];
		} else {
			$customer_group_id = (int)$this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET `customer_group_id` = '" . (int)$customer_group_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', `password` = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', `newsletter` = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `status` = '" . (int)!$customer_group_info['approval'] . "', `date_added` = NOW()");

		$customer_id = $this->db->getLastId();

		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET `customer_id` = '" . (int)$customer_id . "', `type` = 'customer', `date_added` = NOW()");
		}

		return $customer_id;
	}

	/**
	 * Edit Customer
	 *
	 * @param int                  $customer_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editCustomer(int $customer_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Edit Password
	 *
	 * @param string $email
	 * @param string $password
	 *
	 * @return void
	 */
	public function editPassword(string $email, string $password): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `password` = '" . $this->db->escape(password_hash(html_entity_decode($password, ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', `code` = '' WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");
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
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `code` = '" . $this->db->escape($code) . "' WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");
	}

	/**
	 * Edit Token
	 *
	 * @param string $email
	 * @param string $token
	 *
	 * @return void
	 */
	public function editToken(string $email, string $token): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `token` = '" . $this->db->escape($token) . "' WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");
	}

	/**
	 * Edit Newsletter
	 *
	 * @param bool $newsletter
	 *
	 * @return void
	 */
	public function editNewsletter(bool $newsletter): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `newsletter` = '" . (int)$newsletter . "' WHERE `customer_id` = '" . (int)$this->customer->getId() . "'");
	}

	/**
	 * Delete Customer
	 *
	 * @param int $customer_id
	 *
	 * @return void
	 */
	public function deleteCustomer(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_activity` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_affiliate_report` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_history` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_ip` WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "address` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Get Customer
	 *
	 * @param int $customer_id
	 *
	 * @return array<string, mixed>
	 */
	public function getCustomer(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `customer_id` = '" . (int)$customer_id . "'");

		if ($query->num_rows) {
			return $query->row + ['custom_field' => json_decode($query->row['custom_field'], true)];
		} else {
			return [];
		}
	}

	/**
	 * Get Customer By Email
	 *
	 * @param string $email
	 *
	 * @return array<string, mixed>
	 */
	public function getCustomerByEmail(string $email): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");

		if ($query->num_rows) {
			return $query->row + ['custom_field' => json_decode($query->row['custom_field'], true)];
		} else {
			return [];
		}
	}

	/**
	 * Get Customer By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getCustomerByCode(string $code): array {
		$query = $this->db->query("SELECT `customer_id`, `firstname`, `lastname`, `email` FROM `" . DB_PREFIX . "customer` WHERE `code` = '" . $this->db->escape($code) . "' AND `code` != ''");

		if ($query->num_rows) {
			return $query->row + ['custom_field' => json_decode($query->row['custom_field'], true)];
		} else {
			return [];
		}
	}

	/**
	 * Get Customer By Token
	 *
	 * @param string $token
	 *
	 * @return array<string, mixed>
	 */
	public function getCustomerByToken(string $token): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `token` = '" . $this->db->escape($token) . "' AND `token` != ''");

		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `token` = '' WHERE `customer_id` = '" . (int)$query->row['customer_id'] . "'");

			return $query->row + ['custom_field' => json_decode($query->row['custom_field'], true)];
		} else {
			return [];
		}
	}

	/**
	 * Get Total Customers By Email
	 *
	 * @param string $email
	 *
	 * @return int
	 */
	public function getTotalCustomersByEmail(string $email): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer` WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Transaction
	 *
	 * @param int    $customer_id
	 * @param string $description
	 * @param float  $amount
	 * @param int    $order_id
	 *
	 * @return void
	 */
	public function addTransaction(int $customer_id, string $description, float $amount = 0, int $order_id = 0): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_transaction` SET `customer_id` = '" . (int)$customer_id . "', `order_id` = '" . (int)$order_id . "', `description` = '" . $this->db->escape($description) . "', `amount` = '" . (float)$amount . "', `date_added` = NOW()");
	}

	/**
	 * Delete Transaction By Order ID
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteTransactionByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * Get Transaction Total
	 *
	 * @param int $customer_id
	 *
	 * @return float
	 */
	public function getTransactionTotal(int $customer_id): float {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (float)$query->row['total'];
	}

	/**
	 * Get Total Transactions By Order ID
	 *
	 * @param int $order_id
	 *
	 * @return int
	 */
	public function getTotalTransactionsByOrderId(int $order_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Reward Total
	 *
	 * @param int $customer_id
	 *
	 * @return int
	 */
	public function getRewardTotal(int $customer_id): int {
		$query = $this->db->query("SELECT SUM(`points`) AS `total` FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Ips
	 *
	 * @param int $customer_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getIps(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Ips
	 *
	 * @param int $customer_id
	 *
	 * @return int
	 */
	public function getTotalIps(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_ip` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Login
	 *
	 * @param int    $customer_id
	 * @param string $ip
	 * @param string $country
	 *
	 * @return void
	 */
	public function addLogin(int $customer_id, string $ip, string $country = ''): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_ip` SET `customer_id` = '" . (int)$customer_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `ip` = '" . $this->db->escape($ip) . "', `country` = '" . $this->db->escape($country) . "', `date_added` = NOW()");
	}

	/**
	 * Add Login Attempt
	 *
	 * @param string $email
	 *
	 * @return void
	 */
	public function addLoginAttempt(string $email): void {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower((string)$email)) . "' AND `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_login` SET `email` = '" . $this->db->escape(oc_strtolower((string)$email)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `total` = '1', `date_added` = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', `date_modified` = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE `" . DB_PREFIX . "customer_login` SET `total` = (`total` + 1), `date_modified` = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE `customer_login_id` = '" . (int)$query->row['customer_login_id'] . "'");
		}
	}

	/**
	 * Get Login Attempts
	 *
	 * @param string $email
	 *
	 * @return array<string, mixed>
	 */
	public function getLoginAttempts(string $email): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");

		return $query->row;
	}

	/**
	 * Delete Login Attempts
	 *
	 * @param string $email
	 *
	 * @return void
	 */
	public function deleteLoginAttempts(string $email): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");
	}

	/**
	 * Add Authorize
	 *
	 * @param int                  $customer_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addAuthorize(int $customer_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_authorize` SET `customer_id` = '" . (int)$customer_id . "', `token` = '" . $this->db->escape($data['token']) . "', `ip` = '" . $this->db->escape($data['ip']) . "', `user_agent` = '" . $this->db->escape($data['user_agent']) . "', `date_added` = NOW()");
	}

	/**
	 * Edit Authorize Status
	 *
	 * @param int  $customer_authorize_id
	 * @param bool $status
	 *
	 * @return void
	 */
	public function editAuthorizeStatus(int $customer_authorize_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_authorize` SET `status` = '" . (bool)$status . "' WHERE `customer_authorize_id` = '" . (int)$customer_authorize_id . "'");
	}

	/**
	 * Edit Authorize Total
	 *
	 * @param int $customer_authorize_id
	 * @param int $total
	 *
	 * @return void
	 */
	public function editAuthorizeTotal(int $customer_authorize_id, int $total): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_authorize` SET `total` = '" . (int)$total . "' WHERE `customer_authorize_id` = '" . (int)$customer_authorize_id . "'");
	}

	/**
	 * Delete Authorize
	 *
	 * @param int $customer_authorize_id
	 *
	 * @return void
	 */
	public function deleteAuthorize(int $customer_authorize_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_authorize` WHERE `customer_authorize_id` = '" . (int)$customer_authorize_id . "'");
	}

	/**
	 * Get Authorize By Token
	 *
	 * @param int    $customer_id
	 * @param string $token
	 *
	 * @return array<string, mixed>
	 */
	public function getAuthorizeByToken(int $customer_id, string $token): array {
		$query = $this->db->query("SELECT *, (SELECT SUM(`total`) FROM `" . DB_PREFIX . "customer_authorize` WHERE `customer_id` = '" . (int)$customer_id . "') AS `attempts` FROM `" . DB_PREFIX . "customer_authorize` WHERE `customer_id` = '" . (int)$customer_id . "' AND `token` = '" . $this->db->escape($token) . "'");

		return $query->row;
	}

	/**
	 * Reset Authorizes
	 *
	 * @param int $customer_id
	 *
	 * @return void
	 */
	public function resetAuthorizes(int $customer_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_authorize` SET `total` = '0' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}
}
