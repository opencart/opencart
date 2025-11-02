<?php
namespace Opencart\Admin\Model\Customer;
/**
 * Class Custom Field
 *
 * Can be loaded using $this->load->model('customer/custom_field');
 *
 * @package Opencart\Admin\Model\Customer
 */
class CustomField extends \Opencart\System\Engine\Model {
	/**
	 * Add Custom Field
	 *
	 * Create a new custom field record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new custom field record
	 *
	 * @example
	 *
	 * $custom_field_data = [
	 *     'custom_field_description' => [],
	 *     'type'                     => 'radio',
	 *     'value'                    => 'Custom Field Value',
	 *     'validation'               => '',
	 *     'location'                 => 'account',
	 *     'status'                   => 0,
	 *     'sort_order'               => 0
	 * ];
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $custom_field_id = $this->model_customer_custom_field->addCustomField($custom_field_data);
	 */
	public function addCustomField(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field` SET `type` = '" . $this->db->escape((string)$data['type']) . "', `value` = '" . $this->db->escape((string)$data['value']) . "', `validation` = '" . $this->db->escape((string)$data['validation']) . "', `location` = '" . $this->db->escape((string)$data['location']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$custom_field_id = $this->db->getLastId();

		foreach ($data['custom_field_description'] as $language_id => $custom_field_description) {
			$this->addDescription($custom_field_id, $language_id, $custom_field_description);
		}

		if (isset($data['custom_field_customer_group'])) {
			foreach ($data['custom_field_customer_group'] as $custom_field_customer_group) {
				if (isset($custom_field_customer_group['customer_group_id'])) {
					$this->addCustomerGroup($custom_field_id, $custom_field_customer_group);
				}
			}
		}

		if (isset($data['custom_field_value'])) {
			foreach ($data['custom_field_value'] as $custom_field_value) {
				$this->addValue($custom_field_id, $custom_field_value);
			}
		}

		return $custom_field_id;
	}

	/**
	 * Edit Custom Field
	 *
	 * Edit custom field record in the database.
	 *
	 * @param int                  $custom_field_id primary key of the custom field record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $custom_field_data = [
	 *     'custom_field_description' => [],
	 *     'type'                     => 'radio',
	 *     'value'                    => 'Custom Field Value',
	 *     'validation'               => '',
	 *     'location'                 => 'account',
	 *     'status'                   => 1,
	 *     'sort_order'               => 0
	 * ];
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->editCustomField($custom_field_id, $custom_field_data);
	 */
	public function editCustomField(int $custom_field_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "custom_field` SET `type` = '" . $this->db->escape((string)$data['type']) . "', `value` = '" . $this->db->escape((string)$data['value']) . "', `validation` = '" . $this->db->escape((string)$data['validation']) . "', `location` = '" . $this->db->escape((string)$data['location']) . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");

		$this->deleteDescriptions($custom_field_id);

		foreach ($data['custom_field_description'] as $language_id => $custom_field_description) {
			$this->addDescription($custom_field_id, $language_id, $custom_field_description);
		}

		$this->deleteCustomerGroups($custom_field_id);

		if (isset($data['custom_field_customer_group'])) {
			foreach ($data['custom_field_customer_group'] as $custom_field_customer_group) {
				if (isset($custom_field_customer_group['customer_group_id'])) {
					$this->addCustomerGroup($custom_field_id, $custom_field_customer_group);
				}
			}
		}

		$this->deleteValues($custom_field_id);

		if (isset($data['custom_field_value'])) {
			foreach ($data['custom_field_value'] as $custom_field_value) {
				$this->addValue($custom_field_id, $custom_field_value);
			}
		}
	}

