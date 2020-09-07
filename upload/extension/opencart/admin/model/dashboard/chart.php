<?php
namespace Opencart\Application\Model\Extension\Opencart\Dashboard;
class Chart extends \Opencart\System\Engine\Model {
	public function getTotalOrdersByDay() {
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

		$query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) = DATE(NOW()) GROUP BY HOUR(date_added) ORDER BY date_added ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = [
				'hour'  => $result['hour'],
				'total' => $result['total']
			];
		}

		return $order_data;
	}

	public function getTotalOrdersByWeek() {
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

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('w', strtotime($result['date_added']))] = [
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $order_data;
	}

	public function getTotalOrdersByMonth() {
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

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('j', strtotime($result['date_added']))] = [
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $order_data;
	}

	public function getTotalOrdersByYear() {
		$implode = [];

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}

		$order_data = [];

		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = [
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id IN(" . implode(",", $implode) . ") AND YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('n', strtotime($result['date_added']))] = [
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $order_data;
	}
	
	public function getTotalCustomersByDay() {
		$customer_data = [];

		for ($i = 0; $i < 24; $i++) {
			$customer_data[$i] = [
				'hour'  => $i,
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour FROM `" . DB_PREFIX . "customer` WHERE DATE(date_added) = DATE(NOW()) GROUP BY HOUR(date_added) ORDER BY date_added ASC");

		foreach ($query->rows as $result) {
			$customer_data[$result['hour']] = [
				'hour'  => $result['hour'],
				'total' => $result['total']
			];
		}

		return $customer_data;
	}

	public function getTotalCustomersByWeek() {
		$customer_data = [];

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));

			$customer_data[date('w', strtotime($date))] = [
				'day'   => date('D', strtotime($date)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "customer` WHERE DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(date_added)");

		foreach ($query->rows as $result) {
			$customer_data[date('w', strtotime($result['date_added']))] = [
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $customer_data;
	}

	public function getTotalCustomersByMonth() {
		$customer_data = [];

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$customer_data[date('j', strtotime($date))] = [
				'day'   => date('d', strtotime($date)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "customer` WHERE DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");

		foreach ($query->rows as $result) {
			$customer_data[date('j', strtotime($result['date_added']))] = [
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $customer_data;
	}

	public function getTotalCustomersByYear() {
		$customer_data = [];

		for ($i = 1; $i <= 12; $i++) {
			$customer_data[$i] = [
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			];
		}

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "customer` WHERE YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)");

		foreach ($query->rows as $result) {
			$customer_data[date('n', strtotime($result['date_added']))] = [
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			];
		}

		return $customer_data;
	}	
}