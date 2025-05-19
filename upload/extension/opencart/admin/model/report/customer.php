<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
/**
 * Class Customer
 *
 * Can be called from $this->load->model('extension/opencart/report/customer');
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Report
 */
class Customer extends \Opencart\System\Engine\Model {
	/**
	 * Get Total Customers By Day
	 *
	 * @return array<int, array<string, int>>
	 *
	 * @example
	 *
	 * $customer_total = $this->model_extension_opencart_report_customer->getTotalCustomersByDay();
	 */
	public function getTotalCustomersByDay(): array {
		$customer_data = [];

		for ($i = 0; $i < 24; $i++) {
			$customer_data[$i] = [
				'hour'  => $i,
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total`, HOUR(`date_added`) AS `hour` FROM `" . DB_PREFIX . "customer` WHERE DATE(`date_added`) = DATE(NOW()) GROUP BY HOUR(`date_added`) ORDER BY `date_added` ASC");

		foreach ($query->rows as $result) {
			$customer_data[$result['hour']] = [
				'hour'  => $result['hour'],
				'total' => $result['total']
			];
		}

		return $customer_data;
	}

	/**
	 * Get Total Customers By Week
	 *
	 * @return array<int, array<string, int>>
	 *
	 * @example
	 *
	 * $customer_total = $this->model_extension_opencart_report_customer->getTotalCustomersByWeek();
	 */
	public function getTotalCustomersByWeek(): array {
		$customer_data = [];

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$customer_data[date('w', strtotime($date))] = [
				'day'   => date('D', strtotime($date)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total`, `date_added` FROM `" . DB_PREFIX . "customer` WHERE DATE(`date_added`) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(`date_added`)");

		foreach ($query->rows as $result) {
			$customer_data[date('w', strtotime($result['date_added']))] = [
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $customer_data;
	}

	/**
	 * Get Total Customers By Month
	 *
	 * @return array<int, array<string, int>>
	 *
	 * @example
	 *
	 * $customer_total = $this->model_extension_opencart_report_customer->getTotalCustomersByMonth();
	 */
	public function getTotalCustomersByMonth(): array {
		$customer_data = [];

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$customer_data[date('j', strtotime($date))] = [
				'day'   => date('d', strtotime($date)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total`, `date_added` FROM `" . DB_PREFIX . "customer` WHERE DATE(`date_added`) >= DATE('" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "') GROUP BY DATE(`date_added`)");

		foreach ($query->rows as $result) {
			$customer_data[date('j', strtotime($result['date_added']))] = [
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $customer_data;
	}

	/**
	 * Get Total Customers By Year
	 *
	 * @return array<int, array<string, int>>
	 *
	 * @example
	 *
	 * $customer_total = $this->model_extension_opencart_report_customer->getTotalCustomersByYear();
	 */
	public function getTotalCustomersByYear(): array {
		$customer_data = [];

		for ($i = 1; $i <= 12; $i++) {
			$customer_data[$i] = [
				'month' => date('M', mktime(0, 0, 0, $i, 1)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total`, `date_added` FROM `" . DB_PREFIX . "customer` WHERE YEAR(`date_added`) = YEAR(NOW()) GROUP BY MONTH(`date_added`)");

		foreach ($query->rows as $result) {
			$customer_data[date('n', strtotime($result['date_added']))] = [
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $customer_data;
	}

	/**
	 * Get Customers
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_customer->getCustomers();
	 */
	public function getCustomers(array $data = []): array {
		$sql = "SELECT MIN(`date_added`) AS `date_start`, MAX(`date_added`) AS `date_end`, COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer`";

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

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql .= " GROUP BY YEAR(`date_added`), MONTH(`date_added`), DAY(`date_added`)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(`date_added`), WEEK(`date_added`)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(`date_added`), MONTH(`date_added`)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(`date_added`)";
				break;
		}

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
	 * Get Total Customers
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of customer records
	 *
	 * @example
	 *
	 * $customer_total = $this->model_extension_opencart_report_customer->getTotalCustomers();
	 */
	public function getTotalCustomers(array $data = []): int {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`), MONTH(`date_added`), DAY(`date_added`)) AS `total` FROM `" . DB_PREFIX . "customer`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`), WEEK(`date_added`)) AS `total` FROM `" . DB_PREFIX . "customer`";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`), MONTH(`date_added`)) AS `total` FROM `" . DB_PREFIX . "customer`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`)) AS `total` FROM `" . DB_PREFIX . "customer`";
				break;
		}

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

	/**
	 * Get Orders
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_customer->getOrders();
	 */
	public function getOrders(array $data = []): array {
		$sql = "SELECT `c`.`customer_id`, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `c`.`email`, `cgd`.`name` AS `customer_group`, `c`.`status`, `o`.`order_id`, SUM(`op`.`quantity`) AS `products`, `o`.`total` AS `total` FROM `" . DB_PREFIX . "order` `o` LEFT JOIN `" . DB_PREFIX . "order_product` `op` ON (`o`.`order_id` = `op`.`order_id`) LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`o`.`customer_id` = `c`.`customer_id`) LEFT JOIN `" . DB_PREFIX . "customer_group_description` `cgd` ON (`c`.`customer_group_id` = `cgd`.`customer_group_id`) WHERE `o`.`customer_id` > '0' AND `cgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND `o`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND `o`.`order_status_id` > '0'";
		}

		$sql .= " GROUP BY `o`.`order_id`";

		$sql = "SELECT `t`.`customer_id`, `t`.`customer`, `t`.`email`, `t`.`customer_group`, `t`.`status`, COUNT(DISTINCT `t`.`order_id`) AS `orders`, SUM(`t`.`products`) AS `products`, SUM(`t`.`total`) AS `total` FROM (" . $sql . ") AS `t` GROUP BY `t`.`customer_id` ORDER BY `total` DESC";

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
	 * Get Total Orders
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of order records
	 *
	 * @example
	 *
	 * $order_total = $this->model_extension_opencart_report_customer->getTotalOrders();
	 */
	public function getTotalOrders(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT `o`.`customer_id`) AS `total` FROM `" . DB_PREFIX . "order` `o` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`o`.`customer_id` = `c`.`customer_id`) WHERE `o`.`customer_id` > '0'";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND `o`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND `o`.`order_status_id` > '0'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Reward Points
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_customer->getRewardPoints();
	 */
	public function getRewardPoints(array $data = []): array {
		$sql = "SELECT `cr`.`customer_id`, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `c`.`email`, `cgd`.`name` AS `customer_group`, `c`.`status`, SUM(`cr`.`points`) AS `points`, COUNT(`o`.`order_id`) AS `orders`, SUM(`o`.`total`) AS `total` FROM `" . DB_PREFIX . "customer_reward` `cr` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`cr`.`customer_id` = `c`.`customer_id`) LEFT JOIN `" . DB_PREFIX . "customer_group_description` `cgd` ON (`c`.`customer_group_id` = `cgd`.`customer_group_id`) LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`cr`.`order_id` = `o`.`order_id`) WHERE `cgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`cr`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`cr`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		$sql .= " GROUP BY `cr`.`customer_id` ORDER BY `points` DESC";

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
	 * Get Total Reward Points
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of reward point records
	 *
	 * @example
	 *
	 * $reward_total = $this->model_extension_opencart_report_customer->getTotalRewardPoints();
	 */
	public function getTotalRewardPoints(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT `cr`.`customer_id`) AS `total` FROM `" . DB_PREFIX . "customer_reward` `cr` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`cr`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(`cr`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(`cr`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Customer Activities
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_customer->getCustomerActivities();
	 */
	public function getCustomerActivities(array $data = []): array {
		$sql = "SELECT `ca`.`customer_activity_id`, `ca`.`customer_id`, `ca`.`key`, `ca`.`data`, `ca`.`ip`, `ca`.`date_added` FROM `" . DB_PREFIX . "customer_activity` `ca` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ca`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(`ca`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(`ca`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "`ca`.`ip` LIKE '" . $this->db->escape((string)$data['filter_ip']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY `ca`.`date_added` DESC";

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
	 * Get Total Customer Activities
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of customer activity records
	 *
	 * @example
	 *
	 * $customer_total = $this->model_extension_opencart_report_customer->getTotalCustomerActivities();
	 */
	public function getTotalCustomerActivities(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_activity` `ca` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ca`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(`ca`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(`ca`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "`ca`.`ip` LIKE '" . $this->db->escape((string)$data['filter_ip']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Customer Searches
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_customer->getCustomerSearches();
	 */
	public function getCustomerSearches(array $data = []): array {
		$sql = "SELECT `cs`.`customer_id`, `cs`.`keyword`, `cs`.`category_id`, `cs`.`products`, `cs`.`ip`, `cs`.`date_added`, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer` FROM `" . DB_PREFIX . "customer_search` `cs` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`cs`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(`cs`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(`cs`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_keyword'])) {
			$implode[] = "`cs`.`keyword` LIKE '" . $this->db->escape((string)$data['filter_keyword'] . '%') . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "`cs`.`ip` LIKE '" . $this->db->escape((string)$data['filter_ip']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY `cs`.`date_added` DESC";

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
	 * Get Total Customer Searches
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of customer search records
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_customer->getTotalCustomerSearches();
	 */
	public function getTotalCustomerSearches(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_search` `cs` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`cs`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(`cs`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(`cs`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_keyword'])) {
			$implode[] = "`cs`.`keyword` LIKE '" . $this->db->escape((string)$data['filter_keyword'] . '%') . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "`cs`.`ip` LIKE '" . $this->db->escape((string)$data['filter_ip']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
