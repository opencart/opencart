<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
/**
 * Class Sale
 *
 * Can be called from $this->load->model('extension/opencart/report/sale');
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Report
 */
class Sale extends \Opencart\System\Engine\Model {
	/**
	 * Get Total Sales
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return float total number of sale records
	 *
	 * @example
	 *
	 * $sale_total = $this->model_extension_opencart_report_sale->getTotalSales();
	 */
	public function getTotalSales(array $data = []): float {
		$sql = "SELECT SUM(`total`) AS `total` FROM `" . DB_PREFIX . "order` WHERE `order_status_id` > '0'";

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(`date_added`) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		$query = $this->db->query($sql);

		return (float)$query->row['total'];
	}

	/**
	 * Get Total Orders By Country
	 *
	 * @return array<int, array<string, mixed>> total number of order records by country
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_dashboard_map->getTotalOrdersByCountry();
	 */
	public function getTotalOrdersByCountry(): array {
		$query = $this->db->query("SELECT COUNT(*) AS `total`, SUM(`o`.`total`) AS `amount`, `c`.`iso_code_2` FROM `" . DB_PREFIX . "order` `o` LEFT JOIN `" . DB_PREFIX . "country` `c` ON (`o`.`payment_country_id` = `c`.`country_id`) WHERE `o`.`order_status_id` > '0' GROUP BY `o`.`payment_country_id`");

		return $query->rows;
	}

