<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Startup
 *
 * Can be loaded using $this->load->model('setting/startup');
 *
 * @package Opencart\Admin\Model\Setting
 */
class Startup extends \Opencart\System\Engine\Model {
	/**
	 * Add Startup
	 *
	 * Create a new startup record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $startup_data = [
	 *     'code'        => 'Startup Code',
	 *     'description' => 'Startup Description',
	 *     'action'      => 'Startup Action',
	 *     'status'      => 0,
	 *     'sort_order'  => 0
	 * ];
	 *
	 * $this->load->model('setting/startup');
	 *
	 * $startup_id = $this->model_setting_startup->addStartup($startup_data);
	 */
	public function addStartup(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "startup` SET `code` = '" . $this->db->escape($data['code']) . "', `description` = '" . $this->db->escape($data['description']) . "', `action` = '" . $this->db->escape($data['action']) . "', `status` = '" . (bool)$data['status'] . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		return $this->db->getLastId();
	}

	/**
	 * Delete Startup
	 *
	 * Delete startup record in the database.
	 *
	 * @param int $startup_id primary key of the startup record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/startup');
	 *
	 * $this->model_setting_startup->deleteStartup($startup_id);
	 */
	public function deleteStartup(int $startup_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "startup` WHERE `startup_id` = '" . (int)$startup_id . "'");
	}

	/**
	 * Delete Startup By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/startup');
	 *
	 * $this->model_setting_startup->deleteStartupByCode($code);
	 */
	public function deleteStartupByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "startup` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Edit Status
	 *
	 * Edit startup status record in the database.
	 *
	 * @param int  $startup_id primary key of the startup record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/startup');
	 *
	 * $this->model_setting_startup->editStatus($startup_id, $status);
	 */
	public function editStatus(int $startup_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "startup` SET `status` = '" . (bool)$status . "' WHERE `startup_id` = '" . (int)$startup_id . "'");
	}

	/**
	 * Get Startup
	 *
	 * Get the record of the startup record in the database.
	 *
	 * @param int $startup_id primary key of the startup record
	 *
	 * @return array<string, mixed> startup record that has startup ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/startup');
	 *
	 * $startup_info = $this->model_setting_startup->getStartup($startup_id);
	 */
	public function getStartup(int $startup_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "startup` WHERE `startup_id` = '" . (int)$startup_id . "'");

		return $query->row;
	}

	/**
	 * Get Startup By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('setting/startup');
	 *
	 * $startup_info = $this->model_setting_startup->getStartupByCode($code);
	 */
	public function getStartupByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "startup` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * Get Startups
	 *
	 * Get the record of the startup records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> startup records
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
	 * $this->load->model('setting/startup');
	 *
	 * $results = $this->model_setting_startup->getStartups($filter_data);
	 */
	public function getStartups(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "startup` ORDER BY `code` ASC, `sort_order` ASC";

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
	 * Get Total Startups
	 *
	 * Get the total number of total startup records in the database.
	 *
	 * @return int total number of startup records
	 *
	 * @example
	 *
	 * $this->load->model('setting/startup');
	 *
	 * $startup_total = $this->model_setting_startup->getTotalStartups();
	 */
	public function getTotalStartups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "startup`");

		return (int)$query->row['total'];
	}
}
