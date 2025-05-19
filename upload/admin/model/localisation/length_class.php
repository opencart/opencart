<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Length Class
 *
 * Can be loaded using $this->load->model('localisation/length_class');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class LengthClass extends \Opencart\System\Engine\Model {
	/**
	 * Add Length Class
	 *
	 * Create a new length class record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new length class record
	 *
	 * @example
	 *
	 * $length_class_data = [
	 *     'length_class_description' => [],
	 *     'value'                    => 0.00000000
	 * ];
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $length_class_id = $this->model_localisation_length_class->addLengthClass($length_class_data);
	 */
	public function addLengthClass(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "length_class` SET `value` = '" . (float)$data['value'] . "'");

		$length_class_id = $this->db->getLastId();

		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->addDescription($length_class_id, $language_id, $value);
		}

		$this->cache->delete('length_class');

		return $length_class_id;
	}

	/**
	 * Edit Length Class
	 *
	 * Edit length class record in the database.
	 *
	 * @param int                  $length_class_id primary key of the length class record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $length_class_data = [
	 *     'length_class_description' => [],
	 *     'value'                    => 0.00000000
	 * ];
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $this->model_localisation_length_class->editLengthClass($length_class_id, $length_class_data);
	 */
	public function editLengthClass(int $length_class_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "length_class` SET `value` = '" . (float)$data['value'] . "' WHERE `length_class_id` = '" . (int)$length_class_id . "'");

		$this->deleteDescriptions($length_class_id);

		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->addDescription($length_class_id, $language_id, $value);
		}

		$this->cache->delete('length_class');
	}

	/**
	 * Delete Length Class
	 *
	 * Delete length class record in the database.
	 *
	 * @param int $length_class_id primary key of the length class record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $this->model_localisation_length_class->deleteLengthClass($length_class_id);
	 */
	public function deleteLengthClass(int $length_class_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "length_class` WHERE `length_class_id` = '" . (int)$length_class_id . "'");

		$this->deleteDescriptions($length_class_id);

		$this->cache->delete('length_class');
	}

	/**
	 * Delete Length Classes By Language ID
	 *
	 * Delete length classes by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $this->model_localisation_length_class->deleteLengthClassesByLanguageId($language_id);
	 */
	public function deleteLengthClassesByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "length_class` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('length_class');
	}

	/**
	 * Get Length Classes
	 *
	 * Get the record of the length class records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> length class records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'title',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $length_classes = $this->model_localisation_length_class->getLengthClasses($filter_data);
	 */
	public function getLengthClasses(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "length_class` `lc` LEFT JOIN `" . DB_PREFIX . "length_class_description` `lcd` ON (`lc`.`length_class_id` = `lcd`.`length_class_id`) WHERE `lcd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = [
			'title',
			'unit',
			'value'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `title`";
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

		$key = md5($sql);

		$length_class_data = $this->cache->get('length_class.' . $key);

		if (!$length_class_data) {
			$query = $this->db->query($sql);

			$length_class_data = $query->rows;

			$this->cache->set('length_class.' . $key, $length_class_data);
		}

		return $length_class_data;
	}

	/**
	 * Get Length Class
	 *
	 * Get the record of the length class record in the database.
	 *
	 * @param int $length_class_id primary key of the length class record
	 *
	 * @return array<string, mixed> length class record that has length class ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $length_class_info = $this->model_localisation_length_class->getLengthClass($length_class_id);
	 */
	public function getLengthClass(int $length_class_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class` `lc` LEFT JOIN `" . DB_PREFIX . "length_class_description` `lcd` ON (`lc`.`length_class_id` = `lcd`.`length_class_id`) WHERE `lc`.`length_class_id` = '" . (int)$length_class_id . "' AND `lcd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Add Description
	 *
	 * Create a new length class description record in the database.
	 *
	 * @param int                  $length_class_id primary key of the length class record
	 * @param int                  $language_id     primary key of the language record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $length_class_data['length_class_description'] = [
	 *     'length_class_id' => 1,
	 *     'language_id'     => 1,
	 *     'title'           => 'Length Class Title',
	 *     'unit'            => 'cm'
	 * ];
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $this->model_localisation_length_class->addDescription($length_class_id, $language_id, $length_class_data);
	 */
	public function addDescription(int $length_class_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "length_class_description` SET `length_class_id` = '" . (int)$length_class_id . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($data['title']) . "', `unit` = '" . $this->db->escape($data['unit']) . "'");
	}

	/**
	 * Delete Description
	 *
	 * Delete length class description record in the database.
	 *
	 * @param int $length_class_id primary key of the length class record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $this->model_localisation_length_class->deleteDescriptions($length_class_id);
	 */
	public function deleteDescriptions(int $length_class_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "length_class_description` WHERE `length_class_id` = '" . (int)$length_class_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete length class descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $this->model_localisation_length_class->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "length_class_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the length class description records in the database.
	 *
	 * @param int $length_class_id primary key of the length class record
	 *
	 * @return array<int, array<string, mixed>> description records that have length class ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $length_class_description = $this->model_localisation_length_class->getDescriptions($length_class_id);
	 */
	public function getDescriptions(int $length_class_id): array {
		$length_class_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class_description` WHERE `length_class_id` = '" . (int)$length_class_id . "'");

		foreach ($query->rows as $result) {
			$length_class_data[$result['language_id']] = $result;
		}

		return $length_class_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the length class description by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $results = $this->model_localisation_length_class->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Get Description By Unit
	 *
	 * @param string $unit
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $length_class_info = $this->model_localisation_length_class->getDescriptionByUnit($unit);
	 */
	public function getDescriptionByUnit(string $unit): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class_description` WHERE `unit` = '" . $this->db->escape($unit) . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Total Length Classes
	 *
	 * Get the total number of length class records in the database.
	 *
	 * @return int total number of length class records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/length_class');
	 *
	 * $length_class_total = $this->model_localisation_length_class->getTotalLengthClasses();
	 */
	public function getTotalLengthClasses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "length_class`");

		return (int)$query->row['total'];
	}
}
