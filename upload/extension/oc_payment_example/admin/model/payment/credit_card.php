<?php
namespace Opencart\Admin\Model\Extension\OcPaymentExample\Payment;
/**
 * Credit Card
 *
 * Can be called from $this->load->model('extension/oc_payment_example/payment/credit_card');
 *
 * @package Opencart\Admin\Model\Extension\OcPaymentExample\Payment
 */
class CreditCard extends \Opencart\System\Engine\Model {
	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "credit_card` (
			`credit_card_id` int(11) NOT NULL AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
			`type` varchar(64) NOT NULL,
			`card_name` varchar(64) NOT NULL,
			`card_number` varchar(64) NOT NULL,
			`card_expire_month` varchar(64) NOT NULL,
			`card_expire_year` varchar(64) NOT NULL,
			`card_cvv` varchar(3) NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`credit_card_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "credit_card_report` (
			`credit_card_report_id` int(11) NOT NULL AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
			`credit_card_id` int(11) NOT NULL,
			`order_id` int(11) NOT NULL,
			`card_number` varchar(64) NOT NULL,
			`type` varchar(64) NOT NULL,
			`amount` decimal(15,4) NOT NULL,
			`response` tinyint(1) NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`credit_card_report_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
		");
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "credit_card`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "credit_card_report`");
	}

	/**
	 * Add Credit Card
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return void
	 */
	public function addCreditCard(array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_payment` SET `customer_id` = '" . (int)$this->customer->getId() . "', `name` = '" . (int)$this->customer->getId() . "', `type` = '" . $this->db->escape($data['type']) . "', `extension` = '" . $this->db->escape($data['extension']) . "', `code` = '" . $this->db->escape($data['code']) . "', `token` = '" . $this->db->escape($data['token']) . "', `date_expire` = '" . $this->db->escape($data['date_expire']) . "', `default` = '" . (bool)$data['default'] . "', `status` = '1', `date_added` = NOW()");
	}

	/**
	 * Delete Credit Card
	 *
	 * @param int $customer_payment_id primary key of the customer payment record
	 *
	 * @return void
	 */
	public function deleteCreditCard(int $customer_payment_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_payment` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `customer_payment_id` = '" . (int)$customer_payment_id . "'");
	}

	/**
	 * Get Reports
	 *
	 * @param int $start
	 * @param int $limit
	 * @param int $download_id primary key of the download record
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getReports(int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "credit_card_report` ORDER BY `date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Reports
	 *
	 * @return int
	 */
	public function getTotalReports(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "credit_card_report`");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}
}
