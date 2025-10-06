<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
/**
 * Class Coupon
 *
 * Can be called from $this->load->model('extension/opencart/report/coupon');
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Report
 */
class Coupon extends \Opencart\System\Engine\Model {
	/**
	 * Get Coupons
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_coupon->getCoupons();
	 */
	public function getCoupons(array $data = []): array {
		$sql = "SELECT `ch`.`coupon_id`, `c`.`name`, `c`.`code`, COUNT(DISTINCT `ch`.`order_id`) AS `orders`, SUM(`ch`.`amount`) AS `total` FROM `" . DB_PREFIX . "coupon_history` `ch` LEFT JOIN `" . DB_PREFIX . "coupon` `c` ON (`ch`.`coupon_id` = `c`.`coupon_id`)";

		$implode = [];

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(`ch`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(`ch`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " GROUP BY `ch`.`coupon_id` ORDER BY `total` DESC";

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
	 * Get Total Coupons
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of coupon records
	 *
	 * @example
	 *
	 * $coupon_total = $this->model_extension_opencart_report_coupon->getTotalCoupons()
	 */
	public function getTotalCoupons(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT `coupon_id`) AS `total` FROM `" . DB_PREFIX . "coupon_history`";

		$implode = [];

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
