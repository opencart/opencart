<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Affiliate
 *
 * Can be called using $this->load->model('account/affiliate');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Affiliate extends \Opencart\System\Engine\Model {
	/**
	 * Add Affiliate
	 *
	 * Create a new customer affiliate record in the database.
	 *
	 * @param int                  $customer_id primary key of the customer record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $affiliate_data = [
	 *     'company'            => '',
	 *     'website'            => '',
	 *     'tracking'           => '',
	 *     'commission'         => 0.00,
	 *     'tax'                => '',
	 *     'payment_method'     => '',
	 *     'cheque'             => '',
	 *     'paypal'             => '',
	 *     'bank_name'          => '',
	 *     'bank_branch_number' => '',
	 *     'custom_field'       => [],
	 *     'status'             => 0
	 * ];
	 *
	 * $this->load->model('account/affiliate');
	 *
	 * $this->model_account_affiliate->addAffiliate($customer_id, $affiliate_data);
	 */
	public function addAffiliate(int $customer_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate` SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape($data['company']) . "', `website` = '" . $this->db->escape($data['website']) . "', `tracking` = '" . $this->db->escape(oc_token(10)) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape($data['tax']) . "', `payment_method` = '" . $this->db->escape($data['payment_method']) . "', `cheque` = '" . $this->db->escape($data['cheque']) . "', `paypal` = '" . $this->db->escape($data['paypal']) . "', `bank_name` = '" . $this->db->escape($data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape($data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape($data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($data['bank_account_number']) . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', `status` = '" . (int)!$this->config->get('config_affiliate_approval') . "', `date_added` = NOW()");

		// Approval
		if ($this->config->get('config_affiliate_approval')) {
			$this->load->model('account/approval');

			$this->model_account_approval->addApproval($customer_id, 'affiliate');
		}
	}

	/**
	 * Edit Affiliate
	 *
	 * Edit customer affiliate record in the database.
	 *
	 * @param int                  $customer_id primary key of the customer record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $affiliate_data = [
	 *     'company'            => '',
	 *     'website'            => '',
	 *     'tracking'           => '',
	 *     'commission'         => 0.00,
	 *     'tax'                => '',
	 *     'payment_method'     => '',
	 *     'cheque'             => '',
	 *     'paypal'             => '',
	 *     'bank_name'          => '',
	 *     'bank_branch_number' => '',
	 *     'custom_field'       => [],
	 *     'status'             => 1
	 * ];
	 *
	 * $this->load->model('account/affiliate');
	 *
	 * $this->model_account_affiliate->editAffiliate($customer_id, $affiliate_data);
	 */
	public function editAffiliate(int $customer_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_affiliate` SET `company` = '" . $this->db->escape($data['company']) . "', `website` = '" . $this->db->escape($data['website']) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape($data['tax']) . "', `payment_method` = '" . $this->db->escape($data['payment_method']) . "', `cheque` = '" . $this->db->escape($data['cheque']) . "', `paypal` = '" . $this->db->escape($data['paypal']) . "', `bank_name` = '" . $this->db->escape($data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape($data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape($data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($data['bank_account_number']) . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete Affiliate
	 *
	 * Delete customer affiliate record in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/affiliate');
	 *
	 * $this->model_account_affiliate->deleteAffiliate($customer_id);
	 */
	public function deleteAffiliate(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

		$this->deleteReports($customer_id);
	}

	/**
	 * Get Affiliate
	 *
	 * Get the record of the customer affiliate record in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return array<string, mixed> affiliate record that has customer ID
	 *
	 * @example
	 *
	 * $this->load->model('account/affiliate');
	 *
	 * $affiliate_info = $this->model_account_affiliate->getAffiliate($customer_id);
	 */
	public function getAffiliate(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

		if ($query->num_rows) {
			return ['custom_field' => $query->row['custom_field'] ? json_decode($query->row['custom_field'], true) : []] + $query->row;
		} else {
			return [];
		}
	}

	/**
	 * Get Affiliate By Tracking
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('account/affiliate');
	 *
	 * $affiliate_info = $this->model_account_affiliate->getAffiliateByTracking($code);
	 */
	public function getAffiliateByTracking(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `tracking` = '" . $this->db->escape($code) . "'");

		if ($query->num_rows) {
			return ['custom_field' => $query->row['custom_field'] ? json_decode($query->row['custom_field'], true) : []] + $query->row;
		} else {
			return [];
		}
	}

	/**
	 * Add Report
	 *
	 * Create a new customer affiliate report record in the database.
	 *
	 * @param int    $customer_id primary key of the customer record
	 * @param string $ip
	 * @param string $country
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/affiliate');
	 *
	 * $this->model_account_affiliate->addReport($customer_id, $ip, $country);
	 */
	public function addReport(int $customer_id, string $ip, string $country = ''): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate_report` SET `customer_id` = '" . (int)$customer_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `ip` = '" . $this->db->escape($ip) . "', `country` = '" . $this->db->escape($country) . "', `date_added` = NOW()");
	}

	/**
	 * Delete Customer Affiliate Reports
	 *
	 * Delete customer affiliate report records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/affiliate');
	 *
	 * $this->model_account_affiliate->deleteReports($customer_id);
	 */
	public function deleteReports(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_affiliate_report` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}
}
