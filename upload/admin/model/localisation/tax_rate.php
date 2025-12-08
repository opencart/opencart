<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Tax Rate
 *
 * Can be loaded using $this->load->model('localisation/tax_rate');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class TaxRate extends \Opencart\System\Engine\Model {
	/**
	 * Add Tax Rate
	 *
	 * Create a new tax rate record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new tax rate record
	 *
	 * @example
	 *
	 * $tax_rate_data = [
	 *     'name'        => 'Tax Rate Name',
	 *     'rate'        => 0.0000,
	 *     'type'        => 'F',
	 *     'geo_zone_id' => 1
	 * ];
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $tax_rate_id = $this->model_localisation_tax_rate->addTaxRate($tax_rate_data);
	 */
	public function addTaxRate(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_rate` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `rate` = '" . (float)$data['rate'] . "', `type` = '" . $this->db->escape((string)$data['type']) . "', `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "'");

		$tax_rate_id = $this->db->getLastId();

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->addCustomerGroup($tax_rate_id, $customer_group_id);
			}
		}

		return $tax_rate_id;
	}

	/**
	 * Edit Tax Rate
	 *
	 * Edit tax rate record in the database.
	 *
	 * @param int                  $tax_rate_id primary key of the tax rate record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $tax_rate_data = [
	 *     'name'        => 'Tax Rate Name',
	 *     'rate'        => 0.0000,
	 *     'type'        => 'F',
	 *     'geo_zone_id' => 1
	 * ];
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $this->model_localisation_tax_rate->editTaxRate($tax_rate_id, $tax_rate_data);
	 */
	public function editTaxRate(int $tax_rate_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "tax_rate` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `rate` = '" . (float)$data['rate'] . "', `type` = '" . $this->db->escape((string)$data['type']) . "', `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "' WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");

		$this->deleteCustomerGroups($tax_rate_id);

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->addCustomerGroup($tax_rate_id, $customer_group_id);
			}
		}
	}

	/**
	 * Delete Tax Rate
	 *
	 * Delete tax rate record in the database.
	 *
	 * @param int $tax_rate_id primary key of the tax rate record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $this->model_localisation_tax_rate->deleteTaxRate($tax_rate_id);
	 */
	public function deleteTaxRate(int $tax_rate_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rate` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");

		$this->deleteCustomerGroups($tax_rate_id);
	}

	/**
	 * Get Tax Rate
	 *
	 * Get the record of the tax rate record in the database.
	 *
	 * @param int $tax_rate_id primary key of the tax rate record
	 *
	 * @return array<string, mixed> tax rate record that has tax rate ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($tax_rate_id);
	 */
	public function getTaxRate(int $tax_rate_id): array {
		$query = $this->db->query("SELECT `tr`.`tax_rate_id`, `tr`.`name` AS `name`, `tr`.`rate`, `tr`.`type`, `tr`.`geo_zone_id`, `gz`.`name` AS `geo_zone` FROM `" . DB_PREFIX . "tax_rate` `tr` LEFT JOIN `" . DB_PREFIX . "geo_zone` `gz` ON (`tr`.`geo_zone_id` = `gz`.`geo_zone_id`) WHERE `tr`.`tax_rate_id` = '" . (int)$tax_rate_id . "'");

		return $query->row;
	}

	/**
	 * Get Tax Rates
	 *
	 * Get the record of the tax rate records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> tax rate records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'tr.name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $results = $this->model_localisation_tax_rate->getTaxRates($filter_data);
	 */
	public function getTaxRates(array $data = []): array {
		$sql = "SELECT `tr`.`tax_rate_id`, `tr`.`name` AS `name`, `tr`.`rate`, `tr`.`type`, `gz`.`name` AS `geo_zone` FROM `" . DB_PREFIX . "tax_rate` `tr` LEFT JOIN `" . DB_PREFIX . "geo_zone` `gz` ON (`tr`.`geo_zone_id` = `gz`.`geo_zone_id`)";

		$sort_data = [
			'name'     => 'tr.name',
			'rate'     => 'tr.rate',
			'type'     => 'tr.type',
			'geo_zone' => 'gz.name'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `tr`.`name`";
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

	/*
	 * Get Tax Rates By Geo Zone ID
	 */
	public function getTaxRatesByGeoZoneId(int $geo_zone_id): array {
		$query = $this->db->query("SELECT `tr1`.`tax_rule_id`, `tr1`.`tax_rate_id`, `tr1`.`tax_class_id`, `tr2`.`name`, `tr2`.`rate`, `tr2`.`type`, `tr1`.`priority`, `gz`.`name` AS `geo_zone`  FROM `" . DB_PREFIX . "tax_rule` `tr1` LEFT JOIN `" . DB_PREFIX . "tax_rate` `tr2` ON (`tr1`.`tax_rate_id` = `tr2`.`tax_rate_id`) LEFT JOIN `" . DB_PREFIX . "geo_zone` `gz` ON (`tr2`.`geo_zone_id` = `gz`.`geo_zone_id`) WHERE `tr2`.`geo_zone_id` = '" . (int)$geo_zone_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Tax Rates
	 *
	 * Get the total number of tax rate records in the database.
	 *
	 * @return int total number of tax rate records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRates();
	 */
	public function getTotalTaxRates(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "tax_rate`");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Tax Rates By Geo Zone ID
	 *
	 * Get the total number of tax rates by zone records in the database.
	 *
	 * @param int $geo_zone_id primary key of the geo zone record
	 *
	 * @return int total number of tax rate records that have geo zone ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRatesByGeoZoneId($geo_zone_id);
	 */
	public function getTotalTaxRatesByGeoZoneId(int $geo_zone_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "tax_rate` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Customer Group
	 *
	 * Create a new tax rate to customer group record in the database.
	 *
	 * @param int $tax_rate_id       primary key of the tax rate record
	 * @param int $customer_group_id primary key of the customer group record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $this->model_localisation_tax_rate->addCustomerGroup($tax_rate_id, $customer_group_id);
	 */
	public function addCustomerGroup(int $tax_rate_id, int $customer_group_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_rate_to_customer_group` SET `tax_rate_id` = '" . (int)$tax_rate_id . "', `customer_group_id` = '" . (int)$customer_group_id . "'");
	}

	/**
	 * Delete Customer Groups
	 *
	 * Delete tax rate to customer group records in the database.
	 *
	 * @param int $tax_rate_id primary key of the tax rate record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $this->model_localisation_tax_rate->deleteCustomerGroups($tax_rate_id);
	 */
	public function deleteCustomerGroups(int $tax_rate_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rate_to_customer_group` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");
	}

	/**
	 * Delete Customer Groups By Customer Group ID
	 *
	 * Delete tax rate to customer groups by customer group records in the database.
	 *
	 * @param int $customer_group_id primary key of the customer group record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $this->model_localisation_tax_rate->deleteCustomerGroupsByCustomerGroupId($customer_group_id);
	 */
	public function deleteCustomerGroupsByCustomerGroupId(int $customer_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rate_to_customer_group` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");
	}

	/**
	 * Get Customer Groups
	 *
	 * Get the record of the tax rate to customer group records in the database.
	 *
	 * @param int $tax_rate_id primary key of the tax rate record
	 *
	 * @return array<int, int> customer group records that have tax rate ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/tax_rate');
	 *
	 * $tax_rate_customer_group = $this->model_localisation_tax_rate->getCustomerGroups($tax_rate_id);
	 */
	public function getCustomerGroups(int $tax_rate_id): array {
		$tax_customer_group_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tax_rate_to_customer_group` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");

		foreach ($query->rows as $result) {
			$tax_customer_group_data[] = $result['customer_group_id'];
		}

		return $tax_customer_group_data;
	}
}
