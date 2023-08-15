<?php
namespace Opencart\Catalog\Model\Marketing;
/**
 * Class Marketing
 *
 * @package Opencart\Catalog\Model\Marketing
 */
class Marketing extends \Opencart\System\Engine\Model {
	/**
	 * @param string $code
	 *
	 * @return array
	 */
	public function getMarketingByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "marketing` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	/**
	 * @param int    $marketing_id
	 * @param string $ip
	 * @param string $country
	 *
	 * @return void
	 */
	public function addReport(int $marketing_id, string $ip, string $country = ''): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "marketing_report` SET `marketing_id` = '" . (int)$marketing_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `ip` = '" . $this->db->escape($ip) . "', `country` = '" . $this->db->escape($country) . "', `date_added` = NOW()");
	}
}
