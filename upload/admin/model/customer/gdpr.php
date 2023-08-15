<?php
namespace Opencart\Admin\Model\Customer;
/**
 * Class GDPR
 *
 * @package Opencart\Admin\Model\Customer
 */
class Gdpr extends \Opencart\System\Engine\Model {
	/**
	 * @param int $gdpr_id
	 * @param int $status
	 *
	 * @return void
	 */
	public function editStatus(int $gdpr_id, int $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "gdpr` SET `status` = '" . (int)$status . "' WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");
	}

	/**
	 * @param int $gdpr_id
	 *
	 * @return void
	 */
	public function deleteGdpr(int $gdpr_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gdpr` WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getGdprs(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "gdpr`";

		$implode = [];

		if (!empty($data['filter_email'])) {
			$implode[] = "`email` LIKE '" . $this->db->escape((string)$data['filter_email']) . "'";
		}

		if (!empty($data['filter_action'])) {
			$implode[] = "`action` = '" . $this->db->escape((string)$data['filter_action']) . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY `date_added` DESC";

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
	 * @param int $gdpr_id
	 *
	 * @return array
	 */
	public function getGdpr(int $gdpr_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function getTotalGdprs(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "gdpr`";

		$implode = [];

		if (!empty($data['filter_email'])) {
			$implode[] = "`email` LIKE '" . $this->db->escape((string)$data['filter_email']) . "'";
		}

		if (!empty($data['filter_action'])) {
			$implode[] = "`action` = '" . $this->db->escape((string)$data['filter_action']) . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * @return array
	 */
	public function getExpires(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `status` = '2' AND DATE(`date_added`) <= DATE('" . $this->db->escape(date('Y-m-d', strtotime('+' . (int)$this->config->get('config_gdpr_limit') . ' days'))) . "') ORDER BY `date_added` DESC");

		return $query->rows;
	}
}
