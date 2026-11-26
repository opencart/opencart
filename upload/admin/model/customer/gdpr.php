<?php
namespace Opencart\Admin\Model\Customer;
/**
 * Class GDPR
 *
 * Can be loaded using $this->load->model('customer/gdpr');
 *
 * @package Opencart\Admin\Model\Customer
 */
class Gdpr extends \Opencart\System\Engine\Model {
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
	 * $this->load->model('customer/gdpr');
	 *
	 * $this->model_customer_gdpr->editStatus($gdpr_id, $status);
	 */
	public function editStatus(int $gdpr_id, int $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "gdpr` SET `status` = '" . (int)$status . "' WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");
	}

	/**
	 * Delete Gdpr
	 *
	 * Delete gdpr record in the database.
	 *
	 * @param int $gdpr_id primary key of the gdpr record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $this->model_customer_gdpr->deleteGdpr($gdpr_id);
	 */
	public function deleteGdpr(int $gdpr_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gdpr` WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");
	}

	/**
	 * Delete Gdpr(s) By Store ID
	 *
	 * Delete gdprs by store records in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $this->model_customer_gdpr->deleteGdprsByStoreId($store_id);
	 */
	public function deleteGdprsByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "gdpr` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Gdpr(s)
	 *
	 * Get the record of the gdpr records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> gdpr records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_email'     => 'demo@opencart.com',
	 *     'filter_action'    => 'export',
	 *     'filter_status'    => 1,
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $results = $this->model_customer_gdpr->getGdprs($filter_data);
	 */
	public function getGdprs(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "gdpr`";

		$implode = [];

		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email'])) . "'";
		}

		if (!empty($data['filter_action'])) {
			$implode[] = "`action` = '" . $this->db->escape($data['filter_action']) . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY `date_added` DESC";

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
	 * Get Gdpr
	 *
	 * Get the record of the gdpr record in the database.
	 *
	 * @param int $gdpr_id primary key of the gdpr record
	 *
	 * @return array<string, mixed> gdpr record that has gdpr ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $gdpr_info = $this->model_customer_gdpr->getGdpr($gdpr_id);
	 */
	public function getGdpr(int $gdpr_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `gdpr_id` = '" . (int)$gdpr_id . "'");

		return $query->row;
	}

	/**
	 * Get Total Gdpr(s)
	 *
	 * Get the total number of gdpr records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of gdpr records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_email'     => 'demo@opencart.com',
	 *     'filter_action'    => 'export',
	 *     'filter_status'    => 1,
	 *     'filter_date_from' => '2021-01-01',
	 *     'filter_date_to'   => '2021-01-31',
	 *     'start'            => 0,
	 *     'limit'            => 10
	 * ];
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $gdpr_total = $this->model_customer_gdpr->getTotalGdprs($filter_data);
	 */
	public function getTotalGdprs(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "gdpr`";

		$implode = [];

		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email'])) . "'";
		}

		if (!empty($data['filter_action'])) {
			$implode[] = "`action` = '" . $this->db->escape($data['filter_action']) . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Expires
	 *
	 * @return array<int, array<string, mixed>> expire records
	 *
	 * @example
	 *
	 * $this->load->model('customer/gdpr');
	 *
	 * $results = $this->model_customer_gdpr->getExpires();
	 */
	public function getExpires(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `status` = '2' AND DATE(`date_added`) <= DATE('" . $this->db->escape(date('Y-m-d', strtotime('-' . (int)$this->config->get('config_gdpr_limit') . ' days'))) . "') ORDER BY `date_added` DESC");

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
	 * $total = $this->model_customer_gdpr->getTotalRetainedRecords($email, $customer_id);
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
	 * $this->model_customer_gdpr->eraseCustomerData($email, $customer_id);
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
	 * $this->model_customer_gdpr->anonymiseRecords($email, $customer_id);
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
