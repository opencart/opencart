<?php
namespace Opencart\Admin\Model\Extension\OcPaymentExample\Payment;
class CreditCard extends \Opencart\System\Engine\Model {
	public function install(): void {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "credit_card` (
			`credit_card_id` int(11) NOT NULL AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
			`card_name` varchar(64) NOT NULL,
			`card_number` varchar(64) NOT NULL,
			`card_expire_month` varchar(64) NOT NULL,
			`card_expire_year` varchar(64) NOT NULL,
			`card_cvv` varchar(3) NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`credit_card_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
		");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "credit_card_report` (
			`credit_card_report_id` int(11) NOT NULL AUTO_INCREMENT,
			`order_id` int(11) NOT NULL,
			`card_name` varchar(64) NOT NULL,
			`amount` decimal(15,4) NOT NULL,
			`response` text NOT NULL,
			`order_status_id` int(11) NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`credit_card_report_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
		");
	}

	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "credit_card`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "credit_card_report`");
	}


	public function addCreditCard(array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_payment` SET 
		`customer_id` = '" . (int)$this->customer->getId() . "', 
		`name` = '" . (int)$this->customer->getId() . "', 
		`image` = '" . $this->db->escape($data['image']) . "', 
		`type` = '" . $this->db->escape($data['type']) . "', 
		`extension` = '" . $this->db->escape($data['extension']) . "', 
		`code` = '" . $this->db->escape($data['code']) . "', 
		`token` = '" . $this->db->escape($data['token']) . "', 
		`date_expire` = '" . $this->db->escape($data['date_expire']) . "', 
		`default` = '" . (bool)$data['default'] . "', 
		`status` = '1', 
		`date_added` = NOW()");


	}

	public function deleteCreditCard(int $customer_payment_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_payment` WHERE `customer_id` = '" . (int)$this->customer->getId() . "' AND `customer_payment_id` = '" . (int)$customer_payment_id . "'");
	}

	public function getReports(int $download_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "credit_card_report` ORDER BY `date_added` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReports(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "credit_card_report`");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}
