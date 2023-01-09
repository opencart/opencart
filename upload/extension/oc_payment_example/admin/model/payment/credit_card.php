<?php
namespace Opencart\Admin\Model\Extension\OcPaymentExample\Payment;
class CreditCard extends \Opencart\System\Engine\Model {
	public function install(): void {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "credit_card_report` (
		`credit_card_report_id` int(11) NOT NULL AUTO_INCREMENT,
		`order_id` int(11) NOT NULL,
		`card` varchar(64) NOT NULL,
		`amount` decimal(15,4) NOT NULL,
		`response` text NOT NULL,
		`order_status_id` int(11) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`credit_card_report_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
	}

	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "credit_card_report`");
	}

	public function charge(int $customer_id, int $customer_payment_id, float $amount): int {

		return $this->config->get('config_subscription_active_status_id');
	}

	public function getReports(): array {


		return [];
	}

	public function getTotalReports(): int {


		return 0;
	}
}
