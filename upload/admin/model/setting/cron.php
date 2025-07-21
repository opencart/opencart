<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Cron
 *
 * Can be loaded using $this->load->model('setting/cron');
 *
 * @package Opencart\Admin\Model\Setting
 */
class Cron extends \Opencart\System\Engine\Model {
	/**
	 * Add Cron
	 *
	 * Create a new cron record in the database.
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $cron_data = [
	 *
	 *
	 * ]
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $cron_id = $this->model_setting_cron->addCron($cron_data);
	 */
	public function addCron(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "cron` SET `code` = '" . $this->db->escape($data['code']) . "', `description` = '" . $this->db->escape($data['description']) . "', `cycle` = '" . $this->db->escape($data['cycle']) . "', `action` = '" . $this->db->escape($data['action']) . "', `status` = '" . (bool)$data['status'] . "', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Delete Cron
	 *
	 * Delete cron record in the database.
	 *
	 * @param int $cron_id primary key of the cron record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $this->model_setting_cron->deleteCron($cron_id);
	 */
	public function deleteCron(int $cron_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cron` WHERE `cron_id` = '" . (int)$cron_id . "'");
	}

	/**
	 * Delete Cron By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $this->model_setting_cron->deleteCronByCode($code);
	 */
	public function deleteCronByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cron` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

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
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> cron records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'code',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('setting/cron');
	 *
	 * $results = $this->model_setting_cron->getCrons($filter_data);
	 */
	public function getCrons(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "cron` ORDER BY `code` ASC";

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
