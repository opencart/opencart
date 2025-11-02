<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Download
 *
 * Can be loaded using $this->load->model('catalog/download');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Download extends \Opencart\System\Engine\Model {
	/**
	 * Add Download
	 *
	 * Create a new download record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new download record
	 *
	 * @example
	 *
	 * $download_data = [
	 *     'download_description' => [],
	 *     'filename'             => 'download_filename',
	 *     'mask'                 => 'mask string',
	 * ];
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $download_id = $this->model_catalog_download->addDownload($download_data);
	 */
	public function addDownload(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "download` SET `filename` = '" . $this->db->escape((string)$data['filename']) . "', `mask` = '" . $this->db->escape((string)$data['mask']) . "', `date_added` = NOW()");

		$download_id = $this->db->getLastId();

		foreach ($data['download_description'] as $language_id => $download_description) {
			$this->model_catalog_download->addDescription($download_id, $language_id, $download_description);
		}

		return $download_id;
	}

	/**
	 * Edit Download
	 *
	 * Edit download record in the database.
	 *
	 * @param int                  $download_id primary key of the download record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $download_data = [
	 *     'download_description' => [],
	 *     'filename'             => 'download_filename',
	 *     'mask'                 => 'mask string',
	 * ];
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $this->model_catalog_download->editDownload($download_id, $download_data);
	 */
	public function editDownload(int $download_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "download` SET `filename` = '" . $this->db->escape((string)$data['filename']) . "', `mask` = '" . $this->db->escape((string)$data['mask']) . "' WHERE `download_id` = '" . (int)$download_id . "'");

		$this->model_catalog_download->deleteDescriptions($download_id);

		foreach ($data['download_description'] as $language_id => $download_description) {
			$this->model_catalog_download->addDescription($download_id, $language_id, $download_description);
		}
	}

	/**
	 * Delete Download
	 *
	 * Delete download record in the database.
	 *
	 * @param int $download_id primary key of the download record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $this->model_catalog_download->deleteDownload($download_id);
	 */
	public function deleteDownload(int $download_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "download` WHERE `download_id` = '" . (int)$download_id . "'");

		$this->model_catalog_download->deleteDescriptions($download_id);
		$this->model_catalog_download->deleteReports($download_id);

		// Product
		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteDownloadsByDownloadId($download_id);
	}

	/**
	 * Get Download
	 *
	 * Get the record of the download record in the database.
	 *
	 * @param int $download_id primary key of the download record
	 *
	 * @return array<string, mixed> download record that has download ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $download_info = $this->model_catalog_download->getDownload($download_id);
	 */
	public function getDownload(int $download_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "download` `d` LEFT JOIN `" . DB_PREFIX . "download_description` `dd` ON (`d`.`download_id` = `dd`.`download_id`) WHERE `d`.`download_id` = '" . (int)$download_id . "' AND `dd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Downloads
	 *
	 * Get the record of the download records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> download records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'dd.name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $results = $this->model_catalog_download->getDownloads($filter_data);
	 */
	public function getDownloads(array $data = []): array {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT * FROM `" . DB_PREFIX . "download` `d` LEFT JOIN `" . DB_PREFIX . "download_description` `dd` ON (`d`.`download_id` = `dd`.`download_id`) WHERE `dd`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`dd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		$sort_data = [
			'name'       => 'dd.name',
			'date_added' => 'd.date_added'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `dd`.`name`";
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
	 * Get Total Downloads
	 *
	 * Get the total number of download records in the database.
	 *
	 * @return int total number of download records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $download_total = $this->model_catalog_download->getTotalDownloads();
	 */
	public function getTotalDownloads(array $data = []): int {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "download` `d` LEFT JOIN `" . DB_PREFIX . "download_description` `dd` ON (`d`.`download_id` = `dd`.`download_id`) WHERE `dd`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`dd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new download description record in the database.
	 *
	 * @param int                  $download_id primary key of the download record
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $download_data['download_description'] = [
	 *     'name' => 'Download Name'
	 * ];
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $this->model_catalog_download->addDescription($download_id, $language_id, $download_data);
	 */
	public function addDescription(int $download_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "download_description` SET `download_id` = '" . (int)$download_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete download description records in the database.
	 *
	 * @param int $download_id primary key of the download record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $this->model_catalog_download->deleteDescriptions($download_id);
	 */
	public function deleteDescriptions(int $download_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "download_description` WHERE `download_id` = '" . (int)$download_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete download descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $this->model_catalog_download->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "download_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the download description records in the database.
	 *
	 * @param int $download_id primary key of the download record
	 *
	 * @return array<int, array<string, string>> description records that have download ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $download_description = $this->model_catalog_download->getDescriptions($download_id);
	 */
	public function getDescriptions(int $download_id): array {
		$download_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "download_description` WHERE `download_id` = '" . (int)$download_id . "'");

		foreach ($query->rows as $result) {
			$download_description_data[$result['language_id']] = $result;
		}

		return $download_description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the download descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $results = $this->model_catalog_download->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "download_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Get Reports
	 *
	 * Get the record of the download report records in the database.
	 *
	 * @param int $download_id primary key of the download record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> report records that have download ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $results = $this->model_catalog_download->getReports($download_id, $start, $limit);
	 */
	public function getReports(int $download_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `ip`, `store_id`, `country`, `date_added` FROM `" . DB_PREFIX . "download_report` WHERE `download_id` = '" . (int)$download_id . "' ORDER BY `date_added` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Delete Reports
	 *
	 * Delete download report records in the database.
	 *
	 * @param int $download_id primary key of the download record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $this->model_catalog_download->deleteReports($download_id);
	 */
	public function deleteReports(int $download_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "download_report` WHERE `download_id` = '" . (int)$download_id . "'");
	}

	/**
	 * Get Total Reports
	 *
	 * Get the total number of download report records in the database.
	 *
	 * @param int $download_id primary key of the download record
	 *
	 * @return int total number of report records that have download ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/download');
	 *
	 * $report_total = $this->model_catalog_download->getTotalReports($download_id);
	 */
	public function getTotalReports(int $download_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "download_report` WHERE `download_id` = '" . (int)$download_id . "'");

		return (int)$query->row['total'];
	}
}
