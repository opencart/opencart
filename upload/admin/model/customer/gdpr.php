<?php
namespace Opencart\Admin\Model\Customer;
/**
 * Class GDPR
 *
 * Can be loaded using $this->load->model('customer/gdpr');
 *
 * @package Opencart\Admin\Model\Customer
 */
class Gdpr extends \Opencart\System\Engine\Model {
	/**
	 * Edit Status
	 *
	 * Edit gdpr status record in the database.
	 *
	 * @param int $gdpr_id primary key of the gdpr record
	 * @param int $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $this->model_customer_gdpr->editStatus($gdpr_id, $status);
	 */
	public function editStatus(int $gdpr_id, int $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "gdpr` SET `status` = '" . (int)$status . "' WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");
	}

	/**
	 * Delete Gdpr
	 *
	 * Delete gdpr record in the database.
	 *
	 * @param int $gdpr_id primary key of the gdpr record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $this->model_customer_gdpr->deleteGdpr($gdpr_id);
	 */
	public function deleteGdpr(int $gdpr_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gdpr` WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");
	}

	/**
	 * Delete Gdpr(s) By Store ID
	 *
	 * Delete gdprs by store records in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $this->model_customer_gdpr->deleteGdprsByStoreId($store_id);
	 */
	public function deleteGdprsByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gdpr` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Gdpr(s)
	 *
	 * Get the record of the gdpr records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> gdpr records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_email'     => 'demo@opencart.com',
	 *     'filter_action'    => 'export',
	 *     'filter_status'    => 1,
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $results = $this->model_customer_gdpr->getGdprs($filter_data);
	 */
	public function getGdprs(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "gdpr`";

		$implode = [];

		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email'])) . "'";
		}

		if (!empty($data['filter_action'])) {
			$implode[] = "`action` = '" . $this->db->escape($data['filter_action']) . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`status` = '" . (bool)$data['filter_status'] . "'";
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
	 * Get Gdpr
	 *
	 * Get the record of the gdpr record in the database.
	 *
	 * @param int $gdpr_id primary key of the gdpr record
	 *
	 * @return array<string, mixed> gdpr record that has gdpr ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $gdpr_info = $this->model_customer_gdpr->getGdpr($gdpr_id);
	 */
	public function getGdpr(int $gdpr_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");

		return $query->row;
	}

	/**
	 * Get Total Gdpr(s)
	 *
	 * Get the total number of gdpr records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of gdpr records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_email'     => 'demo@opencart.com',
	 *     'filter_action'    => 'export',
	 *     'filter_status'    => 1,
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $gdpr_total = $this->model_customer_gdpr->getTotalGdprs($filter_data);
	 */
	public function getTotalGdprs(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "gdpr`";

		$implode = [];

		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email'])) . "'";
		}

		if (!empty($data['filter_action'])) {
			$implode[] = "`action` = '" . $this->db->escape($data['filter_action']) . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`status` = '" . (bool)$data['filter_status'] . "'";
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
	 * Get Expires
	 *
	 * @return array<int, array<string, mixed>> expire records
	 *
	 * @example
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $results = $this->model_customer_gdpr->getExpires();
	 */
	public function getExpires(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `status` = '2' AND DATE(`date_added`) <= DATE('" . $this->db->escape(date('Y-m-d', strtotime('+' . (int)$this->config->get('config_gdpr_limit') . ' days'))) . "') ORDER BY `date_added` DESC");

		return $query->rows;
	}
}
