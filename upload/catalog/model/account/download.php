<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Download
 *
 * Can be called using $this->load->model('account/download');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Download extends \Opencart\System\Engine\Model {
	/**
	 * Get Download
	 *
	 * Get the record of the download record in the database.
	 *
	 * @param int $download_id primary key of the download record
	 *
	 * @return array<string, mixed> download record that has download ID
	 *
	 * @example
	 *
	 * $this->load->model('account/download');
	 *
	 * $download_info = $this->model_account_download->getDownload($download_id);
	 */
	public function getDownload(int $download_id): array {
		$implode = [];

		$order_statuses = (array)$this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "`o`.`order_status_id` = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT `d`.`filename`, `d`.`mask` FROM `" . DB_PREFIX . "order` `o` LEFT JOIN `" . DB_PREFIX . "order_product` `op` ON (`o`.`order_id` = `op`.`order_id`) LEFT JOIN `" . DB_PREFIX . "product_to_download` `p2d` ON (`op`.`product_id` = `p2d`.`product_id`) LEFT JOIN `" . DB_PREFIX . "download` `d` ON (`p2d`.`download_id` = `d`.`download_id`) WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "' AND (" . implode(" OR ", $implode) . ") AND `d`.`download_id` = '" . (int)$download_id . "'");

			return $query->row;
		}

		return [];
	}

	/**
	 * Get Downloads
	 *
	 * Get the record of the download records in the database.
	 *
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> download records
	 *
	 * @example
	 *
	 * $this->load->model('account/download');
	 *
	 * $results = $this->model_account_download->getDownloads();
	 */
	public function getDownloads(int $start = 0, int $limit = 20): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$implode = [];

		$order_statuses = (array)$this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "`o`.`order_status_id` = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT DISTINCT `d`.`download_id`, `o`.`order_id`, `o`.`date_added`, `dd`.`name`, `d`.`filename` FROM `" . DB_PREFIX . "order` `o` LEFT JOIN `" . DB_PREFIX . "order_product` `op` ON (`o`.`order_id` = `op`.`order_id`) LEFT JOIN `" . DB_PREFIX . "product_to_download` `p2d` ON (`op`.`product_id` = `p2d`.`product_id`) LEFT JOIN `" . DB_PREFIX . "download` `d` ON (`p2d`.`download_id` = `d`.`download_id`) LEFT JOIN `" . DB_PREFIX . "download_description` `dd` ON (`d`.`download_id` = `dd`.`download_id`) WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "' AND `o`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND (" . implode(" OR ", $implode) . ") AND `dd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `d`.`date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

			return $query->rows;
		}

		return [];
	}

	/**
	 * Get Total Downloads
	 *
	 * Get the total number of total download records in the database.
	 *
	 * @return int total number of download records
	 *
	 * @example
	 *
	 * $this->load->model('account/download');
	 *
	 * $download_total = $this->model_account_download->getTotalDownloads();
	 */
	public function getTotalDownloads(): int {
		$implode = [];

		$order_statuses = (array)$this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "`o`.`order_status_id` = '" . (int)$order_status_id . "'";
		}

		if ($implode) {
			$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order` `o` LEFT JOIN `" . DB_PREFIX . "order_product` `op` ON (`o`.`order_id` = `op`.`order_id`) LEFT JOIN `" . DB_PREFIX . "product_to_download` `p2d` ON (`op`.`product_id` = `p2d`.`product_id`) WHERE `o`.`customer_id` = '" . (int)$this->customer->getId() . "' AND `o`.`store_id` = '" . (int)$this->config->get('config_store_id') . "' AND (" . implode(" OR ", $implode) . ") AND `p2d`.`download_id` > '0'");

			return $query->row['total'];
		}

		return 0;
	}

	/**
	 * Add Report
	 *
	 * Create a new download report record in the database.
	 *
	 * @param int    $download_id primary key of the download record
	 * @param string $ip
	 * @param string $country
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/download');
	 *
	 * $this->model_account_download->addReport($download_id, $ip, $country);
	 */
	public function addReport(int $download_id, string $ip, string $country = ''): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "download_report` SET `download_id` = '" . (int)$download_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `ip` = '" . $this->db->escape($ip) . "', `country` = '" . $this->db->escape($country) . "', `date_added` = NOW()");
	}
}
