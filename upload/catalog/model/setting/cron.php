<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Cron
 *
 * Can be called using $this->load->model('setting/cron');
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Cron extends \Opencart\System\Engine\Model {
	/**
	 * Edit Cron
	 *
	 * Edit cron record in the database.
	 *
	 * @param int $cron_id primary key of the cron record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $this->model_setting_cron->editCron($cron_id);
	 */
	public function editCron(int $cron_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `date_modified` = NOW() WHERE `cron_id` = '" . (int)$cron_id . "'");
	}

	/**
	 * Edit Status
	 *
	 * Edit cron status record in the database.
	 *
	 * @param int  $cron_id primary key of the cron record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $this->model_setting_cron->editStatus($cron_id, $status);
	 */
	public function editStatus(int $cron_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `status` = '" . (bool)$status . "' WHERE `cron_id` = '" . (int)$cron_id . "'");
	}

	/**
	 * Get Cron
	 *
	 * Get the record of the cron record in the database.
	 *
	 * @param int $cron_id primary key of the cron record
	 *
	 * @return array<string, mixed> cron record that has cron ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $cron_info = $this->model_setting_cron->getCron($cron_id);
	 */
	public function getCron(int $cron_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `cron_id` = '" . (int)$cron_id . "'");

		return $query->row;
	}

	/**
	 * Get Cron By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $cron_info = $this->model_setting_cron->getCronByCode($code);
	 */
	public function getCronByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * Get Cron(s)
	 *
	 * Get the record of the cron records in the database.
	 *
	 * @return array<int, array<string, mixed>> cron records
	 *
	 * @example
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $results = $this->model_setting_cron->getCrons();
	 */
	public function getCrons(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cron` ORDER BY `date_modified` DESC");

		return $query->rows;
	}

	/**
	 * Get Total Cron(s)
	 *
	 * Get the total number of total cron records in the database.
	 *
	 * @return int total number of cron records
	 *
	 * @example
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $cron_total = $this->model_setting_cron->getTotalCrons();
	 */
	public function getTotalCrons(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "cron`");

		return (int)$query->row['total'];
	}
}
