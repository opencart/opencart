<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class TaxRate
 *
 * @package Opencart\Admin\Model\Localisation
 */
class TaxRate extends \Opencart\System\Engine\Model {
	/**
	 * Add Tax Rate
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addTaxRate(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_rate` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `rate` = '" . (float)$data['rate'] . "', `type` = '" . $this->db->escape((string)$data['type']) . "', `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "'");

		$tax_rate_id = $this->db->getLastId();

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_rate_to_customer_group` SET `tax_rate_id` = '" . (int)$tax_rate_id . "', `customer_group_id` = '" . (int)$customer_group_id . "'");
			}
		}

		return $tax_rate_id;
	}

	/**
	 * Edit Tax Rate
	 *
	 * @param int                  $tax_rate_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editTaxRate(int $tax_rate_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "tax_rate` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `rate` = '" . (float)$data['rate'] . "', `type` = '" . $this->db->escape((string)$data['type']) . "', `geo_zone_id` = '" . (int)$data['geo_zone_id'] . "' WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rate_to_customer_group` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");

		if (isset($data['tax_rate_customer_group'])) {
			foreach ($data['tax_rate_customer_group'] as $customer_group_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "tax_rate_to_customer_group` SET `tax_rate_id` = '" . (int)$tax_rate_id . "', `customer_group_id` = '" . (int)$customer_group_id . "'");
			}
		}
	}

	/**
	 * Delete Tax Rate
	 *
	 * @param int $tax_rate_id
	 *
	 * @return void
	 */
	public function deleteTaxRate(int $tax_rate_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rate` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rate_to_customer_group` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");
	}

	/**
	 * Get Tax Rate
	 *
	 * @param int $tax_rate_id
	 *
	 * @return array<string, mixed>
	 */
	public function getTaxRate(int $tax_rate_id): array {
		$query = $this->db->query("SELECT `tr`.`tax_rate_id`, `tr`.`name` AS name, `tr`.`rate`, `tr`.`type`, `tr`.`geo_zone_id`, `gz`.`name` AS `geo_zone` FROM `" . DB_PREFIX . "tax_rate` `tr` LEFT JOIN `" . DB_PREFIX . "geo_zone` `gz` ON (`tr`.`geo_zone_id` = `gz`.`geo_zone_id`) WHERE `tr`.`tax_rate_id` = '" . (int)$tax_rate_id . "'");

		return $query->row;
	}

	/**
	 * Get Tax Rates
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getTaxRates(array $data = []): array {
		$sql = "SELECT `tr`.`tax_rate_id`, `tr`.`name` AS `name`, `tr`.`rate`, `tr`.`type`, `gz`.`name` AS `geo_zone` FROM `" . DB_PREFIX . "tax_rate` `tr` LEFT JOIN `" . DB_PREFIX . "geo_zone` `gz` ON (`tr`.`geo_zone_id` = `gz`.`geo_zone_id`)";

		$sort_data = [
			'tr.name',
			'tr.rate',
			'tr.type',
			'gz.name'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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

	/**
	 * Get Customer Groups
	 *
	 * @param int $tax_rate_id
	 *
	 * @return array<int, int>
	 */
	public function getCustomerGroups(int $tax_rate_id): array {
		$tax_customer_group_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "tax_rate_to_customer_group` WHERE `tax_rate_id` = '" . (int)$tax_rate_id . "'");

		foreach ($query->rows as $result) {
			$tax_customer_group_data[] = $result['customer_group_id'];
		}

		return $tax_customer_group_data;
	}

	/**
	 * Get Total Tax Rates
	 *
	 * @return int
	 */
	public function getTotalTaxRates(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "tax_rate`");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Tax Rates By Geo Zone ID
	 *
	 * @param int $geo_zone_id
	 *
	 * @return int
	 */
	public function getTotalTaxRatesByGeoZoneId(int $geo_zone_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "tax_rate` WHERE `geo_zone_id` = '" . (int)$geo_zone_id . "'");

		return (int)$query->row['total'];
	}
}
