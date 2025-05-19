<?php
namespace Opencart\Admin\Model\Customer;
/**
 * Class Customer Approval
 *
 * Can be loaded using $this->load->model('customer/customer_approval');
 *
 * @package Opencart\Admin\Model\Customer
 */
class CustomerApproval extends \Opencart\System\Engine\Model {
	/**
	 * Delete Approvals By Customer ID
	 *
	 * Delete customer approvals by customer records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer_approval');
	 *
	 * $this->model_customer_customer_approval->deleteApprovalsByCustomerId($customer_id);
	 */
	public function deleteApprovalsByCustomerId(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Get Customer Approvals
	 *
	 * Get the record of the customer approval records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> customer approval records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_customer'          => 'John Doe',
	 *     'filter_email'             => 'demo@opencart.com',
	 *     'filter_customer_group_id' => 1,
	 *     'filter_type'              => 'customer',
	 *     'filter_date_from'         => '2021-01-01',
	 *     'filter_date_to'           => '2021-01-31',
	 *     'start'                    => 0,
	 *     'limit'                    => 10
	 * ];
	 *
	 * $this->load->model('customer/customer_approval');
	 *
	 * $results = $this->model_customer_customer_approval->getCustomerApprovals($filter_data);
	 */
	public function getCustomerApprovals(array $data = []): array {
		$sql = "SELECT *, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `cgd`.`name` AS `customer_group`, `ca`.`type` FROM `" . DB_PREFIX . "customer_approval` `ca` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ca`.`customer_id` = `c`.`customer_id`) LEFT JOIN `" . DB_PREFIX . "customer_group_description` `cgd` ON (`c`.`customer_group_id` = `cgd`.`customer_group_id`) WHERE `cgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_customer'])) {
			$sql .= " AND LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_customer']) . '%') . "'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND LCASE(`c`.`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email']) . '%') . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND `c`.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_type'])) {
			$sql .= " AND `ca`.`type` = '" . $this->db->escape((string)$data['filter_type']) . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(`c`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(`c`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		$sql .= " ORDER BY `c`.`date_added` DESC";

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
	 * Get Customer Approval
	 *
	 * Get the record of the customer approval record in the database.
	 *
	 * @param int $customer_approval_id primary key of the customer approval record
	 *
	 * @return array<string, mixed> customer approval record that has customer approval ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer_approval');
	 *
	 * $customer_approval_info = $this->model_customer_customer_approval->getCustomerApproval($customer_approval_id);
	 */
	public function getCustomerApproval(int $customer_approval_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_approval_id` = '" . (int)$customer_approval_id . "'");

		return $query->row;
	}

	/**
	 * Get Total Customer Approvals
	 *
	 * Get the total number of customer approval records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of customer approval records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_customer'          => 'John Doe',
	 *     'filter_email'             => 'demo@opencart.com',
	 *     'filter_customer_group_id' => 1,
	 *     'filter_type'              => 'customer',
	 *     'filter_date_from'         => '2021-01-01',
	 *     'filter_date_to'           => '2021-01-31',
	 *     'start'                    => 0,
	 *     'limit'                    => 10
	 * ];
	 *
	 * $this->load->model('customer/customer_approval');
	 *
	 * $customer_approval_total = $this->model_customer_customer_approval->getTotalCustomerApprovals($filter_data);
	 */
	public function getTotalCustomerApprovals(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_approval` `ca` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ca`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_customer'])) {
			$implode[] = "LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_customer']) . '%') . "'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(`c`.`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email']) . '%') . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "`c`.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_type'])) {
			$implode[] = "`ca`.`type` = '" . $this->db->escape((string)$data['filter_type']) . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`c`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`c`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Approve Customer
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer_approval');
	 *
	 * $this->model_customer_customer_approval->approveCustomer($customer_id);
	 */
	public function approveCustomer(int $customer_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `status` = '1' WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "' AND `type` = 'customer'");
	}

	/**
	 * Deny Customer
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer_approval');
	 *
	 * $this->model_customer_customer_approval->denyCustomer($customer_id);
	 */
	public function denyCustomer(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "' AND `type` = 'customer'");
	}

	/**
	 * Approve Affiliate
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer_approval');
	 *
	 * $this->model_customer_customer_approval->approveAffiliate($customer_id);
	 */
	public function approveAffiliate(int $customer_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_affiliate` SET `status` = '1' WHERE `customer_id` = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "' AND `type` = 'affiliate'");
	}

	/**
	 * Deny Affiliate
	 *
	 * Delete customer approval record in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer_approval');
	 *
	 * $this->model_customer_customer_approval->denyAffiliate($customer_id);
	 */
	public function denyAffiliate(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "' AND `type` = 'affiliate'");
	}
}
