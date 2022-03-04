<?php
namespace Opencart\Catalog\Model\Account;
class PaymentMethod extends \Opencart\System\Engine\Model {
	public function deletePaymentMethod(int $payment_method_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "payment_method` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `payment_method_id` = '" . (int)$payment_method_id . "'");
	}

	public function getPaymentMethod(int $customer_id, int $payment_method_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "payment_method` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `payment_method_id` = '" . (int)$payment_method_id . "'");

		return $query->row;
	}

	public function getPaymentMethods(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "payment_method` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function getTotalPaymentMethods(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "payment_method` WHERE `customer_id` = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
}