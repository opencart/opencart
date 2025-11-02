<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Option
 *
 * Can be loaded using $this->load->model('catalog/option');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Option extends \Opencart\System\Engine\Model {
	/**
	 * Add Option
	 *
	 * Create a new option record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new option record
	 *
	 * @example
	 *
	 * $option_data = [
	 *     'option_description' => [],
	 *     'type'               => 'radio',
	 *     'validation'         => '',
	 *     'sort_order'         => 0
	 * ];
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $option_id = $this->model_catalog_option->addOption($option_data);
	 */
	public function addOption(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET `type` = '" . $this->db->escape((string)$data['type']) . "', `validation` = '" . $this->db->escape((string)$data['validation']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$option_id = $this->db->getLastId();

		foreach ($data['option_description'] as $language_id => $value) {
			$this->model_catalog_option->addDescription($option_id, $language_id, $value);
		}

		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				$this->model_catalog_option->addValue($option_id, $option_value);
			}
		}

		return $option_id;
	}

	/**
	 * Edit Option
	 *
	 * Edit option record in the database.
	 *
	 * @param int                  $option_id primary key of the option record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $option_data = [
	 *     'option_description' => [],
	 *     'type'               => 'radio',
	 *     'validation'         => '',
	 *     'sort_order'         => 0
	 * ];
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->editOption($option_id, $option_data);
	 */
	public function editOption(int $option_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `type` = '" . $this->db->escape((string)$data['type']) . "', `validation` = '" . $this->db->escape((string)$data['validation']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `option_id` = '" . (int)$option_id . "'");

		$this->model_catalog_option->deleteDescriptions($option_id);

		foreach ($data['option_description'] as $language_id => $value) {
			$this->model_catalog_option->addDescription($option_id, $language_id, $value);
		}

		$this->model_catalog_option->deleteValues($option_id);

		if (isset($data['option_value'])) {
			foreach ($data['option_value'] as $option_value) {
				$this->model_catalog_option->addValue($option_id, $option_value);
			}
		}
	}

	/**
	 * Delete Option
	 *
	 * Delete option record in the database.
	 *
	 * @param int $option_id primary key of the option record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->deleteOption($option_id);
	 */
	public function deleteOption(int $option_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option` WHERE `option_id` = '" . (int)$option_id . "'");

		$this->model_catalog_option->deleteDescriptions($option_id);
		$this->model_catalog_option->deleteValues($option_id);
	}

	/**
	 * Get Option
	 *
	 * Get the record of the option record in the database.
	 *
	 * @param int $option_id primary key of the option record
	 *
	 * @return array<string, mixed> option record that has option ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $option_info = $this->model_catalog_option->getOption($option_id);
	 */
	public function getOption(int $option_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` `o` LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`o`.`option_id` = `od`.`option_id`) WHERE `o`.`option_id` = '" . (int)$option_id . "' AND `od`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Options
	 *
	 * Get the record of the option records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> option records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'od.name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $results = $this->model_catalog_option->getOptions($filter_data);
	 */
	public function getOptions(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "option` `o` LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`o`.`option_id` = `od`.`option_id`) WHERE `od`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`od`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		$sort_data = [
			'name'       => 'od.name',
			'type'       => 'o.type',
			'sort_order' => 'o.sort_order'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `od`.`name`";
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
	 * Get Total Options
	 *
	 * Get the total number of option records in the database.
	 *
	 * @return int total number of option records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $option_total = $this->model_catalog_option->getTotalOptions();
	 */
	public function getTotalOptions(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "option`");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new option description record in the database.
	 *
	 * @param int                  $option_id   primary key of the option record
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $option_data['option_description'] = [
	 *     'name' => 'Option Name'
	 * ];
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->addDescription($option_id, $language_id, $option_data);
	 */
	public function addDescription(int $option_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_description` SET `option_id` = '" . (int)$option_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete option description records in the database.
	 *
	 * @param int $option_id primary key of the option record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->deleteDescriptions($option_id);
	 */
	public function deleteDescriptions(int $option_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_description` WHERE `option_id` = '" . (int)$option_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete option descriptions by language records n the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the option description records in the database.
	 *
	 * @param int $option_id primary key of the option record
	 *
	 * @return array<int, array<string, string>> description records that have option ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $option_description = $this->model_catalog_option->getDescriptions($option_id);
	 */
	public function getDescriptions(int $option_id): array {
		$description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_description` WHERE `option_id` = '" . (int)$option_id . "'");

		foreach ($query->rows as $result) {
			$description_data[$result['language_id']] = $result;
		}

		return $description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the option descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $results = $this->model_catalog_option->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Add Value
	 *
	 * Create a new option value record in the database.
	 *
	 * @param int                  $option_id primary key of the option record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @return int returns the primary key of the new option value record
	 *
	 * @example
	 *
	 * $option_value_data = [
	 *     'option_value_description' => [],
	 *     'option_value_id'          => 0,
	 *     'option_id'                => 1,
	 *     'image'                    => 'option_image',
	 *     'sort_order'               => 0
	 * ];
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->addValue($option_id, $option_value_data);
	 */
	public function addValue(int $option_id, array $data): int {
		if ($data['option_value_id']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value` SET `option_value_id` = '" . (int)$data['option_value_id'] . "', `option_id` = '" . (int)$option_id . "', `image` = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

			$option_value_id = $data['option_value_id'];
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value` SET `option_id` = '" . (int)$option_id . "', `image` = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

			$option_value_id = $this->db->getLastId();
		}

		if (isset($data['option_value_description'])) {
			foreach ($data['option_value_description'] as $language_id => $option_value_description) {
				$this->model_catalog_option->addValueDescription($option_value_id, $option_id, $language_id, $option_value_description);
			}
		}

		return $option_value_id;
	}

	/**
	 * Delete Values
	 *
	 * Delete option value records in the database.
	 *
	 * @param int $option_id primary key of the option record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->deleteValues($option_id);
	 */
	public function deleteValues(int $option_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_value` WHERE `option_id` = '" . (int)$option_id . "'");

		$this->model_catalog_option->deleteValueDescriptionsByOptionId($option_id);
	}

	/**
	 * Get Value
	 *
	 * Get the record of the option value record in the database.
	 *
	 * @param int $option_value_id primary key of the option value record
	 *
	 * @return array<string, mixed> value record that has option value ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $option_value_info = $this->model_catalog_option->getValue($option_value_id);
	 */
	public function getValue(int $option_value_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value` `ov` LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ov`.`option_value_id` = `ovd`.`option_value_id`) WHERE `ov`.`option_value_id` = '" . (int)$option_value_id . "' AND `ovd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Values
	 *
	 * Get the record of the option value records in the database.
	 *
	 * @param int $option_id primary key of the option record
	 *
	 * @return array<int, array<string, mixed>> value records that have option ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $option_values = $this->model_catalog_option->getValues($option_id);
	 */
	public function getValues(int $option_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value` `ov` LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ov`.`option_value_id` = `ovd`.`option_value_id`) WHERE `ov`.`option_id` = '" . (int)$option_id . "' AND `ovd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `ov`.`sort_order`, `ovd`.`name`");

		return $query->rows;
	}

	/**
	 * Add Value Description
	 *
	 * Create a new option value description record in the database.
	 *
	 * @param int                  $option_value_id primary key of the option value record
	 * @param int                  $option_id       primary key of the option record
	 * @param int                  $language_id     primary key of the language record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $option_value_description_data[1] = [
	 *     'name' => 'Option Value Name'
	 * ];
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->addValueDescription($option_value_id, $option_id, $language_id, $option_value_description_data);
	 */
	public function addValueDescription(int $option_value_id, int $option_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value_description` SET `option_value_id` = '" . (int)$option_value_id . "', `language_id` = '" . (int)$language_id . "', `option_id` = '" . (int)$option_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Value Descriptions By Option ID
	 *
	 * Delete option value descriptions by option records in the database.
	 *
	 * @param int $option_id primary key of the option record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->deleteValueDescriptionsByOptionId($option_id);
	 */
	public function deleteValueDescriptionsByOptionId(int $option_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_value_description` WHERE `option_id` = '" . (int)$option_id . "'");
	}

	/**
	 * Delete Value Descriptions By Language ID
	 *
	 * Delete option value descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $this->model_catalog_option->deleteValueDescriptionsByLanguageId($language_id);
	 */
	public function deleteValueDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_value_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Value Descriptions
	 *
	 * Get the record of the option value description records in the database.
	 *
	 * @param int $option_id primary key of the option record
	 *
	 * @return array<int, array<string, mixed>> value description records that have option ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $option_values = $this->model_catalog_option->getValueDescriptions($option_id);
	 */
	public function getValueDescriptions(int $option_id): array {
		$option_value_data = [];

		$option_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value` WHERE `option_id` = '" . (int)$option_id . "' ORDER BY `sort_order`");

		foreach ($option_value_query->rows as $option_value) {
			$option_value_description_data = [];

			$option_value_description_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value_description` WHERE `option_value_id` = '" . (int)$option_value['option_value_id'] . "'");

			foreach ($option_value_description_query->rows as $option_value_description) {
				$option_value_description_data[$option_value_description['language_id']] = ['name' => $option_value_description['name']];
			}

			$option_value_data[] = ['option_value_description' => $option_value_description_data] + $option_value;
		}

		return $option_value_data;
	}

	/**
	 * Get Value Descriptions By Language ID
	 *
	 * Get the record of the option value descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> value description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/option');
	 *
	 * $results = $this->model_catalog_option->getValueDescriptionsByLanguageId($language_id);
	 */
	public function getValueDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
