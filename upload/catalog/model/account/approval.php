<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Customer
 *
 * Can be called using $this->load->model('account/approval');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Approval extends \Opencart\System\Engine\Model {
	/**
	 * Add Customer Approval
	 *
	 * Create a new customer approval record in the database.
	 *
	 * @param int    $customer_id primary key of the customer record
	 * @param string $type
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/approval');
	 *
	 * $this->model_account_approval->addApproval($customer_id, $type);
	 */
	public function addApproval(int $customer_id, string $type): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET `customer_id` = '" . (int)$customer_id . "', `type` = '" . $this->db->escape($type) . "', `date_added` = NOW()");
	}

	/**
	 * Delete Customer Approvals
	 *
	 * Delete customer approval records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/approval');
	 *
	 * $this->model_account_approval->deleteApprovals($customer_id);
	 */
	public function deleteApprovals(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}
}
