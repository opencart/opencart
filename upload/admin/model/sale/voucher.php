<?php
namespace Opencart\Admin\Model\Sale;
/**
 * Class Voucher
 *
 * @package Opencart\Admin\Model\Sale
 */
class Voucher extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addVoucher(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher` SET `code` = '" . $this->db->escape((string)$data['code']) . "', `from_name` = '" . $this->db->escape((string)$data['from_name']) . "', `from_email` = '" . $this->db->escape((string)$data['from_email']) . "', `to_name` = '" . $this->db->escape((string)$data['to_name']) . "', `to_email` = '" . $this->db->escape((string)$data['to_email']) . "', `voucher_theme_id` = '" . (int)$data['voucher_theme_id'] . "', `message` = '" . $this->db->escape((string)$data['message']) . "', `amount` = '" . (float)$data['amount'] . "', `status` = '" . (bool)$data['status'] . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * @param int   $voucher_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editVoucher(int $voucher_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "voucher` SET `code` = '" . $this->db->escape((string)$data['code']) . "', `from_name` = '" . $this->db->escape((string)$data['from_name']) . "', `from_email` = '" . $this->db->escape((string)$data['from_email']) . "', `to_name` = '" . $this->db->escape((string)$data['to_name']) . "', `to_email` = '" . $this->db->escape((string)$data['to_email']) . "', `voucher_theme_id` = '" . (int)$data['voucher_theme_id'] . "', `message` = '" . $this->db->escape((string)$data['message']) . "', `amount` = '" . (float)$data['amount'] . "', `status` = '" . (bool)$data['status'] . "' WHERE `voucher_id` = '" . (int)$voucher_id . "'");
	}

	/**
	 * @param int $voucher_id
	 *
	 * @return void
	 */
	public function deleteVoucher(int $voucher_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher` WHERE `voucher_id` = '" . (int)$voucher_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher_history` WHERE `voucher_id` = '" . (int)$voucher_id . "'");
	}

	/**
	 * @param int $voucher_id
	 *
	 * @return array
	 */
	public function getVoucher(int $voucher_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "voucher` WHERE `voucher_id` = '" . (int)$voucher_id . "'");

		return $query->row;
	}

	/**
	 * @param string $code
	 *
	 * @return array
	 */
	public function getVoucherByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "voucher` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getVouchers(array $data = []): array {
		$sql = "SELECT v.`voucher_id`, v.`order_id`, v.`code`, v.`from_name`, v.`from_email`, v.`to_name`, v.`to_email`, (SELECT vtd.`name` FROM `" . DB_PREFIX . "voucher_theme_description` vtd WHERE vtd.`voucher_theme_id` = v.`voucher_theme_id` AND vtd.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS theme, v.`amount`, v.`status`, v.`date_added` FROM `" . DB_PREFIX . "voucher` v";

		$sort_data = [
			'v.code',
			'v.from_name',
			'v.to_name',
			'theme',
			'v.amount',
			'v.status',
			'v.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY v.`date_added`";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * @return int
	 */
	public function getTotalVouchers(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "voucher`");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $voucher_theme_id
	 *
	 * @return int
	 */
	public function getTotalVouchersByVoucherThemeId(int $voucher_theme_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "voucher` WHERE `voucher_theme_id` = '" . (int)$voucher_theme_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $voucher_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getHistories(int $voucher_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT vh.`order_id`, CONCAT(o.`firstname`, ' ', o.`lastname`) AS customer, vh.`amount`, vh.`date_added` FROM `" . DB_PREFIX . "voucher_history` vh LEFT JOIN `" . DB_PREFIX . "order` o ON (vh.`order_id` = o.`order_id`) WHERE vh.`voucher_id` = '" . (int)$voucher_id . "' ORDER BY vh.`date_added` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * @param int $voucher_id
	 *
	 * @return int
	 */
	public function getTotalHistories(int $voucher_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "voucher_history` WHERE `voucher_id` = '" . (int)$voucher_id . "'");

		return (int)$query->row['total'];
	}
}
