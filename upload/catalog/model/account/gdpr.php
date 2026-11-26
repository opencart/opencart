<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Gdpr
 *
 * Can be called using $this->load->model('account/gdpr');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Gdpr extends \Opencart\System\Engine\Model {
	/**
	 * Add Gdpr
	 *
	 * Create a new gdpr record in the database.
	 *
	 * @param string $code
	 * @param string $email
	 * @param string $action
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $this->model_account_gdpr->addGdpr($code, $email, $action);
	 */
	public function addGdpr(string $code, string $email, string $action): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "gdpr` SET `store_id` = '" . $this->db->escape($this->config->get('config_store_id')) . "', `language_id` = '" . $this->db->escape($this->config->get('config_language_id')) . "', `code` = '" . $this->db->escape($code) . "', `email` = '" . $this->db->escape($email) . "', `action` = '" . $this->db->escape($action) . "', `date_added` = NOW()");
	}

	/**
	 * Edit Status
	 *
	 * Edit gdpr status record in the database.
	 *
	 * @param int $gdpr_id primary key of the gdpr record
	 * @param int $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $this->model_account_gdpr->editStatus($gdpr_id, $status);
	 */
	public function editStatus(int $gdpr_id, int $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "gdpr` SET `status` = '" . (int)$status . "' WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");
	}

	/**
	 * Get Gdpr
	 *
	 * Get the record of the zone record in the database.
	 *
	 * @param int $gdpr_id primary key of the gdpr record
	 *
	 * @return array<string, mixed> gdpr record that has gdpr ID
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $gdpr_info = $this->model_account_gdpr->getGdpr($gdpr_id);
	 */
	public function getGdpr(int $gdpr_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");

		return $query->row;
	}

	/**
	 * Get Gdpr By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $gdpr_info = $this->model_account_gdpr->getGdprByCode($code);
	 */
	public function getGdprByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	/**
	 * Get Gdpr(s) By Email
	 *
	 * @param string $email
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $results = $this->model_account_customer->getGdprsByEmail($email);
	 */
	public function getGdprsByEmail(string $email): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `email` = '" . $this->db->escape($email) . "'");

		return $query->rows;
	}

	/**
	 * Get Expires
	 *
	 * @return array<int, array<string, mixed>> expire records
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $results = $this->model_account_customer->getExpires();
	 */
	public function getExpires(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `status` = '2' AND DATE(`date_added`) <= DATE('" . $this->db->escape(date('Y-m-d', strtotime('-' . (int)$this->config->get('config_gdpr_limit') . ' days'))) . "') ORDER BY `date_added` DESC");

		return $query->rows;
	}

	/**
	 * Get Removals
	 *
	 * Approved account removal requests that are still processing.
	 *
	 * @return array<int, array<string, mixed>> gdpr records
	 *
	 * @example
	 *
	 * $this->load->model('account/gdpr');
	 *
	 * $results = $this->model_account_gdpr->getRemovals();
	 */
	public function getRemovals(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `action` = 'remove' AND `status` = '2' ORDER BY `date_added` ASC");

		return $query->rows;
	}

	/**
	 * Get Total Retained Records
	 *
	 * Counts the orders and returns of a data subject that are still inside the
	 * GDPR limit (config_gdpr_limit days) and so may still be needed for fraud
	 * checks, chargebacks, refunds or tax. Missing orders (order status 0) never count.
	 *
	 * @param string $email       e-mail address of the GDPR request
	 * @param int    $customer_id customer ID, 0 if the account no longer exists
	 *
	 * @return int total number of retained order and return records
	 *
	 * @example
	 *
	 * $total = $this->model_account_gdpr->getTotalRetainedRecords($email, $customer_id);
	 */
	public function getTotalRetainedRecords(string $email, int $customer_id = 0): int {
		$match = $this->getSubjectMatch($email, $customer_id);
		$cutoff = $this->getRetentionCutoff();

		$order_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order` WHERE " . $match . " AND `order_status_id` > '0' AND `date_added` >= '" . $this->db->escape($cutoff) . "'");
		$return_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return` WHERE " . $match . " AND `date_added` >= '" . $this->db->escape($cutoff) . "'");

		return (int)$order_query->row['total'] + (int)$return_query->row['total'];
	}

	/**
	 * Erase Customer Data
	 *
	 * Erases the personal data of a data subject that the customer account
	 * deletion does not cover, anonymises order and return records that are no
	 * longer needed and minimises the ones that are kept until the GDPR limit.
	 *
	 * @param string $email       e-mail address of the GDPR request
	 * @param int    $customer_id customer ID of the deleted account, 0 if none
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->model_account_gdpr->eraseCustomerData($email, $customer_id);
	 */
	public function eraseCustomerData(string $email, int $customer_id = 0): void {
		if ($customer_id) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "cart` WHERE `customer_id` = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_online` WHERE `customer_id` = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_search` WHERE `customer_id` = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_token` WHERE `customer_id` = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_affiliate_report` WHERE `customer_id` = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "review` WHERE `customer_id` = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "article_comment` WHERE `customer_id` = '" . (int)$customer_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "article_rating` WHERE `customer_id` = '" . (int)$customer_id . "'");
			$this->db->query("UPDATE `" . DB_PREFIX . "coupon_history` SET `customer_id` = '0' WHERE `customer_id` = '" . (int)$customer_id . "'");

			// Stop subscriptions from renewing once the account is gone
			$canceled_status_id = (int)$this->config->get('config_subscription_canceled_status_id');
			$expired_status_id = (int)$this->config->get('config_subscription_expired_status_id');

			if ($canceled_status_id) {
				$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . $canceled_status_id . "', `date_modified` = NOW() WHERE `customer_id` = '" . (int)$customer_id . "' AND `subscription_status_id` NOT IN ('" . $canceled_status_id . "', '" . $expired_status_id . "')");
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `customer_id` = '0', `payment_address_id` = '0', `shipping_address_id` = '0' WHERE `customer_id` = '" . (int)$customer_id . "'");
		}

		// Failed login attempts are stored by e-mail
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");

		$this->anonymiseRecords($email, $customer_id);
	}

	/**
	 * Anonymise Records
	 *
	 * Orders and returns outside the GDPR limit (and missing orders) are
	 * anonymised. Orders and returns inside the limit are kept for fraud checks,
	 * chargebacks, refunds and tax, cut down to what is needed for that.
	 *
	 * @param string $email       e-mail address of the GDPR request
	 * @param int    $customer_id customer ID of the deleted account, 0 if none
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->model_account_gdpr->anonymiseRecords($email, $customer_id);
	 */
	public function anonymiseRecords(string $email, int $customer_id = 0): void {
		$match = $this->getSubjectMatch($email, $customer_id);
		$cutoff = $this->db->escape($this->getRetentionCutoff());

		// Anonymise orders that are no longer needed
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `customer_id` = '0', `firstname` = '', `lastname` = '', `email` = '', `telephone` = '', `custom_field` = '', `payment_address_id` = '0', `payment_firstname` = '', `payment_lastname` = '', `payment_company` = '', `payment_address_1` = '', `payment_address_2` = '', `payment_city` = '', `payment_postcode` = '', `payment_custom_field` = '', `shipping_address_id` = '0', `shipping_firstname` = '', `shipping_lastname` = '', `shipping_company` = '', `shipping_address_1` = '', `shipping_address_2` = '', `shipping_city` = '', `shipping_postcode` = '', `shipping_custom_field` = '', `comment` = '', `ip` = '', `forwarded_ip` = '', `user_agent` = '', `accept_language` = '' WHERE " . $match . " AND (`order_status_id` = '0' OR `date_added` < '" . $cutoff . "')");

		// Anonymise returns that are no longer needed
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET `customer_id` = '0', `firstname` = '', `lastname` = '', `email` = '', `telephone` = '', `comment` = '' WHERE " . $match . " AND `date_added` < '" . $cutoff . "'");

		// Minimise the orders and returns that are kept until the GDPR limit
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `customer_id` = '0', `telephone` = '', `custom_field` = '', `payment_address_id` = '0', `payment_custom_field` = '', `shipping_address_id` = '0', `shipping_custom_field` = '', `accept_language` = '' WHERE " . $match);
		$this->db->query("UPDATE `" . DB_PREFIX . "return` SET `customer_id` = '0', `telephone` = '' WHERE " . $match);
	}

	/**
	 * Get Subject Match
	 *
	 * @param string $email
	 * @param int    $customer_id
	 *
	 * @return string SQL condition matching the records of the data subject
	 */
	private function getSubjectMatch(string $email, int $customer_id): string {
		$sql = "(LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'";

		if ($customer_id) {
			$sql .= " OR `customer_id` = '" . (int)$customer_id . "'";
		}

		return $sql . ")";
	}

	/**
	 * Get Retention Cutoff
	 *
	 * @return string date before which order and return records are no longer kept
	 */
	private function getRetentionCutoff(): string {
		return date('Y-m-d H:i:s', strtotime('-' . max(0, (int)$this->config->get('config_gdpr_limit')) . ' days'));
	}
}
