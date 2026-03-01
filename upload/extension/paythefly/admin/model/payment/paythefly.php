<?php
namespace Opencart\Admin\Model\Extension\Paythefly\Payment;

/**
 * Class PayTheFly
 *
 * Admin model for PayTheFly payment gateway.
 * Manages database schema for transaction tracking.
 *
 * @package Opencart\Admin\Model\Extension\Paythefly\Payment
 */
class Paythefly extends \Opencart\System\Engine\Model {
	/**
	 * Install - Create the paythefly_transaction table
	 *
	 * @return void
	 */
	public function install(): void {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paythefly_transaction` (
				`paythefly_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
				`order_id`                 INT(11) NOT NULL,
				`serial_no`                VARCHAR(64) NOT NULL,
				`project_id`               VARCHAR(64) NOT NULL,
				`chain_symbol`             VARCHAR(16) NOT NULL DEFAULT '',
				`chain_id`                 INT(11) NOT NULL DEFAULT 0,
				`token_address`            VARCHAR(128) NOT NULL DEFAULT '',
				`amount`                   VARCHAR(78) NOT NULL DEFAULT '0' COMMENT 'Raw wei/sun amount',
				`amount_display`           DECIMAL(20,8) NOT NULL DEFAULT 0 COMMENT 'Human-readable amount',
				`tx_hash`                  VARCHAR(128) NOT NULL DEFAULT '',
				`wallet`                   VARCHAR(128) NOT NULL DEFAULT '',
				`fee`                      VARCHAR(78) NOT NULL DEFAULT '0',
				`tx_type`                  TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=payment, 2=withdrawal',
				`confirmed`                TINYINT(1) NOT NULL DEFAULT 0,
				`signature`                TEXT NOT NULL,
				`deadline`                 INT(11) NOT NULL DEFAULT 0,
				`payment_url`              TEXT NOT NULL,
				`status`                   VARCHAR(32) NOT NULL DEFAULT 'pending',
				`webhook_raw`              TEXT NOT NULL COMMENT 'Raw webhook payload for debugging',
				`date_added`               DATETIME NOT NULL,
				`date_modified`            DATETIME NOT NULL,
				PRIMARY KEY (`paythefly_transaction_id`),
				KEY `idx_order_id` (`order_id`),
				KEY `idx_serial_no` (`serial_no`),
				KEY `idx_tx_hash` (`tx_hash`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
		");
	}

	/**
	 * Uninstall - Drop the paythefly_transaction table
	 *
	 * @return void
	 */
	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "paythefly_transaction`");
	}

	/**
	 * Add a transaction record
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int Transaction ID
	 */
	public function addTransaction(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "paythefly_transaction` SET
			`order_id`       = '" . (int)$data['order_id'] . "',
			`serial_no`      = '" . $this->db->escape($data['serial_no']) . "',
			`project_id`     = '" . $this->db->escape($data['project_id']) . "',
			`chain_symbol`   = '" . $this->db->escape($data['chain_symbol'] ?? '') . "',
			`chain_id`       = '" . (int)($data['chain_id'] ?? 0) . "',
			`token_address`  = '" . $this->db->escape($data['token_address'] ?? '') . "',
			`amount`         = '" . $this->db->escape($data['amount'] ?? '0') . "',
			`amount_display` = '" . (float)($data['amount_display'] ?? 0) . "',
			`signature`      = '" . $this->db->escape($data['signature'] ?? '') . "',
			`deadline`       = '" . (int)($data['deadline'] ?? 0) . "',
			`payment_url`    = '" . $this->db->escape($data['payment_url'] ?? '') . "',
			`status`         = 'pending',
			`webhook_raw`    = '',
			`date_added`     = NOW(),
			`date_modified`  = NOW()
		");

		return $this->db->getLastId();
	}

	/**
	 * Get transaction by serial number
	 *
	 * @param string $serialNo
	 *
	 * @return array<string, mixed>
	 */
	public function getTransactionBySerialNo(string $serialNo): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paythefly_transaction` WHERE `serial_no` = '" . $this->db->escape($serialNo) . "'");

		return $query->row;
	}

	/**
	 * Get transaction by order ID
	 *
	 * @param int $orderId
	 *
	 * @return array<string, mixed>
	 */
	public function getTransactionByOrderId(int $orderId): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paythefly_transaction` WHERE `order_id` = '" . (int)$orderId . "' ORDER BY `date_added` DESC LIMIT 1");

		return $query->row;
	}

	/**
	 * Update transaction from webhook
	 *
	 * @param string $serialNo
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function updateTransaction(string $serialNo, array $data): void {
		$sets = [];

		if (isset($data['tx_hash'])) {
			$sets[] = "`tx_hash` = '" . $this->db->escape($data['tx_hash']) . "'";
		}
		if (isset($data['wallet'])) {
			$sets[] = "`wallet` = '" . $this->db->escape($data['wallet']) . "'";
		}
		if (isset($data['fee'])) {
			$sets[] = "`fee` = '" . $this->db->escape($data['fee']) . "'";
		}
		if (isset($data['chain_symbol'])) {
			$sets[] = "`chain_symbol` = '" . $this->db->escape($data['chain_symbol']) . "'";
		}
		if (isset($data['tx_type'])) {
			$sets[] = "`tx_type` = '" . (int)$data['tx_type'] . "'";
		}
		if (isset($data['confirmed'])) {
			$sets[] = "`confirmed` = '" . (int)$data['confirmed'] . "'";
		}
		if (isset($data['status'])) {
			$sets[] = "`status` = '" . $this->db->escape($data['status']) . "'";
		}
		if (isset($data['webhook_raw'])) {
			$sets[] = "`webhook_raw` = '" . $this->db->escape($data['webhook_raw']) . "'";
		}

		$sets[] = "`date_modified` = NOW()";

		if ($sets) {
			$this->db->query("UPDATE `" . DB_PREFIX . "paythefly_transaction` SET " . implode(', ', $sets) . " WHERE `serial_no` = '" . $this->db->escape($serialNo) . "'");
		}
	}
}
