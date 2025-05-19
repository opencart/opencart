<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
/**
 * Class Marketing
 *
 * Can be called from $this->load->model('extension/opencart/report/marketing');
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Report
 */
class Marketing extends \Opencart\System\Engine\Model {
	/**
	 * Get Marketing
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_marketing->getMarketing();
	 */
	public function getMarketing(array $data = []): array {
		$sql = "SELECT `m`.`marketing_id`, `m`.`name` AS `campaign`, `m`.`code`, `m`.`clicks` AS `clicks`, (SELECT COUNT(DISTINCT `order_id`) FROM `" . DB_PREFIX . "order` `o1` WHERE `o1`.`marketing_id` = `m`.`marketing_id`";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND `o1`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND `o1`.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o1`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o1`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		$sql .= ") AS `orders`, (SELECT SUM(`total`) FROM `" . DB_PREFIX . "order` `o2` WHERE `o2`.`marketing_id` = `m`.`marketing_id`";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND `o2`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND `o2`.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o2`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o2`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		$sql .= " GROUP BY `o2`.`marketing_id`) AS `total` FROM `" . DB_PREFIX . "marketing` m ORDER BY m.`date_added` ASC";

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
	 * Get Total Marketing
	 *
	 * @param array<mixed> $data array of filters
	 *
	 * @return int total number of marketing records
	 *
	 * @example
	 *
	 * $marketing_total = $this->model_extension_opencart_report_marketing();
	 */
	public function getTotalMarketing(array $data = []): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "marketing`");

		return (int)$query->row['total'];
	}
}
