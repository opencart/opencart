<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Return Action
 *
 * Can be loaded using $this->load->model('localisation/return_action');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class ReturnAction extends \Opencart\System\Engine\Model {
	/**
	 * Add Return Action
	 *
	 * Create a new return action record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return ?int
	 *
	 * @example
	 *
	 * $return_action_data['return_action'][1] = [
	 *     'name' => 'Return Action Name'
	 * ];
	 *
	 * $this->load->model('localisation/return_action');
	 *
	 * $return_action_id = $this->model_localisation_return_action->addReturnAction($return_action_data);
	 */
	public function addReturnAction(array $data): ?int {
		$return_action_id = 0;

		foreach ($data['return_action'] as $language_id => $return_action) {
			if (!$return_action_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "return_action` SET `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($return_action['name']) . "'");

				$return_action_id = $this->db->getLastId();
			} else {
				$this->model_localisation_return_action->addDescription($return_action_id, $language_id, $return_action);
			}
		}

		$this->cache->delete('return_action');

		return $return_action_id;
	}

	/**
	 * Edit Return Action
	 *
	 * Edit return action record in the database.
	 *
	 * @param int                  $return_action_id primary key of the return action record
	 * @param array<string, mixed> $data             array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $return_action_data['return_action'][1] = [
	 *     'name' => 'Return Action Name'
	 * ];
	 *
	 * $this->load->model('localisation/return_action');
	 *
	 * $this->model_localisation_return_action->editReturnAction($return_action_id, $return_action_data);
	 */
	public function editReturnAction(int $return_action_id, array $data): void {
		$this->deleteReturnAction($return_action_id);

		foreach ($data['return_action'] as $language_id => $return_action) {
			$this->model_localisation_return_action->addDescription($return_action_id, $language_id, $return_action);
		}

		$this->cache->delete('return_action');
	}

	/**
	 * Delete Return Action
	 *
	 * Delete return action record in the database.
	 *
	 * @param int $return_action_id primary key of the return action record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_action');
	 *
	 * $this->model_localisation_return_action->deleteReturnAction($return_action_id);
	 */
	public function deleteReturnAction(int $return_action_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_action` WHERE `return_action_id` = '" . (int)$return_action_id . "'");

		$this->cache->delete('return_action');
	}

	/**
	 * Delete Return Actions By Language ID
	 *
	 * Delete return actions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_action');
	 *
	 * $this->model_localisation_return_action->deleteReturnActionsByLanguageId($language_id);
	 */
	public function deleteReturnActionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_action` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('return_action');
	}

	/**
	 * Get Return Action
	 *
	 * Get the record of the return action record in the database.
	 *
	 * @param int $return_action_id primary key of the return action record
	 *
	 * @return array<string, mixed> return action record that has return action ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_action');
	 *
	 * $return_action_info = $this->model_localisation_return_action->getReturnAction($return_action_id);
	 */
	public function getReturnAction(int $return_action_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_action` WHERE `return_action_id` = '" . (int)$return_action_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Return Actions
	 *
	 * Get the record of the return action records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> return action records
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
	 * $this->load->model('localisation/return_action');
	 *
	 * $results = $this->model_localisation_return_action->getReturnActions($filter_data);
	 */
	public function getReturnActions(array $data = []): array {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT * FROM `" . DB_PREFIX . "return_action` WHERE `language_id` = '" . (int)$language_id . "' ORDER BY `name`";

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

		$return_action_data = $this->cache->get('return_action.' . $key);

		if (!$return_action_data) {
			$query = $this->db->query($sql);

			$return_action_data = $query->rows;

			$this->cache->set('return_action.' . $key, $return_action_data);
		}

		return $return_action_data;
	}

	/**
	 * Get Total Return Actions
	 *
	 * Get the total number of return action records in the database.
	 *
	 * @return int total number of return action records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_action');
	 *
	 * $return_action_total = $this->model_localisation_return_action->getTotalReturnActions();
	 */
	public function getTotalReturnActions(array $data = []): int {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return_action` WHERE `language_id` = '" . (int)$language_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new return action description record in the database.
	 *
	 * @param int                  $return_action_id primary key of the return action record
	 * @param int                  $language_id      primary key of the language record
	 * @param array<string, mixed> $data             array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $return_action_data = [
	 *     'return_action_id' => 1,
	 *     'language_id'      => 1,
	 *     'name'             => 'Return Action Name'
	 * ];
	 *
	 * $this->load->model('localisation/return_action');
	 *
	 * $this->model_localisation_return_action->addDescription($return_action_id, $language_id, $return_action_data);
	 */
	public function addDescription(int $return_action_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return_action` SET `return_action_id` = '" . (int)$return_action_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete country descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $this->model_localisation_country->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_action` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Description
	 *
	 * Get the record of the country description in the database.
	 *
	 * @param int $country_id  primary key of the country record
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<string, mixed> country description record
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $description = $this->model_localisation_country->getDescription($country_id, $language_id);
	 */
	public function getDescription(int $return_action_id, int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_action` WHERE `return_action_id` = '" . (int)$return_action_id . "' AND `language_id` = '" . (int)$language_id . "'");

		return $query->row;
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the country description records in the database.
	 *
	 * @param int $return_action_id primary key of the return action record
	 *
	 * @return array<int, array<string, string>> description records that have return action ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_action');
	 *
	 * $return_action = $this->model_localisation_return_action->getDescriptions($return_action_id);
	 */
	public function getDescriptions(int $return_action_id): array {
		$return_action_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_action` WHERE `return_action_id` = '" . (int)$return_action_id . "'");

		foreach ($query->rows as $result) {
			$return_action_data[$result['language_id']] = $result;
		}

		return $return_action_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the country descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/return_action');
	 *
	 * $results = $this->model_localisation_return_action->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_action` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
