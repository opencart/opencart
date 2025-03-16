<?php
namespace Opencart\Catalog\Model\Marketing;
/**
 * Class Marketing
 *
 * Can be called using $this->load->model('marketing/marketing');
 *
 * @package Opencart\Catalog\Model\Marketing
 */
class Marketing extends \Opencart\System\Engine\Model {
	/**
	 * Get Marketing By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('marketing/marketing');
	 *
	 * $marketing_info = $this->model_marketing_marketing->getMarketingByCode($code);
	 */
	public function getMarketingByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "marketing` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	/**
	 * Add Report
	 *
	 * Create a new marketing report record in the database.
	 *
	 * @param int    $marketing_id primary key of the marketing record
	 * @param string $ip
	 * @param string $country
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/marketing');
	 *
	 * $this->model_marketing_marketing->addReport($marketing_id, $ip, $country);
	 */
	public function addReport(int $marketing_id, string $ip, string $country = ''): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "marketing_report` SET `marketing_id` = '" . (int)$marketing_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `ip` = '" . $this->db->escape($ip) . "', `country` = '" . $this->db->escape($country) . "', `date_added` = NOW()");
	}
}
