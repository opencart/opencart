<?php

class ModelExtensionPaymentSquareup extends Model {
	const RECURRING_ACTIVE = 1;
//	const RECURRING_INACTIVE = 2;
	const RECURRING_CANCELLED = 3;
//	const RECURRING_SUSPENDED = 4;
//	const RECURRING_EXPIRED = 5;
//	const RECURRING_PENDING = 6;

	public function getPayment($squareup_payment_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "squareup_payment` WHERE squareup_payment_id='" . (int)$squareup_payment_id . "'");
		return $query->row;
	}
    
	public function getPayments($data) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "squareup_payment`";

		if (isset($data['order_id'])) {
			$sql .= " WHERE opencart_order_id='" . (int)$data['order_id'] . "'";
		}

		$sql .= " ORDER BY created_at DESC";

		if (isset($data['start']) && isset($data['limit'])) {
			$sql .= " LIMIT " . $data['start'] . ', ' . $data['limit'];
		}

		$query = $this->db->query($sql);
		return $query->rows;
   }

	public function getTotalPayments($data) {
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "squareup_payment`";
		
		if (isset($data['order_id'])) {
			$sql .= " WHERE opencart_order_id='" . (int)$data['order_id'] . "'";
		}

		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function updatePaymentRefund($squareup_payment_id, $updated_at, $amount) {
		$this->db->query("UPDATE `" . DB_PREFIX ."squareup_payment` SET `refunded_amount`='" . (int)$amount . "', `refunded_currency`=`currency`, updated_at='$updated_at' WHERE `squareup_payment_id`='" . (int)$squareup_payment_id . "';" );
	}

	public function updatePaymentStatus($squareup_payment_id, $status, $updated_at) {
		$this->db->query("UPDATE `" . DB_PREFIX . "squareup_payment` SET status='" . $this->db->escape($status) . "', updated_at='$updated_at' WHERE squareup_payment_id='" . (int)$squareup_payment_id . "'");
	}

	public function updatePayment($squareup_payment_id, $payment) {
		$refunded_amount = 0;
		$refunded_currency = $payment['payment']['amount_money']['currency'];
		if (isset($payment['payment']['refunded_money']['amount'])) {
			$refunded_amount = $payment['payment']['refunded_money']['amount'];
		}
		if (isset($payment['payment']['refunded_money']['currency'])) {
			$refunded_currency = $payment['payment']['refunded_money']['currency'];
		}
		$card_fingerprint = '';
		if (isset($payment['payment']['card_details']['card']['fingerprint'])) {
			$card_fingerprint = $payment['payment']['card_details']['card']['fingerprint'];
		}
		$billing_address = array();
		if (isset($payment['payment']['billing_address'])) {
			$billing_address = $payment['payment']['billing_address'];
		}
		$sql  = "UPDATE `" . DB_PREFIX . "squareup_payment` SET ";
		$sql .= "`payment_id`='".$this->db->escape($payment['payment']['id'])."', ";
		$sql .= "`location_id`='".$this->db->escape($payment['payment']['location_id'])."', ";
		$sql .= "`order_id`='".$this->db->escape($payment['payment']['order_id'])."', ";
		$sql .= "`customer_id`='".(isset($payment['payment']['customer_id']) ? $this->db->escape($payment['payment']['customer_id']) : '')."', ";
		$sql .= "`created_at`='".$this->db->escape($payment['payment']['created_at'])."', ";
		$sql .= "`updated_at`='".$this->db->escape($payment['payment']['updated_at'])."', ";
		$sql .= "`amount`=".(int)$payment['payment']['amount_money']['amount'].", ";
		$sql .= "`currency`='".$this->db->escape($payment['payment']['amount_money']['currency'])."', ";
		$sql .= "`status`='".$this->db->escape($payment['payment']['status'])."', ";
		$sql .= "`source_type`='".$this->db->escape($payment['payment']['source_type'])."', ";
		$sql .= "`square_product`='".$this->db->escape($payment['payment']['application_details']['square_product'])."', ";
		$sql .= "`application_id`='".$this->db->escape($payment['payment']['application_details']['application_id'])."', ";
		$sql .= "`refunded_amount`='".(int)$refunded_amount."', ";
		$sql .= "`refunded_currency`='".$this->db->escape($refunded_currency)."', ";
		$sql .= "`card_fingerprint`='".$this->db->escape($card_fingerprint)."', ";
		$sql .= "`first_name`='".$this->db->escape(isset($billing_address['first_name']) ? $billing_address['first_name'] : '')."', ";
		$sql .= "`last_name`='".$this->db->escape(isset($billing_address['last_name']) ? $billing_address['last_name'] : '')."', ";
		$sql .= "`address_line_1`='".$this->db->escape(isset($billing_address['address_line_1']) ? $billing_address['address_line_1'] : '')."', ";
		$sql .= "`address_line_2`='".$this->db->escape(isset($billing_address['address_line_2']) ? $billing_address['address_line_2'] : '')."', ";
		$sql .= "`address_line_3`='".$this->db->escape(isset($billing_address['address_line_3']) ? $billing_address['address_line_3'] : '')."', ";
		$sql .= "`locality`='".$this->db->escape(isset($billing_address['locality']) ? $billing_address['locality'] : '')."', ";
		$sql .= "`sublocality`='".$this->db->escape(isset($billing_address['sublocality']) ? $billing_address['sublocality'] : '')."', ";
		$sql .= "`sublocality_2`='".$this->db->escape(isset($billing_address['sublocality_2']) ? $billing_address['sublocality_2'] : '')."', ";
		$sql .= "`sublocality_3`='".$this->db->escape(isset($billing_address['sublocality_3']) ? $billing_address['sublocality_3'] : '')."', ";
		$sql .= "`administrative_district_level_1`='".$this->db->escape(isset($billing_address['administrative_district_level_1']) ? $billing_address['administrative_district_level_1'] : '')."', ";
		$sql .= "`administrative_district_level_2`='".$this->db->escape(isset($billing_address['administrative_district_level_2']) ? $billing_address['administrative_district_level_2'] : '')."', ";
		$sql .= "`administrative_district_level_3`='".$this->db->escape(isset($billing_address['administrative_district_level_3']) ? $billing_address['administrative_district_level_3'] : '')."', ";
		$sql .= "`postal_code`='".$this->db->escape(isset($billing_address['postal_code']) ? $billing_address['postal_code'] : '')."', ";
		$sql .= "`country`='".$this->db->escape(isset($billing_address['country']) ? $billing_address['country'] : '')."' ";
		$sql .= "WHERE squareup_payment_id='".(int)$squareup_payment_id."';";
		$this->db->query($sql);
	}

	public function getOrderStatusId($order_id, $payment_status = null) {
		if ($payment_status) {
			return $this->config->get('payment_squareup_status_' . strtolower($payment_status));
		} else {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			return $order_info['order_status_id'];
		}
	}

	public function editOrderRecurringStatus($order_recurring_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = '" . (int)$status . "' WHERE `order_recurring_id` = '" . (int)$order_recurring_id . "'");
	}

	public function createTables() {
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "squareup_payment` ( ";
		$sql .= "`squareup_payment_id` int NOT NULL AUTO_INCREMENT, ";
		$sql .= "`opencart_order_id` int NOT NULL, ";
		$sql .= "`payment_id` varchar(192) NOT NULL, ";
		$sql .= "`merchant_id` varchar(255) NOT NULL, ";
		$sql .= "`location_id` varchar(50) NOT NULL, ";
		$sql .= "`order_id` varchar(192) NOT NULL, ";
		$sql .= "`customer_id` varchar(191) NOT NULL, ";
		$sql .= "`created_at` varchar(32) NOT NULL, ";
		$sql .= "`updated_at` varchar(32) NOT NULL, ";
		$sql .= "`amount` bigint NOT NULL DEFAULT '0', ";
		$sql .= "`currency` char(3) NOT NULL, ";
		$sql .= "`status` varchar(50) NOT NULL, ";
		$sql .= "`source_type` varchar(50) NOT NULL, ";
		$sql .= "`square_product` varchar(16) NOT NULL, ";
		$sql .= "`application_id` varchar(255) NOT NULL, ";
		$sql .= "`refunded_amount` bigint NOT NULL DEFAULT '0', ";
		$sql .= "`refunded_currency` char(3) NOT NULL DEFAULT '', ";
		$sql .= "`card_fingerprint` varchar(255) NOT NULL, ";
		$sql .= "`first_name` varchar(300) NOT NULL, ";
		$sql .= "`last_name` varchar(300) NOT NULL, ";
		$sql .= "`address_line_1` varchar(500) NOT NULL, ";
		$sql .= "`address_line_2` varchar(500) NOT NULL, ";
		$sql .= "`address_line_3` varchar(500) NOT NULL, ";
		$sql .= "`locality` varchar(300) NOT NULL, ";
		$sql .= "`sublocality` varchar(300) NOT NULL, ";
		$sql .= "`sublocality_2` varchar(300) NOT NULL, ";
		$sql .= "`sublocality_3` varchar(300) NOT NULL, ";
		$sql .= "`administrative_district_level_1` varchar(200) NOT NULL, ";
		$sql .= "`administrative_district_level_2` varchar(200) NOT NULL, ";
		$sql .= "`administrative_district_level_3` varchar(200) NOT NULL, ";
		$sql .= "`postal_code` varchar(12) NOT NULL, ";
		$sql .= "`country` char(2) NOT NULL DEFAULT 'ZZ', ";
		$sql .= "`ip` varchar(40) NOT NULL, ";
		$sql .= "`user_agent` varchar(255) NOT NULL, ";
		$sql .= "PRIMARY KEY (`squareup_payment_id`), ";
		$sql .= "KEY `opencart_order_id` (`opencart_order_id`) ";
		$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
		$this->db->query($sql);
	}
    
	public function dropTables() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "squareup_payment`");
	}

    public function inferOrderStatusId($search) {
		$order_status_id = 0;
		$query = $this->db->query("SELECT language_id FROM `".DB_PREFIX."language` WHERE `code` LIKE 'en-%'");
		if (!empty($query->row['language_id'])) {
			$language_id = $query->row['language_id'];
			$order_status_query = $this->db->query("SELECT order_status_id FROM `" . DB_PREFIX . "order_status` WHERE LOWER(name) LIKE '" . $this->db->escape(strtolower($search)) . "%' AND language_id='".(int)$language_id."' ORDER BY LENGTH(`name`) ASC LIMIT 1");
			if (!empty($order_status_query->row['order_status_id'])) {
				$order_status_id = (int)$order_status_query->row['order_status_id'];
			}
		}
		return $order_status_id;
    }
}
