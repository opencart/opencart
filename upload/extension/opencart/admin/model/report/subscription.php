<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
/**
 * Class Subscription
 *
 * @example
 *
 * $this->load->model('extension/opencart/report/subscription');
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Report
 */
class Subscription extends \Opencart\System\Engine\Model {
	/**
	 * Get Subscriptions
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $this->load->model('extension/opencart/report/subscription');
	 *
	 * $results = $this->model_extension_opencart_report_subscription->getSubscriptions();
	 */
	public function getSubscriptions(array $data = []): array {
		$sql = "SELECT MIN(`s`.`date_added`) AS `date_start`, MAX(`s`.`date_added`) AS `date_end`, COUNT(*) AS `subscriptions`, SUM((SELECT `quantity` FROM `" . DB_PREFIX . "subscription_product` `sp` WHERE `sp`.`subscription_id` = `s`.`subscription_id`)) AS `products`, SUM(`s`.`tax`) AS `tax`, SUM(`s`.`price`) AS `total` FROM `" . DB_PREFIX . "subscription` `s`";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE `s`.`subscription_status_id` = '" . (int)$data['filter_subscription_status_id'] . "'";
		} else {
			$sql .= " WHERE `s`.`subscription_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`s`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`s`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql .= " GROUP BY YEAR(`date_added`), MONTH(`s`.`date_added`), DAY(`s`.`date_added`)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(`date_added`), WEEK(`s`.`date_added`)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(`date_added`), MONTH(`s`.`date_added`)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(`date_added`)";
				break;
		}

		$sql .= " ORDER BY `date_added` DESC";

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
	 * Get Total Subscriptions
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of subscription records
	 *
	 * @example
	 *
	 * $this->load->model('extension/opencart/report/subscription');
	 *
	 * $subscription_total = $this->model_extension_opencart_report_subscription->getTotalSubscriptions();
	 */
	public function getTotalSubscriptions(array $data = []): int {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`), MONTH(`date_added`), DAY(`date_added`)) AS `total` FROM `" . DB_PREFIX . "subscription`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`), WEEK(`date_added`)) AS `total` FROM `" . DB_PREFIX . "subscription`";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`), MONTH(`date_added`)) AS `total` FROM `" . DB_PREFIX . "subscription`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`)) AS `total` FROM `" . DB_PREFIX . "subscription`";
				break;
		}

		if (!empty($data['filter_subscription_status_id'])) {
			$sql .= " WHERE `subscription_status_id` = '" . (int)$data['filter_subscription_status_id'] . "'";
		} else {
			$sql .= " WHERE `subscription_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
