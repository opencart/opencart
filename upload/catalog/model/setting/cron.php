<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Cron
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Cron extends \Opencart\System\Engine\Model {
	/**
	 * @param int $cron_id
	 *
	 * @return void
	 */
	public function editCron(int $cron_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `date_modified` = NOW() WHERE `cron_id` = '" . (int)$cron_id . "'");
	}

	/**
	 * @param int  $cron_id
	 * @param bool $status
	 *
	 * @return void
	 */
	public function editStatus(int $cron_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `status` = '" . (bool)$status . "' WHERE `cron_id` = '" . (int)$cron_id . "'");
	}

	/**
	 * @param int $cron_id
	 *
	 * @return array
	 */
	public function getCron(int $cron_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `cron_id` = '" . (int)$cron_id . "'");

		return $query->row;
	}

	/**
	 * @param string $code
	 *
	 * @return array
	 */
	public function getCronByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * @return array
	 */
	public function getCrons(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cron` ORDER BY `date_modified` DESC");

		return $query->rows;
	}

	/**
	 * @return int
	 */
	public function getTotalCrons(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "cron`");

		return (int)$query->row['total'];
	}
}