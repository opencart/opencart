<?php
namespace Opencart\Catalog\Model\User;
/**
 * Class Api
 *
 * Can be called using $this->load->model('user/api');
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
	 *
	 * @example
	 *
	 * $this->load->model('user/api');
	 *
	 * $api_info = $this->model_user_api->getApiByUsername($username);
	 */
	public function getApiByUsername(string $username): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE `username` = '" . $this->db->escape($username) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * Get Ips
	 *
	 * Get the record of the api ip records in the database.
	 *
	 * @param int $api_id primary key of the Api record
	 *
	 * @return array<int, array<string, mixed>> ip records that have api ID
	 *
	 * @example
	 *
	 * $this->load->model('user/api');
	 *
	 * $results = $this->model_user_api->getIps($api_id);
	 */
	public function getIps(int $api_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api_ip` WHERE `api_id` = '" . (int)$api_id . "'");

		return $query->rows;
	}

	/**
	 * Add History
	 *
	 * Create a new api history record in the database.
	 *
	 * @param int    $api_id primary key of the Api record
	 * @param string $call
	 * @param string $ip
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('user/api');
	 *
	 * $this->model_user_api->addHistory($api_id, $call, $ip);
	 */
	public function addHistory(int $api_id, string $call, string $ip): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "api_history` SET `api_id` = '" . (int)$api_id . "', `call` = '" . $this->db->escape($call) . "', `ip` = '" . $this->db->escape($ip) . "', `date_added` = NOW()");
	}
}
