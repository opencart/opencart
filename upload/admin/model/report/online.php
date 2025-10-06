<?php
namespace Opencart\Admin\Model\Report;
/**
 * Class Online
 *
 * Can be loaded using $this->load->model('report/online');
 *
 * @package Opencart\Admin\Model\Report
 */
class Online extends \Opencart\System\Engine\Model {
	/**
	 * Get Online
	 *
	 * Get the record of the customer online record in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> online records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_customer' => 'John Doe',
	 *     'filter_ip'       => '',
	 *     'start'           => 0,
	 *     'limit'           => 10
	 * ];
	 *
	 * $results = $this->model_report_online->getOnline($filter_data);
	 */
	public function getOnline(array $data = []): array {
		$sql = "SELECT `co`.`ip`, `co`.`customer_id`, `co`.`url`, `co`.`referer`, `co`.`date_added` FROM `" . DB_PREFIX . "customer_online` `co` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`co`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_ip'])) {
			$implode[] = "`co`.`ip` LIKE '" . $this->db->escape((string)$data['filter_ip']) . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "`co`.`customer_id` > '0' AND LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_customer'])) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY `co`.`date_added` DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Online
	 *
	 * Get the total number of total customer online records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of online records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_customer' => 'John Doe',
	 *     'filter_ip'       => '',
	 *     'start'           => 0,
	 *     'limit'           => 10
	 * ];
	 *
	 * $online_total = $this->model_report_online->getTotalOnline($filter_data);
	 */
	public function getTotalOnline(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_online` `co` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`co`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_ip'])) {
			$implode[] = "`co`.`ip` LIKE '" . $this->db->escape((string)$data['filter_ip']) . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "`co`.`customer_id` > '0' AND LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_customer'])) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
