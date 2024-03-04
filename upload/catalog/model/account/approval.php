<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Customer
 *
 * @package Opencart\Catalog\Model\Account
 */
class Approval extends \Opencart\System\Engine\Model {
	/**
	 * Add Customer Approval
	 *
	 * @param int    $customer_id
	 * @param string $type
	 *
	 * @return void
	 */
	public function addApproval(int $customer_id, string $type): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET `customer_id` = '" . (int)$customer_id . "', `type` = '" . $this->db->escape($type) . "', `date_added` = NOW()");
	}

	/**
	 * Delete Customer Approvals
	 *
	 * @param int $customer_id
	 *
	 * @return void
	 */
	public function deleteApprovals(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}
}
