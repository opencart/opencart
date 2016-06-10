<?php
class ModelExtensionPaymentLaybuy extends Model {
	public function addRevisedTransaction($data = array()) {
		$query = $this->db->query("INSERT INTO `" . DB_PREFIX . "laybuy_revise_request` SET `laybuy_transaction_id` = '" . (int)$data['transaction_id'] . "', `type` = '" . $this->db->escape($data['type']) . "', `order_id` = '" . (int)$data['order_id'] . "', `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `address` = '" . $this->db->escape($data['address']) . "', `suburb` = '" . $this->db->escape($data['suburb']) . "', `state` = '" . $this->db->escape($data['state']) . "', `country` = '" . $this->db->escape($data['country']) . "', `postcode` = '" . $this->db->escape($data['postcode']) . "', `email` = '" . $this->db->escape($data['email']) . "', `amount` = '" . (float)$data['amount'] . "', `currency` = '" . $this->db->escape($data['currency']) . "', `downpayment` = '" . $this->db->escape($data['downpayment']) . "', `months` = '" . (int)$data['months'] . "', `downpayment_amount` = '" . (float)$data['downpayment_amount'] . "', `payment_amounts` = '" . (float)$data['payment_amounts'] . "', `first_payment_due` = '" . $this->db->escape($data['first_payment_due']) . "', `last_payment_due` = '" . $this->db->escape($data['last_payment_due']) . "', `store_id` = '" . (int)$data['store_id'] . "', `status` = '" . (int)$data['status'] . "', `report` = '" . $this->db->escape($data['report']) . "', `transaction` = '" . (int)$data['transaction'] . "', `paypal_profile_id` = '" . $this->db->escape($data['paypal_profile_id']) . "', `laybuy_ref_no` = '" . (int)$data['laybuy_ref_no'] . "', `payment_type` = '" . (int)$data['payment_type'] . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	public function getCustomerIdByOrderId($order_id) {
		$query = $this->db->query("SELECT `customer_id` FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "'");

		if ($query->num_rows) {
			return $query->row['customer_id'];
		} else {
			return 0;
		}
	}

	public function getInitialPayments() {
		$minimum = $this->config->get('laybuy_min_deposit') ? $this->config->get('laybuy_min_deposit') : 20;

		$maximum = $this->config->get('laybuy_max_deposit') ? $this->config->get('laybuy_max_deposit') : 50;

		$initial_payments = array();

		for ($i = $minimum; $i <= $maximum; $i += 10) {
			$initial_payments[] = $i;
		}

		return $initial_payments;
	}

	public function getMonths() {
		$this->load->language('extension/payment/laybuy');

		$max_months = $this->config->get('laybuy_max_months');

		if (!$max_months) {
			$max_months = 3;
		}

		if ($max_months < 1) {
			$max_months = 1;
		}

		$months = array();

		for ($i = 1; $i <= $max_months; $i++) {
			$months[] = array(
				'value' => $i,
				'label' => $i . ' ' . (($i > 1) ? $this->language->get('text_months') : $this->language->get('text_month'))
			);
		}

		return $months;
	}

	public function getPayPalProfileIds() {
		$query = $this->db->query("SELECT `paypal_profile_id` FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `status` = '1'");

		return $query->rows;
	}

	public function getRevisedTransaction($id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_revise_request` WHERE `laybuy_revise_request_id` = '" . (int)$id . "'");

		return $query->row;
	}

	public function getRemainingAmount($amount, $downpayment_amount, $payment_amounts, $transaction = 2) {
		return $amount - ($downpayment_amount + (((int)$transaction - 2) * $payment_amounts));
	}

	public function getRevisedTransactions($id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_revise_request` WHERE `laybuy_revise_request_id` = '" . (int)$id . "'");

		return $query->rows;
	}

	public function getStatusLabel($id) {
		$statuses = $this->getTransactionStatuses();

		foreach ($statuses as $status) {
			if ($status['status_id'] == $id && $status['status_name'] != '') {
				return $status['status_name'];

				break;
			}
		}

		return $id;
	}

	public function getTransaction($id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `laybuy_transaction_id` = '" . (int)$id . "'");

		return $query->row;
	}

	public function getTransactions($data = array()) {
		$sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS `customer` FROM `" . DB_PREFIX . "laybuy_transaction` `lt` WHERE 1 = 1";

		$implode = array();

		if (!empty($data['filter_order_id'])) {
			$implode[] = "`lt`.`order_id` = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_dp_percent'])) {
			$implode[] = "`lt`.`downpayment` = '" . (int)$data['filter_dp_percent'] . "'";
		}

		if (!empty($data['filter_months'])) {
			$implode[] = "`lt`.`months` = '" . (int)$data['filter_months'] . "'";
		}

		if (!empty($data['filter_status'])) {
			$implode[] = "`lt`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(`lt`.`date_added`) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'lt.order_id',
			'customer',
			'lt.amount',
			'lt.downpayment',
			'lt.months',
			'lt.downpayment_amount',
			'lt.first_payment_due',
			'lt.last_payment_due',
			'lt.status',
			'lt.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['sort']) && $data['sort'] != 'lt.date_added') {
			$sql .= ", lt.date_added DESC";
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

	public function getTotalTransactions($data = array()) {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "laybuy_transaction` `lt` WHERE 1 = 1";

		$implode = array();

		if (!empty($data['filter_order_id'])) {
			$implode[] = "`lt`.`order_id` = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_dp_percent'])) {
			$implode[] = "`lt`.`downpayment` = '" . (int)$data['filter_dp_percent'] . "'";
		}

		if (!empty($data['filter_months'])) {
			$implode[] = "`lt`.`months` = '" . (int)$data['filter_months'] . "'";
		}

		if (!empty($data['filter_status'])) {
			$implode[] = "`lt`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(`lt`.`date_added`) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTransactionByLayBuyRefId($laybuy_ref_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `laybuy_ref_no` = '" . (int)$laybuy_ref_id . "'");

		return $query->row;
	}

	public function getTransactionByOrderId($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY `laybuy_ref_no` DESC LIMIT 1");

		return $query->row;
	}

	public function getTransactionStatuses() {
		$this->load->language('extension/payment/laybuy');

		$transaction_statuses = array(
			array(
				'status_id'		=> 1,
				'status_name'	=> $this->language->get('text_status_1')
			),
			array(
				'status_id'		=> 5,
				'status_name'	=> $this->language->get('text_status_5')
			),
			array(
				'status_id'		=> 7,
				'status_name'	=> $this->language->get('text_status_7')
			),
			array(
				'status_id'		=> 50,
				'status_name'	=> $this->language->get('text_status_50')
			),
			array(
				'status_id'		=> 51,
				'status_name'	=> $this->language->get('text_status_51')
			)
		);

		return $transaction_statuses;
	}

	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "laybuy_transaction` (
			`laybuy_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
			`order_id` int(11) NOT NULL DEFAULT '0',
			`firstname` varchar(32) NOT NULL DEFAULT '',
			`lastname` varchar(32) NOT NULL DEFAULT '',
			`address` text,
			`suburb` varchar(128) NOT NULL DEFAULT '',
			`state` varchar(128) NOT NULL DEFAULT '',
			`country` varchar(32) NOT NULL DEFAULT '',
			`postcode` varchar(10) NOT NULL DEFAULT '',
			`email` varchar(96) NOT NULL DEFAULT '',
			`amount` double NOT NULL,
			`currency` varchar(5) NOT NULL,
			`downpayment` double NOT NULL,
			`months` int(11) NOT NULL,
			`downpayment_amount` double NOT NULL,
			`payment_amounts` double NOT NULL,
			`first_payment_due` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			`last_payment_due` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			`store_id` int(11) NOT NULL DEFAULT '0',
			`status` int(11) NOT NULL DEFAULT '1',
			`report` text,
			`transaction` int(11) NOT NULL DEFAULT '2',
			`paypal_profile_id` varchar(250) NOT NULL DEFAULT '',
			`laybuy_ref_no` int(11) NOT NULL DEFAULT '0',
			`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`laybuy_transaction_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "laybuy_revise_request` (
			`laybuy_revise_request_id` int(11) NOT NULL AUTO_INCREMENT,
			`laybuy_transaction_id` int(11) DEFAULT '0',
			`type` varchar(250) NOT NULL DEFAULT '',
			`order_id` int(11) NOT NULL DEFAULT '0',
			`firstname` varchar(32) NOT NULL DEFAULT '',
			`lastname` varchar(32) NOT NULL DEFAULT '',
			`address` text,
			`suburb` varchar(128) NOT NULL DEFAULT '',
			`state` varchar(128) NOT NULL DEFAULT '',
			`country` varchar(32) NOT NULL DEFAULT '',
			`postcode` varchar(10) NOT NULL DEFAULT '',
			`email` varchar(96) NOT NULL DEFAULT '',
			`amount` double NOT NULL,
			`currency` varchar(5) NOT NULL,
			`downpayment` double NOT NULL,
			`months` int(11) NOT NULL,
			`downpayment_amount` double NOT NULL,
			`payment_amounts` double NOT NULL,
			`first_payment_due` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			`last_payment_due` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			`store_id` int(11) NOT NULL DEFAULT '0',
			`status` int(11) NOT NULL DEFAULT '1',
			`report` text,
			`transaction` int(11) NOT NULL DEFAULT '2',
			`paypal_profile_id` varchar(250) NOT NULL DEFAULT '',
			`laybuy_ref_no` int(11) NOT NULL DEFAULT '0',
			`payment_type` tinyint(1) NOT NULL DEFAULT '1',
			`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`laybuy_revise_request_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->load->model('extension/event');

		$this->model_extension_event->addEvent('laybuy', 'catalog/model/checkout/order/deleteOrder/after', 'extension/payment/laybuy/deleteOrder');
	}

	public function log($data, $step = 6) {
		if ($this->config->get('laybuy_logging')) {
			$backtrace = debug_backtrace();

			$log = new Log('laybuy.log');

			$log->write('(' . $backtrace[$step]['class'] . '::' . $backtrace[$step]['function'] . ') - ' . $data);
		}
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "laybuy_transaction`");

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "laybuy_revise_request`");

		$this->load->model('extension/event');

		$this->model_extension_event->deleteEvent('laybuy');
	}

	public function updateOrderStatus($order_id, $order_status_id, $comment) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `order_status_id` = '" . (int)$order_status_id . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_history` SET `order_id` = '" . (int)$order_id . "', `order_status_id` = '" . (int)$order_status_id . "', `notify` = '0', `comment` = '" . $this->db->escape($comment) . "', `date_added` = NOW()");
	}

	public function updateRevisedTransaction($id, $data = array()) {
		$this->db->query("UPDATE `" . DB_PREFIX . "laybuy_revise_request` SET `laybuy_transaction_id` = '" . (int)$data['transaction_id'] . "', `type` = '" . $this->db->escape($data['type']) . "', `order_id` = '" . (int)$data['order_id'] . "', `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `address` = '" . $this->db->escape($data['address']) . "', `suburb` = '" . $this->db->escape($data['suburb']) . "', `state` = '" . $this->db->escape($data['state']) . "', `country` = '" . $this->db->escape($data['country']) . "', `postcode` = '" . $this->db->escape($data['postcode']) . "', `email` = '" . $this->db->escape($data['email']) . "', `amount` = '" . (float)$data['amount'] . "', `currency` = '" . $this->db->escape($data['currency']) . "', `downpayment` = '" . $this->db->escape($data['downpayment']) . "', `months` = '" . (int)$data['months'] . "', `downpayment_amount` = '" . (float)$data['downpayment_amount'] . "', `payment_amounts` = '" . (float)$data['payment_amounts'] . "', `first_payment_due` = '" . $this->db->escape($data['first_payment_due']) . "', `last_payment_due` = '" . $this->db->escape($data['last_payment_due']) . "', `store_id` = '" . (int)$data['store_id'] . "', `status` = '" . (int)$data['status'] . "', `report` = '" . $this->db->escape($data['report']) . "', `transaction` = '" . (int)$data['transaction'] . "', `paypal_profile_id` = '" . $this->db->escape($data['paypal_profile_id']) . "', `laybuy_ref_no` = '" . (int)$data['laybuy_ref_no'] . "', `payment_type` = '" . (int)$data['payment_type'] . "', `date_added` = NOW() WHERE `laybuy_revise_request_id` = '" . (int)$id . "'");
	}

	public function updateTransaction($id, $status, $report, $transaction) {
		$this->db->query("UPDATE `" . DB_PREFIX . "laybuy_transaction` SET `status` = '" . (int)$status . "', `report` = '" . $this->db->escape($report) . "', `transaction` = '" . (int)$transaction . "' WHERE `laybuy_transaction_id` = '" . (int)$id . "'");
	}

	public function updateTransactionStatus($id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "laybuy_transaction` SET `status` = '" . (int)$status . "' WHERE `laybuy_transaction_id` = '" . (int)$id . "'");
	}
}