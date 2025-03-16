<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Custom Field
 *
 * Can be called using $this->load->model('account/custom_field');
 *
 * @package Opencart\Catalog\Model\Account
 */
class CustomField extends \Opencart\System\Engine\Model {
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
	 * $this->load->model('account/custom_field');
	 *
	 * $custom_field = $this->model_account_custom_field->getCustomField($custom_field_id);
	 */
	public function getCustomField(int $custom_field_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` `cf` LEFT JOIN `" . DB_PREFIX . "custom_field_description` `cfd` ON (`cf`.`custom_field_id` = `cfd`.`custom_field_id`) WHERE `cf`.`status` = '1' AND `cf`.`custom_field_id` = '" . (int)$custom_field_id . "' AND `cfd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Custom Fields
	 *
	 * Get the record of the custom field records in the database.
	 *
	 * @param int $customer_group_id primary key of the customer group record
	 *
	 * @return array<int, array<string, mixed>> custom field records that have customer group ID
	 *
	 * @example
	 *
	 * $this->load->model('account/custom_field');
	 *
	 * $results = $this->model_account_custom_field->getCustomFields($customer_group_id);
	 */
	public function getCustomFields(int $customer_group_id = 0): array {
		$custom_field_data = [];

		if (!$customer_group_id) {
			$custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN `" . DB_PREFIX . "custom_field_description` `cfd` ON (`cf`.`custom_field_id` = `cfd`.`custom_field_id`) WHERE `cf`.`status` = '1' AND `cfd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `cf`.`sort_order` ASC");
		} else {
			$custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_customer_group` `cfcg` LEFT JOIN `" . DB_PREFIX . "custom_field` `cf` ON (`cfcg`.`custom_field_id` = `cf`.`custom_field_id`) LEFT JOIN `" . DB_PREFIX . "custom_field_description` `cfd` ON (`cf`.`custom_field_id` = `cfd`.`custom_field_id`) WHERE `cf`.`status` = '1' AND `cfd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `cfcg`.`customer_group_id` = '" . (int)$customer_group_id . "' ORDER BY `cf`.`sort_order` ASC");
		}

		foreach ($custom_field_query->rows as $custom_field) {
			$custom_field_value_data = [];

			if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio' || $custom_field['type'] == 'checkbox') {
				$custom_field_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_value` `cfv` LEFT JOIN `" . DB_PREFIX . "custom_field_value_description` `cfvd` ON (`cfv`.`custom_field_value_id` = `cfvd`.`custom_field_value_id`) WHERE `cfv`.`custom_field_id` = '" . (int)$custom_field['custom_field_id'] . "' AND `cfvd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `cfv`.`sort_order` ASC");

				$custom_field_value_data = $custom_field_value_query->rows;
			}

			$custom_field_data[] = [
				'custom_field_value' => $custom_field_value_data,
				'required'           => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true
			] + $custom_field;
		}

		return $custom_field_data;
	}
}
