<?php
namespace Opencart\Admin\Model\Customer;
/**
 * Class Customer Group
 *
 * @package Opencart\Admin\Model\Customer
 */
class CustomerGroup extends \Opencart\System\Engine\Model {
	/**
	 * Add Customer Group
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addCustomerGroup(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group` SET `approval` = '" . (isset($data['approval']) ? (bool)$data['approval'] : 0) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$customer_group_id = $this->db->getLastId();

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->addDescription($customer_group_id, $language_id, $value);
		}

		return $customer_group_id;
	}

	/**
	 * Edit Customer Group
	 *
	 * @param int                  $customer_group_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editCustomerGroup(int $customer_group_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_group` SET `approval` = '" . (isset($data['approval']) ? (bool)$data['approval'] : 0) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");

		$this->deleteDescriptions($customer_group_id);

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->addDescription($customer_group_id, $language_id, $value);
		}
	}

	/**
	 * Delete Customer Group
	 *
	 * @param int $customer_group_id
	 *
	 * @return void
	 */
	public function deleteCustomerGroup(int $customer_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_group` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");

		$this->deleteDescriptions($customer_group_id);

		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteDiscountsByCustomerGroupId($customer_group_id);
		$this->model_catalog_product->deleteSpecialsByCustomerGroupId($customer_group_id);
		$this->model_catalog_product->deleteRewardsByCustomerGroupId($customer_group_id);

		$this->load->model('localisation/tax_rate');

		$this->model_localisation_tax_rate->deleteCustomerGroupsByCustomerGroupId($customer_group_id);
	}

	/**
	 * Get Customer Group
	 *
	 * @param int $customer_group_id
	 *
	 * @return array<string, mixed>
	 */
	public function getCustomerGroup(int $customer_group_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "customer_group` `cg` LEFT JOIN `" . DB_PREFIX . "customer_group_description` `cgd` ON (`cg`.`customer_group_id` = `cgd`.`customer_group_id`) WHERE `cg`.`customer_group_id` = '" . (int)$customer_group_id . "' AND `cgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Customer Groups
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getCustomerGroups(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "customer_group` `cg` LEFT JOIN `" . DB_PREFIX . "customer_group_description` `cgd` ON (`cg`.`customer_group_id` = `cgd`.`customer_group_id`) WHERE `cgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = [
			'cgd.name',
			'cg.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `cgd`.`name`";
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
	 * Add Description
	 *
	 * @param int                  $customer_group_id
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addDescription(int $customer_group_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "', `description` = '" . $this->db->escape($data['description']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * @param int $customer_group_id
	 *
	 * @return void
	 */
	public function deleteDescriptions(int $customer_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_group_description` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_group_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $customer_group_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptions(int $customer_group_id): array {
		$customer_group_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group_description` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");

		foreach ($query->rows as $result) {
			$customer_group_data[$result['language_id']] = [
				'name'        => $result['name'],
				'description' => $result['description']
			];
		}

		return $customer_group_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Customer Groups
	 *
	 * @return int
	 */
	public function getTotalCustomerGroups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_group`");

		return (int)$query->row['total'];
	}
}