	/**
	 * Edit Status
	 *
	 * Edit information status record in the database.
	 *
	 * @param int  $information_id primary key of the information record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/information');
	 *
	 * $this->model_catalog_information->editStatus($information_id, $status);
	 */
	public function editStatus(int $custom_field_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "custom_field` SET `status` = '" . (bool)$status . "' WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");
	}

	/**
	 * Delete Custom Field
	 *
	 * Delete custom field record in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->deleteCustomField($custom_field_id);
	 */
	public function deleteCustomField(int $custom_field_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field` WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");

		$this->deleteDescriptions($custom_field_id);
		$this->deleteCustomerGroups($custom_field_id);
		$this->deleteValues($custom_field_id);
	}

	/**
	 * Get Custom Field
	 *
	 * Get the record of the custom field record in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return array<string, mixed> custom field record that has custom field ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $custom_field_info = $this->model_customer_custom_field->getCustomField($custom_field_id);
	 */
	public function getCustomField(int $custom_field_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` `cf` LEFT JOIN `" . DB_PREFIX . "custom_field_description` `cfd` ON (`cf`.`custom_field_id` = `cfd`.`custom_field_id`) WHERE `cf`.`custom_field_id` = '" . (int)$custom_field_id . "' AND `cfd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Custom Fields
	 *
	 * Get the record of the custom field records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> custom field records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'cfd.name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);
	 */
	public function getCustomFields(array $data = []): array {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		if (empty($data['filter_customer_group_id'])) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "custom_field` `cf`";
		} else {
			$sql = "SELECT * FROM `" . DB_PREFIX . "custom_field_customer_group` `cfcg` LEFT JOIN `" . DB_PREFIX . "custom_field` `cf` ON (`cfcg`.`custom_field_id` = `cf`.`custom_field_id`)";
		}

		$sql .= " LEFT JOIN `" . DB_PREFIX . "custom_field_description` `cfd` ON (`cf`.`custom_field_id` = `cfd`.`custom_field_id`) WHERE `cfd`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`cfd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (isset($data['filter_status'])) {
			$sql .= " AND `cf`.`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if (isset($data['filter_location'])) {
			$sql .= " AND `cf`.`location` = '" . $this->db->escape((string)$data['filter_location']) . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND `cfcg`.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		$sort_data = [
			'name'       => 'cfd.name',
			'type'       => 'cf.type',
			'location'   => 'cf.location',
			'status'     => 'cf.status',
			'date_added' => 'cf.date_added'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `cfd`.`name`";
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
	 * Get Total Custom Fields
	 *
	 * Get the total number of custom field records in the database.
	 *
	 * @return int total number of custom field records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'cfd.name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $custom_field_total = $this->model_customer_custom_field->getTotalCustomFields($filter_data);
	 */
	public function getTotalCustomFields(array $data = []): int {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		if (empty($data['filter_customer_group_id'])) {
			$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "custom_field` `cf`";
		} else {
			$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "custom_field_customer_group` `cfcg` LEFT JOIN `" . DB_PREFIX . "custom_field` `cf` ON (`cfcg`.`custom_field_id` = `cf`.`custom_field_id`)";
		}

		$query = $this->db->query($sql . " LEFT JOIN `" . DB_PREFIX . "custom_field_description` `cfd` ON (`cf`.`custom_field_id` = `cfd`.`custom_field_id`) WHERE `cfd`.`language_id` = '" . (int)$language_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new custom field description record in the database.
	 *
	 * @param int                  $custom_field_id primary key of the custom field record
	 * @param int                  $language_id     primary key of the language record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $custom_field_data['custom_field_description'] = [
	 *     'name' => 'Custom Field Name'
	 * ];
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->addDescription($custom_field_id, $language_id, $custom_field_data);
	 */
	public function addDescription(int $custom_field_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field_description` SET `custom_field_id` = '" . (int)$custom_field_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete custom field description records in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->deleteDescriptions($custom_field_id);
	 */
	public function deleteDescriptions(int $custom_field_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_description` WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete custom field descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the custom field description records in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return array<int, array<string, string>> description records that have custom field ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $custom_field_description = $this->model_customer_custom_field->getDescriptions($custom_field_id);
	 */
	public function getDescriptions(int $custom_field_id): array {
		$custom_field_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_description` WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");

		foreach ($query->rows as $result) {
			$custom_field_data[$result['language_id']] = $result;
		}

		return $custom_field_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the custom field descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $results = $this->model_customer_custom_field->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Add Customer Group
	 *
	 * Create a new custom field customer group record in the database.
	 *
	 * @param int                  $custom_field_id primary key of the custom field record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $custom_field_data['custom_field_customer_group'] = [
	 *     'customer_group_id' => 1,
	 *     'required'          => 0
	 * ];
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->addCustomerGroup($custom_field_id, $custom_field_data);
	 */
	public function addCustomerGroup(int $custom_field_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field_customer_group` SET `custom_field_id` = '" . (int)$custom_field_id . "', `customer_group_id` = '" . (int)$data['customer_group_id'] . "', `required` = '" . (int)(isset($data['required']) ? 1 : 0) . "'");
	}

	/**
	 * Delete Customer Groups
	 *
	 * Delete custom field customer group records in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->deleteCustomerGroups($custom_field_id);
	 */
	public function deleteCustomerGroups(int $custom_field_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_customer_group` WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");
	}

	/**
	 * Get Customer Groups
	 *
	 * Get the record of the custom field customer group records in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return array<int, array<string, mixed>> customer group records that have custom field ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $custom_field_customer_groups = $this->model_customer_custom_field->getCustomerGroups($custom_field_id);
	 */
	public function getCustomerGroups(int $custom_field_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_customer_group` WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");

		return $query->rows;
	}

	/**
	 * Add Value
	 *
	 * Create a new custom field value record in the database.
	 *
	 * @param int                  $custom_field_id primary key of the custom field record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return int returns the primary key of the new custom field value record
	 *
	 * @example
	 *
	 * $custom_field_data['custom_field_value'] = [
	 *     'custom_field_value_description' => [],
	 *     'custom_field_value_id'          => 0,
	 *     'custom_field_id'                => 1,
	 *     'sort_order'                     => 0
	 * ];
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $value = $this->model_customer_custom_field->addValue($custom_field_id, $custom_field_data);
	 */
	public function addValue(int $custom_field_id, array $data): int {
		if ($data['custom_field_value_id']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field_value` SET `custom_field_value_id` = '" . (int)$data['custom_field_value_id'] . "', `custom_field_id` = '" . (int)$custom_field_id . "', `sort_order` = '" . (int)$data['sort_order'] . "'");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field_value` SET `custom_field_id` = '" . (int)$custom_field_id . "', `sort_order` = '" . (int)$data['sort_order'] . "'");
		}

		$custom_field_value_id = $this->db->getLastId();

		foreach ($data['custom_field_value_description'] as $language_id => $custom_field_value_description) {
			$this->addValueDescription($custom_field_value_id, $custom_field_id, $language_id, $custom_field_value_description);
		}

		return $custom_field_value_id;
	}

	/**
	 * Delete Values
	 *
	 * Delete custom field value records in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->deleteValues($custom_field_id);
	 */
	public function deleteValues(int $custom_field_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_value` WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");

		$this->deleteValueDescriptions($custom_field_id);
	}

	/**
	 * Get Value
	 *
	 * Get the record of the custom field value record in the database.
	 *
	 * @param int $custom_field_value_id primary key of the custom field value record
	 *
	 * @return array<string, mixed> value record that has custom field value ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $custom_field_value = $this->model_customer_custom_field->getValue($custom_field_value_id);
	 */
	public function getValue(int $custom_field_value_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_value` `cfv` LEFT JOIN `" . DB_PREFIX . "custom_field_value_description` `cfvd` ON (`cfv`.`custom_field_value_id` = `cfvd`.`custom_field_value_id`) WHERE `cfv`.`custom_field_value_id` = '" . (int)$custom_field_value_id . "' AND `cfvd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Values
	 *
	 * Get the record of the custom field value records in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return array<int, array<string, mixed>> value records that have custom field ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $custom_field_value = $this->model_customer_custom_field->getValues($custom_field_id);
	 */
	public function getValues(int $custom_field_id): array {
		$custom_field_value_data = [];

		$custom_field_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_value` `cfv` LEFT JOIN `" . DB_PREFIX . "custom_field_value_description` `cfvd` ON (`cfv`.`custom_field_value_id` = `cfvd`.`custom_field_value_id`) WHERE `cfv`.`custom_field_id` = '" . (int)$custom_field_id . "' AND `cfvd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `cfv`.`sort_order` ASC");

		foreach ($custom_field_value_query->rows as $custom_field_value) {
			$custom_field_value_data[$custom_field_value['custom_field_value_id']] = $custom_field_value;
		}

		return $custom_field_value_data;
	}

	/**
	 * Add Value Description
	 *
	 * Create a new custom field value description record in the database.
	 *
	 * @param int                  $custom_field_value_id          primary key of the custom field value record
	 * @param int                  $custom_field_id                primary key of the custom field record
	 * @param int                  $language_id                    primary key of the language record
	 * @param array<string, mixed> $custom_field_value_description array of data
	 *
	 * @return void
	 *
	 * @example
	 * 
	 * $custom_field_value_description_data[1] = [
	 *     'custom_field_value_id' => 1,
	 *     'custom_field_id'       => 1,
	 *     'name'                  => 'Custom Field Value Description Name'
	 * ];
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->addValueDescription($custom_field_value_id, $custom_field_id, $language_id, $custom_field_value_description);
	 */
	public function addValueDescription(int $custom_field_value_id, int $custom_field_id, int $language_id, array $custom_field_value_description): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field_value_description` SET `custom_field_value_id` = '" . (int)$custom_field_value_id . "', `language_id` = '" . (int)$language_id . "', `custom_field_id` = '" . (int)$custom_field_id . "', `name` = '" . $this->db->escape($custom_field_value_description['name']) . "'");
	}

	/**
	 * Delete Value Descriptions
	 *
	 * Delete custom field value description records in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->deleteValueDescriptions($custom_field_id);
	 */
	public function deleteValueDescriptions(int $custom_field_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_value_description` WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");
	}

	/**
	 * Delete Value Descriptions By Language ID
	 *
	 * Delete custom field value descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $this->model_customer_custom_field->deleteValueDescriptionsByLanguageId($language_id);
	 */
	public function deleteValueDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_value_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Value Descriptions
	 *
	 * Get the record of the custom field value description records in the database.
	 *
	 * @param int $custom_field_id primary key of the custom field record
	 *
	 * @return array<int, array<string, mixed>> value description records that have custom field ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $custom_field_values = $this->model_customer_custom_field->getValueDescriptions($custom_field_id);
	 */
	public function getValueDescriptions(int $custom_field_id): array {
		$custom_field_value_data = [];

		$custom_field_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_value` WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");

		foreach ($custom_field_value_query->rows as $custom_field_value) {
			$custom_field_value_description_data = [];

			$custom_field_value_description_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_value_description` WHERE `custom_field_value_id` = '" . (int)$custom_field_value['custom_field_value_id'] . "'");

			foreach ($custom_field_value_description_query->rows as $custom_field_value_description) {
				$custom_field_value_description_data[$custom_field_value_description['language_id']] = ['name' => $custom_field_value_description['name']];
			}

			$custom_field_value_data[] = ['custom_field_value_description' => $custom_field_value_description_data] + $custom_field_value;
		}

		return $custom_field_value_data;
	}

	/**
	 * Get Value Descriptions By Language ID
	 *
	 * Get the record of the custom field value description by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> value description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/custom_field');
	 *
	 * $results = $this->model_customer_custom_field->getValueDescriptionsByLanguageId($language_id);
	 */
	public function getValueDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_value_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
