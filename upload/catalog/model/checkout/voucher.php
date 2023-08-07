<?php
namespace Opencart\Catalog\Model\Checkout;
/**
 * Class Voucher
 *
 * @package Opencart\Catalog\Model\Checkout
 */
class Voucher extends \Opencart\System\Engine\Model {
	/**
	 * @param int   $order_id
	 * @param array $data
	 *
	 * @return int
	 */
	public function addVoucher(int $order_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher` SET `order_id` = '" . (int)$order_id . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `from_name` = '" . $this->db->escape((string)$data['from_name']) . "', `from_email` = '" . $this->db->escape((string)$data['from_email']) . "', `to_name` = '" . $this->db->escape((string)$data['to_name']) . "', `to_email` = '" . $this->db->escape((string)$data['to_email']) . "', `voucher_theme_id` = '" . (int)$data['voucher_theme_id'] . "', `message` = '" . $this->db->escape((string)$data['message']) . "', `amount` = '" . (float)$data['amount'] . "', `status` = '1', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function disableVoucher(int $order_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "voucher` SET `status` = '0' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * @param string $code
	 *
	 * @return array
	 */
	public function getVoucher(string $code): array {
		$status = true;

		$voucher_query = $this->db->query("SELECT *, vtd.`name` AS theme FROM `" . DB_PREFIX . "voucher` v LEFT JOIN `" . DB_PREFIX . "voucher_theme` vt ON (v.`voucher_theme_id` = vt.`voucher_theme_id`) LEFT JOIN `" . DB_PREFIX . "voucher_theme_description` vtd ON (vt.`voucher_theme_id` = vtd.`voucher_theme_id`) WHERE v.`code` = '" . $this->db->escape($code) . "' AND vtd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND v.`status` = '1'");

		if ($voucher_query->num_rows) {
			if ($voucher_query->row['order_id']) {
				$implode = [];

				foreach ($this->config->get('config_complete_status') as $order_status_id) {
					$implode[] = "'" . (int)$order_status_id . "'";
				}

				$order_query = $this->db->query("SELECT `order_id` FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$voucher_query->row['order_id'] . "' AND `order_status_id` IN(" . implode(",", $implode) . ")");

				if (!$order_query->num_rows) {
					$status = false;
				}

				$order_voucher_query = $this->db->query("SELECT `order_voucher_id` FROM `" . DB_PREFIX . "order_voucher` WHERE `order_id` = '" . (int)$voucher_query->row['order_id'] . "' AND `voucher_id` = '" . (int)$voucher_query->row['voucher_id'] . "'");

				if (!$order_voucher_query->num_rows) {
					$status = false;
				}
			}

			$voucher_history_query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "voucher_history` vh WHERE vh.`voucher_id` = '" . (int)$voucher_query->row['voucher_id'] . "' GROUP BY vh.`voucher_id`");

			if ($voucher_history_query->num_rows) {
				$amount = $voucher_query->row['amount'] + $voucher_history_query->row['total'];
			} else {
				$amount = $voucher_query->row['amount'];
			}

			if ($amount <= 0) {
				$status = false;
			}
		} else {
			$status = false;
		}

		if ($status) {
			return [
				'voucher_id'       => $voucher_query->row['voucher_id'],
				'code'             => $voucher_query->row['code'],
				'from_name'        => $voucher_query->row['from_name'],
				'from_email'       => $voucher_query->row['from_email'],
				'to_name'          => $voucher_query->row['to_name'],
				'to_email'         => $voucher_query->row['to_email'],
				'voucher_theme_id' => $voucher_query->row['voucher_theme_id'],
				'theme'            => $voucher_query->row['theme'],
				'message'          => $voucher_query->row['message'],
				'image'            => $voucher_query->row['image'],
				'amount'           => $amount,
				'status'           => $voucher_query->row['status'],
				'date_added'       => $voucher_query->row['date_added']
			];
		} else {
			return [];
		}
	}

	/**
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deleteVoucherByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher` WHERE `order_id` = '" . (int)$order_id . "'");
	}
}