	/**
	 * Get Total Orders By Day
	 *
	 * @return array<int, array<string, int>> total number of order records by day
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_sale->getTotalOrdersByDay();
	 */
	public function getTotalOrdersByDay(): array {
		$implode = [];

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = [];

		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = [
				'hour'  => $i,
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total`, HOUR(`date_added`) AS hour FROM `" . DB_PREFIX . "order` WHERE `order_status_id` IN(" . implode(",", $implode) . ") AND DATE(`date_added`) = DATE(NOW()) GROUP BY HOUR(`date_added`) ORDER BY `date_added` ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = [
				'hour'  => $result['hour'],
				'total' => $result['total']
			];
		}

		return $order_data;
	}

	/**
	 * Get Total Orders By Week
	 *
	 * @return array<int, array<string, int>> total number of order records by week
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_sale->getTotalOrdersByWeek();
	 */
	public function getTotalOrdersByWeek(): array {
		$implode = [];

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = [];

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$order_data[date('w', strtotime($date))] = [
				'day'   => date('D', strtotime($date)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total`, `date_added` FROM `" . DB_PREFIX . "order` WHERE `order_status_id` IN(" . implode(",", $implode) . ") AND DATE(`date_added`) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(`date_added`)");

		foreach ($query->rows as $result) {
			$order_data[date('w', strtotime($result['date_added']))] = [
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $order_data;
	}

	/**
	 * Get Total Orders By Month
	 *
	 * @return array<int, array<string, int>> total number of order records by month
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_sale->getTotalOrdersByMonth();
	 */
	public function getTotalOrdersByMonth(): array {
		$implode = [];

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = [];

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$order_data[date('j', strtotime($date))] = [
				'day'   => date('d', strtotime($date)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total`, `date_added` FROM `" . DB_PREFIX . "order` WHERE `order_status_id` IN(" . implode(",", $implode) . ") AND DATE(`date_added`) >= DATE('" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "') GROUP BY DATE(`date_added`)");

		foreach ($query->rows as $result) {
			$order_data[date('j', strtotime($result['date_added']))] = [
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $order_data;
	}

	/**
	 * Get Total Orders By Year
	 *
	 * @return array<int, array<string, int>> total number of order records by year
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_sale->getTotalOrdersByYear();
	 */
	public function getTotalOrdersByYear(): array {
		$implode = [];

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = [];

		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = [
				'month' => date('M', mktime(0, 0, 0, $i, 1)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total`, `date_added` FROM `" . DB_PREFIX . "order` WHERE `order_status_id` IN(" . implode(",", $implode) . ") AND YEAR(`date_added`) = YEAR(NOW()) GROUP BY MONTH(`date_added`)");

		foreach ($query->rows as $result) {
			$order_data[date('n', strtotime($result['date_added']))] = [
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $order_data;
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
	 * $results = $this->model_extension_opencart_report_sale->getOrders();
	 */
	public function getOrders(array $data = []): array {
		$sql = "SELECT MIN(`o`.`date_added`) AS `date_start`, MAX(`o`.`date_added`) AS `date_end`, COUNT(*) AS `orders`, SUM((SELECT SUM(`op`.`quantity`) FROM `" . DB_PREFIX . "order_product` `op` WHERE `op`.`order_id` = `o`.`order_id` GROUP BY `op`.`order_id`)) AS `products`, SUM((SELECT SUM(`ot`.`value`) FROM `" . DB_PREFIX . "order_total` `ot` WHERE `ot`.`order_id` = `o`.`order_id` AND `ot`.`code` = 'tax' GROUP BY `ot`.`order_id`)) AS `tax`, SUM(`o`.`total`) AS `total` FROM `" . DB_PREFIX . "order` `o`";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE `o`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE `o`.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), MONTH(`o`.`date_added`), DAY(`o`.`date_added`)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), WEEK(`o`.`date_added`)";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), MONTH(`o`.`date_added`)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(`o`.`date_added`)";
				break;
		}

		$sql .= " ORDER BY `o`.`date_added` DESC";

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
	 * $order_total = $this->model_extension_opencart_report_sale->getTotalOrders();
	 */
	public function getTotalOrders(array $data = []): int {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`), MONTH(`date_added`), DAY(`date_added`)) AS `total` FROM `" . DB_PREFIX . "order`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`), WEEK(`date_added`)) AS `total` FROM `" . DB_PREFIX . "order`";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`), MONTH(`date_added`)) AS `total` FROM `" . DB_PREFIX . "order`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(`date_added`)) AS `total` FROM `" . DB_PREFIX . "order`";
				break;
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE `order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE `order_status_id` > '0'";
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

	/**
	 * Get Taxes
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_sale->getTaxes();
	 */
	public function getTaxes(array $data = []): array {
		$sql = "SELECT MIN(`o`.`date_added`) AS `date_start`, MAX(`o`.`date_added`) AS `date_end`, `ot`.`title`, SUM(`ot`.`value`) AS `total`, COUNT(`o`.`order_id`) AS `orders` FROM `" . DB_PREFIX . "order` `o` LEFT JOIN `" . DB_PREFIX . "order_total` `ot` ON (`ot`.`order_id` = `o`.`order_id`) WHERE `ot`.`code` = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND `o`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND `o`.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), MONTH(`o`.`date_added`), DAY(`o`.`date_added`), `ot`.`title`";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), WEEK(`o`.`date_added`), `ot`.`title`";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), MONTH(`o`.`date_added`), `ot`.`title`";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), ot.`title`";
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
	 * Get Total Taxes
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of tax records
	 *
	 * @example
	 *
	 * $tax_total = $this->model_extension_opencart_report_sale->getTotalTaxes();
	 */
	public function getTotalTaxes(array $data = []): int {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql = "SELECT COUNT(DISTINCT YEAR(`o`.`date_added`), MONTH(`o`.`date_added`), DAY(`o`.`date_added`), `ot`.`title`) AS `total` FROM `" . DB_PREFIX . "order` `o`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(`o`.`date_added`), WEEK(`o`.`date_added`), `ot`.`title`) AS `total` FROM `" . DB_PREFIX . "order` `o`";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(`o`.`date_added`), MONTH(`o`.`date_added`), `ot`.`title`) AS `total` FROM `" . DB_PREFIX . "order` `o`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(`o`.`date_added`), `ot`.`title`) AS `total` FROM `" . DB_PREFIX . "order` `o`";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` `ot` ON (`o`.`order_id` = `ot`.`order_id`) WHERE `ot`.`code` = 'tax'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND `o`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND `o`.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Shipping
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_sale->getShipping();
	 */
	public function getShipping(array $data = []): array {
		$sql = "SELECT MIN(`o`.`date_added`) AS `date_start`, MAX(`o`.`date_added`) AS `date_end`, `ot`.`title`, SUM(`ot`.`value`) AS `total`, COUNT(`o`.`order_id`) AS `orders` FROM `" . DB_PREFIX . "order` `o` LEFT JOIN `" . DB_PREFIX . "order_total` `ot` ON (`o`.`order_id` = `ot`.`order_id`) WHERE `ot`.`code` = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND `o`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND `o`.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), MONTH(`o`.`date_added`), DAY(`o`.`date_added`), `ot`.`title`";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), WEEK(`o`.`date_added`), `ot`.`title`";
				break;
			case 'month':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), MONTH(`o`.`date_added`), `ot`.`title`";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(`o`.`date_added`), `ot`.`title`";
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
	 * Get Total Shipping
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of shipping records
	 *
	 * @example
	 *
	 * $shipping_total = $this->model_extension_opencart_report_sale->getTotalShipping();
	 */
	public function getTotalShipping(array $data = []): int {
		if (!empty($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}

		switch ($group) {
			case 'day':
				$sql = "SELECT COUNT(DISTINCT YEAR(`o`.`date_added`), MONTH(`o`.`date_added`), DAY(`o`.`date_added`), `ot`.`title`) AS `total` FROM `" . DB_PREFIX . "order` `o`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT YEAR(`o`.`date_added`), WEEK(`o`.`date_added`), `ot`.`title`) AS `total` FROM `" . DB_PREFIX . "order` `o`";
				break;
			case 'month':
				$sql = "SELECT COUNT(DISTINCT YEAR(`o`.`date_added`), MONTH(`o`.`date_added`), `ot`.`title`) AS `total` FROM `" . DB_PREFIX . "order` `o`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(`o`.`date_added`), `ot`.`title`) AS `total` FROM `" . DB_PREFIX . "order` `o`";
				break;
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "order_total` `ot` ON (`o`.`order_id` = ot.`order_id`) WHERE `ot`.`code` = 'shipping'";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND `o`.order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND `o`.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
