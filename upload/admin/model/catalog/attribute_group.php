<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Attribute Group
 *
 * Can be loaded using $this->load->model('catalog/attribute_group');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class AttributeGroup extends \Opencart\System\Engine\Model {
	/**
	 * Add Attribute Group
	 *
	 * Create a new attribute group record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new attribute group record
	 *
	 * @example
	 *
	 * $attribute_group_data = [
	 *     'attribute_group_description => [],
	 *     'sort_order'                 => 0,
	 * ];
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $attribute_group_id = $this->model_catalog_attribute_group->addAttributeGroup($attribute_group_data);
	 */
	public function addAttributeGroup(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group` SET `sort_order` = '" . (int)$data['sort_order'] . "'");

		$attribute_group_id = $this->db->getLastId();

		foreach ($data['attribute_group_description'] as $language_id => $attribute_group_description) {
			$this->model_catalog_attribute_group->addDescription($attribute_group_id, $language_id, $attribute_group_description);
		}

		return $attribute_group_id;
	}

	/**
	 * Edit Attribute Group
	 *
	 * Edit attribute group record in the database.
	 *
	 * @param int                  $attribute_group_id primary key of the attribute group record
	 * @param array<string, mixed> $data               array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $attribute_group_data = [
	 *     'attribute_group_description' => [],
	 *     'sort_order'                  => 0
	 * ];
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $this->model_catalog_attribute_group->editAttributeGroup($attribute_group_id, $attribute_group_data);
	 */
	public function editAttributeGroup(int $attribute_group_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "attribute_group` SET `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");

		$this->deleteDescriptions($attribute_group_id);

		foreach ($data['attribute_group_description'] as $language_id => $attribute_group_description) {
			$this->addDescription($attribute_group_id, $language_id, $attribute_group_description);
		}
	}

	/**
	 * Delete Attribute Group
	 *
	 * Delete attribute group record in the database.
	 *
	 * @param int $attribute_group_id primary key of the attribute group record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $this->model_catalog_attribute_group->deleteAttributeGroup($attribute_group_id);
	 */
	public function deleteAttributeGroup(int $attribute_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_group` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");

		$this->model_catalog_attribute_group->deleteDescriptions($attribute_group_id);
	}

	/**
	 * Get Attribute Group
	 *
	 * Get the record of the attribute group record in the database.
	 *
	 * @param int $attribute_group_id primary key of the attribute group record
	 *
	 * @return array<string, mixed> attribute group record that has attribute group ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $attribute_group_info = $this->model_catalog_attribute_group->getAttributeGroup($attribute_group_id);
	 */
	public function getAttributeGroup(int $attribute_group_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_group` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");

		return $query->row;
	}

	/**
	 * Get Attribute Groups
	 *
	 * Get the record of the attribute group records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> attribute group records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $filter_data = [
	 *	  'sort'  => 'agd.name',
	 *	  'order' => 'DESC',
	 *	  'start' => 0,
	 *	  'limit' => 10
	 * ];
	 *
	 * $attribute_groups = $this->model_catalog_attribute_group->getAttributeGroups($filter_data);
	 */
	public function getAttributeGroups(array $data = []): array {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT * FROM `" . DB_PREFIX . "attribute_group` `ag` LEFT JOIN `" . DB_PREFIX . "attribute_group_description` `agd` ON (`ag`.`attribute_group_id` = `agd`.`attribute_group_id`) WHERE `agd`.`language_id` = '" . (int)$language_id . "'";

		$sort_data = [
			'name'       => 'agd.name',
			'sort_order' => 'ag.sort_order'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `agd`.`name`";
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
	 * Get Total Attribute Groups
	 *
	 * Get the total number of attribute group records in the database.
	 *
	 * @return int total number of attribute group records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $attribute_group_total = $this->model_catalog_attribute_group->getTotalAttributeGroups();
	 */
	public function getTotalAttributeGroups(array $data = []): int {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "attribute_group` `ag` LEFT JOIN `" . DB_PREFIX . "attribute_group_description` `agd` ON (`ag`.`attribute_group_id` = `agd`.`attribute_group_id`) WHERE `agd`.`language_id` = '" . (int)$language_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new attribute group description record in the database.
	 *
	 * @param int                  $attribute_group_id primary key of the attribute group record
	 * @param int                  $language_id        primary key of the language record
	 * @param array<string, mixed> $data               array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $attribute_group_data['attribute_group_description'] = [
	 *     'attribute_group_id' => 1,
	 *	   'language_id'        => 1,
	 *	   'name'               => 'Attribute Group Name'
	 * ];
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $this->model_catalog_attribute_group->addDescription($attribute_group_id, $language_id, $attribute_group_data);
	 */
	public function addDescription(int $attribute_group_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group_description` SET `attribute_group_id` = '" . (int)$attribute_group_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete attribute group description records in the database.
	 *
	 * @param int $attribute_group_id primary key of the attribute group record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $this->model_catalog_attribute_group->deleteDescriptions($attribute_group_id);
	 */
	public function deleteDescriptions(int $attribute_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_group_description` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete attribute descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $this->model_catalog_attribute_group->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_group_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the attribute group description records in the database.
	 *
	 * @param int $attribute_group_id primary key of the attribute group record
	 *
	 * @return array<int, array<string, string>> description records that have attribute group ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $attribute_group_description = $this->model_catalog_attribute_group->getDescriptions($attribute_group_id);
	 */
	public function getDescriptions(int $attribute_group_id): array {
		$attribute_group_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_group_description` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");

		foreach ($query->rows as $result) {
			$attribute_group_data[$result['language_id']] = $result;
		}

		return $attribute_group_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the attribute group descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/attribute_group');
	 *
	 * $results = $this->model_catalog_attribute_group->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_group_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
