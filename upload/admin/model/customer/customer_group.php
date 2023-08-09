<?php
namespace Opencart\Admin\Model\Customer;
/**
 * Class Customer Group
 *
 * @package Opencart\Admin\Model\Customer
 */
class CustomerGroup extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addCustomerGroup(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group` SET `approval` = '" . (isset($data['approval']) ? (bool)$data['approval'] : 0) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$customer_group_id = $this->db->getLastId();

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "'");
		}

		return $customer_group_id;
	}

	/**
	 * @param int   $customer_group_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editCustomerGroup(int $customer_group_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_group` SET `approval` = '" . (isset($data['approval']) ? (bool)$data['approval'] : 0) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_group_description` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");

		foreach ($data['customer_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "'");
		}
	}

	/**
	 * @param int $customer_group_id
	 *
	 * @return void
	 */
	public function deleteCustomerGroup(int $customer_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_group` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_group_description` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_discount` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_special` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_reward` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "tax_rate_to_customer_group` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");
	}

	/**
	 * @param int $customer_group_id
	 *
	 * @return array
	 */
	public function getCustomerGroup(int $customer_group_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "customer_group` cg LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (cg.`customer_group_id` = cgd.`customer_group_id`) WHERE cg.`customer_group_id` = '" . (int)$customer_group_id . "' AND cgd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getCustomerGroups(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "customer_group` cg LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (cg.`customer_group_id` = cgd.`customer_group_id`) WHERE cgd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = [
			'cgd.name',
			'cg.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cgd.`name`";
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
	 * @param int $customer_group_id
	 *
	 * @return array
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
	 * @return int
	 */
	public function getTotalCustomerGroups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_group`");

		return (int)$query->row['total'];
	}
}
