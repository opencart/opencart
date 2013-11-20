<?php
class ModelSaleRecurring extends Model {
	public function getTotalProfiles($data) {
		$sql = "
			SELECT COUNT(*) AS `profile_count`
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
			$sql .= " AND or.profile_reference LIKE '" . $this->db->escape($data['filter_payment_reference']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_created'])) {
			$sql .= " AND DATE(or.created) = DATE('" . $this->db->escape($data['filter_created']) . "')";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND or.status = " . (int)$data['filter_status'];
		}

		$result = $this->db->query($sql);

		return $result->row['profile_count'];
	}

	public function getProfiles($data) {
		$sql = "
			SELECT `or`.order_recurring_id, `or`.order_id, `or`.`status`, `or`.`created`, `or`.profile_reference,
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
			$sql .= " AND or.profile_reference LIKE '" . $this->db->escape($data['filter_payment_reference']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_created'])) {
			$sql .= " AND DATE(or.created) = DATE('" . $this->db->escape($data['filter_created']) . "')";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND or.status = " . (int)$data['filter_status'];
		}

		$sort_data = array(
			'or.order_recurring_id',
			'or.order_id',
			'or.profile_reference',
			'customer',
			'or.created',
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

		$profiles = array();

		$results = $this->db->query($sql)->rows;

		foreach ($results as $result) {
			$profiles[] = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id' => $result['order_id'],
				'status' => $this->getStatus($result['status']),
				'created' => $result['created'],
				'profile_reference' => $result['profile_reference'],
				'customer' => $result['customer'],
			);
		}

		return $profiles;
	}

	public function getProfile($order_recurringId) {
		$profile = array();

		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_recurring WHERE order_recurring_id = " . (int)$order_recurringId)->row;

		if ($result) {

			$profile = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id' => $result['order_id'],
				'status' => $this->getStatus($result['status']),
				'status_id' => $result['status'],
				'profile_id' => $result['profile_id'],
				'profile_name' => $result['profile_name'],
				'profile_description' => $result['profile_description'],
				'profile_reference' => $result['profile_reference'],
				'product_name' => $result['product_name'],
				'product_quantity' => $result['product_quantity'],
			);
		}

		return $profile;
	}

	public function getProfileTransactions($order_recurring_id) {
		$results =  $this->db->query("SELECT amount, type, created FROM " . DB_PREFIX . "order_recurring_transaction WHERE order_recurring_id = " . (int)$order_recurring_id . " ORDER BY created DESC")->rows;

		$transactions = array();

		foreach ($results as $result) {

			switch ($result['type']) {
				case 0:
					$type = $this->language->get('text_transaction_created');
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
				'created' => $result['created'],
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
?>