<?php
namespace Opencart\Application\Model\Customer;
class Gdpr extends \Opencart\System\Engine\Model {
	public function deleteGdpr($gdpr_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gdpr` WHERE gdpr_id = '" . (int)$gdpr_id . "'");
	}

	public function getGdprs($data = []) {
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

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(`date_added`) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
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

	public function getGdpr($gdpr_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");

		return $query->row;
	}

	public function getTotalGdprs($data = []) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "gdpr`";

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

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(`date_added`) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getExpires() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `status` = '2' AND DATE(`date_added`) <= DATE('" . $this->db->escape(date('Y-m-d', strtotime('+' . (int)$this->config->get('config_gdpr_limit') . ' days'))) . "') ORDER BY `date_added` DESC");

		return $query->rows;
	}

	public function editStatus($gdpr_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "gdpr` SET status = '" . (int)$status . "' WHERE gdpr_id = '" . (int)$gdpr_id . "'");
	}
}