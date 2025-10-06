<?php
namespace Opencart\Admin\Model\Tool;
/**
 * Class Upload
 *
 * Can be loaded using $this->load->model('tool/upload');
 *
 * @package Opencart\Admin\Model\Tool
 */
class Upload extends \Opencart\System\Engine\Model {
	/**
	 * Add Upload
	 *
	 * Create a new upload record in the database.
	 *
	 * @param string $name
	 * @param string $filename
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $this->load->model('tool/upload');
	 *
	 * $code = $this->model_tool_upload->addUpload($name, $filename);
	 */
	public function addUpload(string $name, string $filename): string {
		$code = oc_token(32);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "upload` SET `name` = '" . $this->db->escape($name) . "', `filename` = '" . $this->db->escape($filename) . "', `code` = '" . $this->db->escape($code) . "', `date_added` = NOW()");

		return $code;
	}

	/**
	 * Delete Upload
	 *
	 * Delete upload record in the database.
	 *
	 * @param int $upload_id primary key of the upload record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('tool/upload');
	 *
	 * $this->model_tool_upload->deleteUpload($upload_id);
	 */
	public function deleteUpload(int $upload_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "upload` WHERE `upload_id` = '" . (int)$upload_id . "'");
	}

	/**
	 * Get Upload
	 *
	 * Get the record of the upload record in the database.
	 *
	 * @param int $upload_id primary key of the upload record
	 *
	 * @return array<string, mixed> upload record that has upload ID
	 *
	 * @example
	 *
	 * $this->load->model('tool/upload');
	 *
	 * $upload_info = $this->model_tool_upload->getUpload($upload_id);
	 */
	public function getUpload(int $upload_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE `upload_id` = '" . (int)$upload_id . "'");

		return $query->row;
	}

	/**
	 * Get Upload By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('tool/upload');
	 *
	 * $upload_info = $this->model_tool_upload->getUploadByCode($code);
	 */
	public function getUploadByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	/**
	 * Get Uploads
	 *
	 * Get the record of the upload records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> upload records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'      => 'Upload Name',
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'sort'             => 'date_added',
	 *     'order'            => 'DESC',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('tool/upload');
	 *
	 * $results = $this->model_tool_upload->getUploads($filter_data);
	 */
	public function getUploads(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "upload`";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "LCASE(`code`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_code']) . '%') . "'";
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

		$sort_data = [
			'name',
			'code',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `date_added`";
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
	 * Get Total Uploads
	 *
	 * Get the total number of total upload records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of upload records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'      => 'Upload Name',
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'sort'             => 'date_added',
	 *     'order'            => 'DESC',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('tool/upload');
	 *
	 * $upload_total = $this->model_tool_upload->getTotalUploads($filter_data);
	 */
	public function getTotalUploads(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "upload`";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "LCASE(`code`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_code']) . '%') . "'";
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
}
