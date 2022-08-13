<?php
namespace Opencart\Catalog\Model\Account;
use \Opencart\System\Helper as Helper;
class Affiliate extends \Opencart\System\Engine\Model {
	public function addAffiliate(int $customer_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate` SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape((string)$data['company']) . "', `website` = '" . $this->db->escape((string)$data['website']) . "', `tracking` = '" . $this->db->escape(Helper\General\token(10)) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape((string)$data['tax']) . "', `payment` = '" . $this->db->escape((string)$data['payment']) . "', `cheque` = '" . $this->db->escape((string)$data['cheque']) . "', `paypal` = '" . $this->db->escape((string)$data['paypal']) . "', `bank_name` = '" . $this->db->escape((string)$data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape((string)$data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape((string)$data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape((string)$data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape((string)$data['bank_account_number']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', `status` = '" . (int)!$this->config->get('config_affiliate_approval') . "', `date_added` = NOW()");

		if ($this->config->get('config_affiliate_approval')) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET `customer_id` = '" . (int)$customer_id . "', `type` = 'affiliate', `date_added` = NOW()");
		}
	}

	public function editAffiliate(int $customer_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_affiliate` SET `company` = '" . $this->db->escape((string)$data['company']) . "', `website` = '" . $this->db->escape((string)$data['website']) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape((string)$data['tax']) . "', `payment` = '" . $this->db->escape((string)$data['payment']) . "', `cheque` = '" . $this->db->escape((string)$data['cheque']) . "', `paypal` = '" . $this->db->escape((string)$data['paypal']) . "', `bank_name` = '" . $this->db->escape((string)$data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape((string)$data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape((string)$data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape((string)$data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape((string)$data['bank_account_number']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	public function getAffiliate(int $customer_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getAffiliateByTracking(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `tracking` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function addReport(int $customer_id, string $ip, string $country = ''): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate_report` SET `customer_id` = '" . (int)$customer_id . "', `store_id` = '" . (int)$this->config->get('config_store_id') . "', `ip` = '" . $this->db->escape($ip) . "', `country` = '" . $this->db->escape($country) . "', `date_added` = NOW()");
	}
}
