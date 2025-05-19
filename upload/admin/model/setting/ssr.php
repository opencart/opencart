<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class SSR
 *
 * Can be loaded using $this->load->model('setting/ssr');
 *
 * @package Opencart\Admin\Model\Setting
 */
class Ssr extends \Opencart\System\Engine\Model {
	/**
	 * Add SSR
	 *
	 * Create a new ssr record in the database.
	 *
	 * @param array $data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('setting/ssr');
	 *
	 * $ssr_id = $this->model_setting_ssr->addSsr($code, $description, $cycle, $action, $status);
	 */
	public function addSsr(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "ssr` SET `code` = '" . $this->db->escape((string)$data['code']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "', `action` = '" . $this->db->escape((string)$data['action']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Delete SSR
	 *
	 * Delete ssr record in the database.
	 *
	 * @param int $ssr_id primary key of the ssr record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/ssr');
	 *
	 * $this->model_setting_ssr->deleteSsr($ssr_id);
	 */
	public function deleteSsr(int $ssr_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "ssr` WHERE `ssr_id` = '" . (int)$ssr_id . "'");
	}

	/**
	 * Delete SSR By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/ssr');
	 *
	 * $this->model_setting_ssr->deleteSsrByCode($code);
	 */
	public function deleteSsrByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "ssr` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Edit Status
	 *
	 * Edit ssr status record in the database.
	 *
	 * @param int  $ssr_id primary key of the ssr record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/ssr');
	 *
	 * $this->model_setting_ssr->editStatus($ssr_id, $status);
	 */
	public function editStatus(int $ssr_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "ssr` SET `status` = '" . (bool)$status . "' WHERE `ssr_id` = '" . (int)$ssr_id . "'");
	}

	/**
	 * Get Ssr
	 *
	 * Get the record of the ssr record in the database.
	 *
	 * @param int $ssr_id primary key of the ssr record
	 *
	 * @return array<string, mixed> ssr record that has ssr ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/ssr');
	 *
	 * $ssr_info = $this->model_setting_ssr->getSsr($ssr_id);
	 */
	public function getSsr(int $ssr_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "ssr` WHERE `ssr_id` = '" . (int)$ssr_id . "'");

		return $query->row;
	}

	/**
	 * Get Ssr By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('setting/ssr');
	 *
	 * $ssr_info = $this->model_setting_ssr->getSsrByCode($code);
	 */
	public function getSsrByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "ssr` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * Get Ssr(s)
	 *
	 * Get the record of the ssr records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> ssr records
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
	 * $this->load->model('setting/ssr');
	 *
	 * $results = $this->model_setting_ssr->getSsrs($filter_data);
	 */
	public function getSsrs(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "ssr`";

		$sort_data = [
			'code',
			'action',
			'sort_order',
			'date_modified'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `code`";
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
	 * Get Total Ssr(s)
	 *
	 * Get the total number of total ssr records in the database.
	 *
	 * @return int total number of ssr records
	 *
	 * @example
	 *
	 * $this->load->model('setting/ssr');
	 *
	 * $ssr_total = $this->model_setting_ssr->getTotalSsrs();
	 */
	public function getTotalSsrs(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "ssr`");

		return (int)$query->row['total'];
	}
}
