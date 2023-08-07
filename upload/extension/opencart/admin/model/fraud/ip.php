<?php
namespace Opencart\Admin\Model\Extension\Opencart\Fraud;
/**
 * Class Ip
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Fraud
 */
class Ip extends \Opencart\System\Engine\Model {
	/**
	 * @return void
	 */
	public function install(): void {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fraud_ip` (
		  `ip` varchar(40) NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`ip`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
	}

	/**
	 * @return void
	 */
	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "fraud_ip`");
	}

	/**
	 * @param string $ip
	 *
	 * @return void
	 */
	public function addIp(string $ip): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "fraud_ip` SET `ip` = '" . $this->db->escape($ip) . "', `date_added` = NOW()");
	}

	/**
	 * @param string $ip
	 *
	 * @return void
	 */
	public function removeIp(string $ip): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "fraud_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
	}

	/**
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getIps(int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraud_ip` ORDER BY `ip` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * @return int
	 */
	public function getTotalIps(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "fraud_ip`");

		return (int)$query->row['total'];
	}

	/**
	 * @param string $ip
	 *
	 * @return int
	 */
	public function getTotalIpsByIp(string $ip): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "fraud_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		return (int)$query->row['total'];
	}
}
