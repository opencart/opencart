<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Api
 *
 * @package Opencart\Catalog\Model\Account
 */
class Api extends \Opencart\System\Engine\Model {
	/**
	 * @param string $username
	 * @param string $key
	 *
	 * @return array
	 */
	public function login(string $username, string $key): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE `username` = '" . $this->db->escape($username) . "' AND `key` = '" . $this->db->escape($key) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * @param int    $api_id
	 * @param string $session_id
	 * @param string $ip
	 *
	 * @return int
	 */
	public function addSession(int $api_id, string $session_id, string $ip): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "api_session` SET `api_id` = '" . (int)$api_id . "', `session_id` = '" . $this->db->escape($session_id) . "', `ip` = '" . $this->db->escape($ip) . "', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * @param int $api_id
	 *
	 * @return array
	 */
	public function getIps(int $api_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api_ip` WHERE `api_id` = '" . (int)$api_id . "'");

		return $query->rows;
	}
}
