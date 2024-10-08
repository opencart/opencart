<?php
namespace Opencart\Catalog\Model\User;
/**
 * Class Api
 *
 * @package Opencart\Catalog\Model\User
 */
class Api extends \Opencart\System\Engine\Model {
	/**
	 * Get Api By Username
	 *
	 * @param string $username
	 *
	 * @return array<string, mixed>
	 */
	public function getApiByUsername(string $username): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE `username` = '" . $this->db->escape($username) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * Get Ips
	 *
	 * @param int $api_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getIps(int $api_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api_ip` WHERE `api_id` = '" . (int)$api_id . "'");

		return $query->rows;
	}

	/**
	 * Add History
	 *
	 * @param int    $api_id
	 * @param string $call
	 * @param string $ip
	 */
	public function addHistory($api_id, $call, $ip): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "api_history` SET `api_id` = '" . (int)$api_id . "', `call` = '" . $this->db->escape($call) . "', `ip` = '" . $this->db->escape($ip) . "', `date_added` = NOW()");
	}
}
