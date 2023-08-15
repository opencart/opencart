<?php
namespace Opencart\Admin\Model\Customer;
/**
 * Class Customer Approval
 *
 * @package Opencart\Admin\Model\Customer
 */
class CustomerApproval extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getCustomerApprovals(array $data = []): array {
		$sql = "SELECT *, CONCAT(c.`firstname`, ' ', c.`lastname`) AS customer, cgd.`name` AS customer_group, ca.`type` FROM `" . DB_PREFIX . "customer_approval` ca LEFT JOIN `" . DB_PREFIX . "customer` c ON (ca.`customer_id` = c.`customer_id`) LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.`customer_group_id` = cgd.`customer_group_id`) WHERE cgd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '" . $this->db->escape('%' . (string)$data['filter_customer'] . '%') . "'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND c.`email` LIKE '" . $this->db->escape((string)$data['filter_email'] . '%') . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND c.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_type'])) {
			$sql .= " AND ca.`type` = '" . $this->db->escape((string)$data['filter_type']) . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(c.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(c.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		$sql .= " ORDER BY c.`date_added` DESC";

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
	 * @param int $customer_approval_id
	 *
	 * @return array
	 */
	public function getCustomerApproval(int $customer_approval_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_approval_id` = '" . (int)$customer_approval_id . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function getTotalCustomerApprovals(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_approval` ca LEFT JOIN `" . DB_PREFIX . "customer` c ON (ca.`customer_id` = c.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '" . $this->db->escape('%' . (string)$data['filter_customer'] . '%') . "'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.`email` LIKE '" . $this->db->escape((string)$data['filter_email'] . '%') . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_type'])) {
			$implode[] = "ca.`type` = '" . $this->db->escape((string)$data['filter_type']) . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(c.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(c.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * @param int $customer_id
	 *
	 * @return void
	 */
	public function approveCustomer(int $customer_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `status` = '1' WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "' AND `type` = 'customer'");
	}

	/**
	 * @param int $customer_id
	 *
	 * @return void
	 */
	public function denyCustomer(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "' AND `type` = 'customer'");
	}

	/**
	 * @param int $customer_id
	 *
	 * @return void
	 */
	public function approveAffiliate(int $customer_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_affiliate` SET `status` = '1' WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "' AND `type` = 'affiliate'");
	}

	/**
	 * @param int $customer_id
	 *
	 * @return void
	 */
	public function denyAffiliate(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "' AND `type` = 'affiliate'");
	}
}
