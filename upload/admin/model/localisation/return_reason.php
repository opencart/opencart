<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Return Reason
 *
 * Can be loaded using $this->load->model('localisation/return_reason');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class ReturnReason extends \Opencart\System\Engine\Model {
	/**
	 * Add Return Reason
	 *
	 * Create a new return reason record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return ?int
	 *
	 * @example
	 *
	 * $return_reason_data['return_reason'][1] = [
	 *     'name' => 'Return Reason Name'
	 * ];
	 *
	 * $this->>load->model('localisation/return_reason');
	 *
	 * $return_reason_id = $this->model_localisation_return_reason->addReturnReason($return_reason_data);
	 */
	public function addReturnReason(array $data): ?int {
		$return_reason_id = 0;

		foreach ($data['return_reason'] as $language_id => $return_reason) {
			if (!$return_reason_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "return_reason` SET `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($return_reason['name']) . "'");

				$return_reason_id = $this->db->getLastId();
			} else {
				$this->model_localisation_return_reason->addDescription($return_reason_id, $language_id, $return_reason);
			}
		}

		$this->cache->delete('return_reason');

		return $return_reason_id;
	}

	/**
	 * Edit Return Reason
	 *
	 * Edit return reason record in the database.
	 *
	 * @param int                  $return_reason_id primary key of the return reason record
	 * @param array<string, mixed> $data             array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $return_reason_data['return_reason'][1] = [
	 *     'name' => 'Return Reason Name'
	 * ];
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $this->model_localisation_return_reason->editReturnReason($return_reason_id, $return_reason_data);
	 */
	public function editReturnReason(int $return_reason_id, array $data): void {
		$this->deleteReturnReason($return_reason_id);

		foreach ($data['return_reason'] as $language_id => $return_reason) {
			$this->model_localisation_return_reason->addDescription($return_reason_id, $language_id, $return_reason);
		}

		$this->cache->delete('return_reason');
	}

	/**
	 * Delete Return Reason
	 *
	 * Delete return reason record in the database.
	 *
	 * @param int $return_reason_id primary key of the return reason record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $this->model_localisation_return_reason->deleteReturnReason($return_reason_id);
	 */
	public function deleteReturnReason(int $return_reason_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_reason` WHERE `return_reason_id` = '" . (int)$return_reason_id . "'");

		$this->cache->delete('return_reason');
	}

	/**
	 * Delete Return Reasons By Language ID
	 *
	 * Delete return reasons by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $this->model_localisation_return_reason->deleteReturnReasonsByLanguageId($language_id);
	 */
	public function deleteReturnReasonsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('return_reason');
	}

	/**
	 * Get Return Reason
	 *
	 * Get the record of the return reason record in the database.
	 *
	 * @param int $return_reason_id primary key of the return reason record
	 *
	 * @return array<string, mixed> return reason record that has return reason ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $return_reason_info = $this->model_localisation_return_reason->getReturnReason($return_reason_id);
	 */
	public function getReturnReason(int $return_reason_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `return_reason_id` = '" . (int)$return_reason_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Return Reasons
	 *
	 * Get the record of the return reason records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> return reason records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $results = $this->model_localisation_return_reason->getReturnReasons($filter_data);
	 */
	public function getReturnReasons(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`";

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

		$key = md5($sql);

		$return_reason_data = $this->cache->get('return_reason.' . $key);

		if (!$return_reason_data) {
			$query = $this->db->query($sql);

			$return_reason_data = $query->rows;

			$this->cache->set('return_reason.' . $key, $return_reason_data);
		}

		return $return_reason_data;
	}

	/**
	 * Add Description
	 *
	 * Create a new return reason description record in the database.
	 *
	 * @param int                  $return_reason_id primary key of the return reason record
	 * @param int                  $language_id      primary key of the language record
	 * @param array<string, mixed> $data             array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $return_reason_data = [
	 *     'return_reason_id' => 1,
	 *     'language_id'      => 1,
	 *     'name'             => 'Return Reason Name'
	 * ];
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $this->model_localisation_return_reason->addDescription($return_reason_id, $language_id, $return_reason_data);
	 */
	public function addDescription(int $return_reason_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return_reason` SET `return_reason_id` = '" . (int)$return_reason_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the return reason description records in the database.
	 *
	 * @param int $return_reason_id primary key of the return reason record
	 *
	 * @return array<int, array<string, string>> description records that have return reason ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $return_reason = $this->model_localisation_return_reason->getDescriptions($return_reason_id);
	 */
	public function getDescriptions(int $return_reason_id): array {
		$return_reason_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `return_reason_id` = '" . (int)$return_reason_id . "'");

		foreach ($query->rows as $result) {
			$return_reason_data[$result['language_id']] = $result;
		}

		return $return_reason_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the return reason descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, mixed>> description records by language ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $results = $this->model_localisation_return_reason->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Return Reasons
	 *
	 * Get the total number of return reason records in the database.
	 *
	 * @return int total number of return reason records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_reason');
	 *
	 * $return_reason_total = $this->model_localisation_return_reason->getTotalReturnReasons();
	 */
	public function getTotalReturnReasons(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return (int)$query->row['total'];
	}
}
