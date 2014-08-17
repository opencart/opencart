<?php
class ModelSaleRecurring extends Model {
	public function getTotalProfiles($data) {
		$sql = "
			SELECT COUNT(*) AS `recurring_count`
			FROM `" . DB_PREFIX . "order_recurring` `or`
			JOIN `" . DB_PREFIX . "order` o USING(order_id)
			WHERE 1 = 1";

		if (!empty($data['filter_order_recurring_id'])) {
			$sql .= " AND or.order_recurring_id = " . (int)$data['filter_order_recurring_id'];
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND or.order_id = " . (int)$data['filter_order_id'];
		}

		if (!empty($data['filter_payment_reference'])) {
			$sql .= " AND or.recurring_reference LIKE '" . $this->db->escape($data['filter_payment_reference']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(or.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND or.status = " . (int)$data['filter_status'];
		}

		$result = $this->db->query($sql);

		return $result->row['recurring_count'];
	}

	public function getProfiles($data) {
		$sql = "
			SELECT `or`.order_recurring_id, `or`.order_id, `or`.`status`, `or`.`date_added`, `or`.recurring_reference,
			  CONCAT(`o`.`firstname`, ' ', `o`.`lastname`) AS `customer`
			FROM `" . DB_PREFIX . "order_recurring` `or`
			JOIN `" . DB_PREFIX . "order` `o` USING(`order_id`)
			WHERE 1 = 1 ";

		if (!empty($data['filter_order_recurring_id'])) {
			$sql .= " AND or.order_recurring_id = " . (int)$data['filter_order_recurring_id'];
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND or.order_id = " . (int)$data['filter_order_id'];
		}

		if (!empty($data['filter_payment_reference'])) {
			$sql .= " AND or.recurring_reference LIKE '" . $this->db->escape($data['filter_payment_reference']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(or.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND or.status = " . (int)$data['filter_status'];
		}

		$sort_data = array(
			'or.order_recurring_id',
			'or.order_id',
			'or.recurring_reference',
			'customer',
			'or.date_added',
			'or.status',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY or.order_recurring_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

		$recurrings = array();

		$results = $this->db->query($sql)->rows;

		foreach ($results as $result) {
			$recurrings[] = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id' => $result['order_id'],
				'status' => $this->getStatus($result['status']),
				'date_added' => $result['date_added'],
				'recurring_reference' => $result['recurring_reference'],
				'customer' => $result['customer'],
			);
		}

		return $recurrings;
	}

	public function getProfile($order_recurring_id) {
		$recurring = array();

		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_recurring WHERE order_recurring_id = " . (int)$order_recurring_id)->row;

		if ($result) {

			$recurring = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id' => $result['order_id'],
				'status' => $this->getStatus($result['status']),
				'status_id' => $result['status'],
				'recurring_id' => $result['recurring_id'],
				'recurring_name' => $result['recurring_name'],
				'recurring_description' => $result['recurring_description'],
				'recurring_reference' => $result['recurring_reference'],
				'product_name' => $result['product_name'],
				'product_quantity' => $result['product_quantity'],
			);
		}

		return $recurring;
	}

	public function getProfileTransactions($order_recurring_id) {
		$results =  $this->db->query("SELECT amount, type, date_added FROM " . DB_PREFIX . "order_recurring_transaction WHERE order_recurring_id = " . (int)$order_recurring_id . " ORDER BY date_added DESC")->rows;

		$transactions = array();

		foreach ($results as $result) {

			switch ($result['type']) {
				case 0:
					$type = $this->language->get('text_transaction_date_added');
					break;

				case 1:
					$type = $this->language->get('text_transaction_payment');
					break;

				case 2:
					$type = $this->language->get('text_transaction_outstanding_payment');
					break;

				case 3:
					$type = $this->language->get('text_transaction_skipped');
					break;

				case 4:
					$type = $this->language->get('text_transaction_failed');
					break;

				case 5:
					$type = $this->language->get('text_transaction_cancelled');
					break;

				case 6:
					$type = $this->language->get('text_transaction_suspended');
					break;

				case 7:
					$type = $this->language->get('text_transaction_suspended_failed');
					break;

				case 8:
					$type = $this->language->get('text_transaction_outstanding_failed');
					break;

				case 9:
					$type = $this->language->get('text_transaction_expired');
					break;

				default:
					$type = '';
					break;
			}

			$transactions[] = array(
				'date_added' => $result['date_added'],
				'amount' => $result['amount'],
				'type' => $type,
			);
		}

		return $transactions;
	}

	private function getStatus($status) {
		switch ($status) {
			case 1:
				$result = $this->language->get('text_status_inactive');
				break;

			case 2:
				$result = $this->language->get('text_status_active');
				break;

			case 3:
				$result = $this->language->get('text_status_suspended');
				break;

			case 4:
				$result = $this->language->get('text_status_cancelled');
				break;

			case 5:
				$result = $this->language->get('text_status_expired');
				break;

			case 6:
				$result = $this->language->get('text_status_pending');
				break;

			default:
				$result = '';
				break;
		}

		return $result;
	}
}