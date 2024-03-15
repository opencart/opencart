<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Option
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Option extends \Opencart\System\Engine\Model {
	/**
	 * Add Option
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addOption(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET `type` = '" . $this->db->escape((string)$data['type']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

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
	 * @param int                  $option_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editOption(int $option_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `type` = '" . $this->db->escape((string)$data['type']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `option_id` = '" . (int)$option_id . "'");

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
	 * @param int $option_id
	 *
	 * @return void
	 */
	public function deleteOption(int $option_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option` WHERE `option_id` = '" . (int)$option_id . "'");

		$this->model_catalog_option->deleteDescriptions($option_id);
		$this->model_catalog_option->deleteValues($option_id);
	}

	/**
	 * Get Option
	 *
	 * @param int $option_id
	 *
	 * @return array<string, mixed>
	 */
	public function getOption(int $option_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` `o` LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`o`.`option_id` = `od`.`option_id`) WHERE `o`.`option_id` = '" . (int)$option_id . "' AND `od`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Options
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getOptions(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "option` `o` LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`o`.`option_id` = `od`.`option_id`) WHERE `od`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`od`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		$sort_data = [
			'od.name',
			'o.type',
			'o.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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
	 * @return int
	 */
	public function getTotalOptions(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "option`");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * @param int                  $option_id   primary key
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addDescription(int $option_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_description` SET `option_id` = '" . (int)$option_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * @param int $option_id
	 *
	 * @return void
	 */
	public function deleteDescriptions(int $option_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_description` WHERE `option_id` = '" . (int)$option_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $option_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptions(int $option_id): array {
		$description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_description` WHERE `option_id` = '" . (int)$option_id . "'");

		foreach ($query->rows as $result) {
			$description_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Add Value
	 *
	 * @param int                  $option_id
	 * @param array<string, mixed> $data
	 */
	public function addValue(int $option_id, array $data): int {
		if ($data['option_value_id']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value` SET `option_value_id` = '" . (int)$data['option_value_id'] . "', `option_id` = '" . (int)$option_id . "', `image` = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value` SET `option_id` = '" . (int)$option_id . "', `image` = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");
		}

		$option_value_id = $this->db->getLastId();

		if ($data['option_value_id']) {
			if (isset($data['option_value_description'])) {
				foreach ($data['option_value_description'] as $language_id => $option_value_description) {
					$this->model_catalog_option->addValueDescription($option_value_id, $option_id, $language_id, $option_value_description);
				}
			}
		}

		return $option_value_id;
	}

	/**
	 * Delete Values
	 *
	 * @param int $option_id
	 *
	 * @return void
	 */
	public function deleteValues(int $option_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_value` WHERE `option_id` = '" . (int)$option_id . "'");

		$this->model_catalog_option->deleteValueDescriptionsByOptionId($option_id);
	}

	/**
	 * Get Value
	 *
	 * @param int $option_value_id
	 *
	 * @return array<string, mixed>
	 */
	public function getValue(int $option_value_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value` `ov` LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ov`.`option_value_id` = `ovd`.`option_value_id`) WHERE `ov`.`option_value_id` = '" . (int)$option_value_id . "' AND `ovd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Values
	 *
	 * @param int $option_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getValues(int $option_id): array {
		$option_value_data = [];

		$option_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value` `ov` LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ov`.`option_value_id` = `ovd`.`option_value_id`) WHERE `ov`.`option_id` = '" . (int)$option_id . "' AND `ovd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `ov`.`sort_order`, `ovd`.`name`");

		foreach ($option_value_query->rows as $option_value) {
			$option_value_data[] = [
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				'image'           => $option_value['image'],
				'sort_order'      => $option_value['sort_order']
			];
		}

		return $option_value_data;
	}

	/**
	 * Add Value Description
	 *
	 * @param int                  $option_value_id
	 * @param int                  $option_id
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addValueDescription(int $option_value_id, int $option_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value_description` SET `option_value_id` = '" . (int)$option_value_id . "', `language_id` = '" . (int)$language_id . "', `option_id` = '" . (int)$option_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Value Descriptions By Option ID
	 *
	 * @param int $option_id
	 *
	 * @return void
	 */
	public function deleteValueDescriptionsByOptionId(int $option_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_value_description` WHERE `option_id` = '" . (int)$option_id . "'");
	}

	/**
	 * Delete Value Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteValueDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_value_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Value Descriptions
	 *
	 * @param int $option_id
	 *
	 * @return array<int, array<string, mixed>>
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

			$option_value_data[] = [
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value_description_data,
				'image'                    => $option_value['image'],
				'sort_order'               => $option_value['sort_order']
			];
		}

		return $option_value_data;
	}

	/**
	 * Get Value Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getValueDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
