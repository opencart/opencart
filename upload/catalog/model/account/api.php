<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Api
 *
 * @package Opencart\Catalog\Model\Account
 */
class Api extends \Opencart\System\Engine\Model {
	/**
	 * Login
	 *
	 * @param string $username
	 * @param string $key
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
}
