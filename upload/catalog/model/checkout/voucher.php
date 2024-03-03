<?php
namespace Opencart\Catalog\Model\Checkout;
/**
 * Class Voucher
 *
 * @package Opencart\Catalog\Model\Checkout
 */
class Voucher extends \Opencart\System\Engine\Model {
	/**
	 * Add Voucher
	 *
	 * @param int                  $order_id
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addVoucher(int $order_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher` SET `order_id` = '" . (int)$order_id . "', `code` = '" . $this->db->escape($data['code']) . "', `from_name` = '" . $this->db->escape($data['from_name']) . "', `from_email` = '" . $this->db->escape($data['from_email']) . "', `to_name` = '" . $this->db->escape($data['to_name']) . "', `to_email` = '" . $this->db->escape($data['to_email']) . "', `voucher_theme_id` = '" . (int)$data['voucher_theme_id'] . "', `message` = '" . $this->db->escape($data['message']) . "', `amount` = '" . (float)$data['amount'] . "', `status` = '1', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Delete Vouchers By Order ID
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteVouchersByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * Disable Voucher
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function disableVoucher(int $order_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "voucher` SET `status` = '0' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * Get Voucher
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getVoucher(string $code): array {
		$status = true;

		$this->load->model('checkout/voucher');

		$voucher_info = $this->model_checkout_voucher->getVoucherByCode($code);

		if ($voucher_info) {
			if ($voucher_info['order_id']) {
				$this->load->model('checkout/order');

				$order_info = $this->model_checkout_order->getOrder($voucher_info['order_id']);

				if (!$order_info || !in_array($order_info['order_status_id'], (array)$this->config->get('config_complete_status'))) {
					$status = false;
				}

				$order_info = $this->model_checkout_order->getVoucherByVoucherId($voucher_info['order_id'], $voucher_info['voucher_id']);

				if (!$order_info) {
					$status = false;
				}
			}

			$remaining = $this->model_checkout_voucher->getVoucherTotal($voucher_info['voucher_id']);

			if ($remaining) {
				$amount = $voucher_info['amount'] + $remaining;
			} else {
				$amount = $voucher_info['amount'];
			}

			if ($amount <= 0) {
				$status = false;
			}
		} else {
			$status = false;
		}

		if ($status) {
			return [
				'voucher_id'       => $voucher_info['voucher_id'],
				'code'             => $voucher_info['code'],
				'from_name'        => $voucher_info['from_name'],
				'from_email'       => $voucher_info['from_email'],
				'to_name'          => $voucher_info['to_name'],
				'to_email'         => $voucher_info['to_email'],
				'voucher_theme_id' => $voucher_info['voucher_theme_id'],
				'theme'            => $voucher_info['theme'],
				'message'          => $voucher_info['message'],
				'image'            => $voucher_info['image'],
				'amount'           => $amount,
				'status'           => $voucher_info['status'],
				'date_added'       => $voucher_info['date_added']
			];
		} else {
			return [];
		}
	}

	/**
	 * Get Voucher By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getVoucherByCode(string $code): array {
		$query = $this->db->query("SELECT *, `vtd`.`name` AS theme FROM `" . DB_PREFIX . "voucher` `v` LEFT JOIN `" . DB_PREFIX . "voucher_theme` `vt` ON (`v`.`voucher_theme_id` = `vt`.`voucher_theme_id`) LEFT JOIN `" . DB_PREFIX . "voucher_theme_description` `vtd` ON (`vt`.`voucher_theme_id` = `vtd`.`voucher_theme_id`) WHERE `v`.`code` = '" . $this->db->escape($code) . "' AND `vtd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `v`.`status` = '1'");

		return $query->row;
	}

	/**
	 * Get Voucher Total
	 *
	 * @param int $voucher_id
	 *
	 * @return float
	 */
	public function getVoucherTotal(int $voucher_id): float {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "voucher_history` `vh` WHERE `vh`.`voucher_id` = '" . (int)$voucher_id . "' GROUP BY `vh`.`voucher_id`");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * Add History
	 *
	 * @param int   $voucher_id
	 * @param int   $order_id
	 * @param float $value
	 *
	 * @return int
	 */
	public function addHistory(int $voucher_id, int $order_id, float $value): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_history` SET `voucher_id` = '" . (int)$voucher_id . "', `order_id` = '" . (int)$order_id . "', `amount` = '" . (float)$value . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Delete Voucher Histories By Order ID
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteHistoriesByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher_history` WHERE `order_id` = '" . (int)$order_id . "'");
	}
}
