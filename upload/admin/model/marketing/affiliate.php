<?php
namespace Opencart\Admin\Model\Marketing;
/**
 * Class Affiliate
 *
 * Can be loaded using $this->load->model('marketing/affiliate');
 *
 * @package Opencart\Admin\Model\Marketing
 */
class Affiliate extends \Opencart\System\Engine\Model {
	/**
	 * Add Affiliate
	 *
	 * Create a new customer affiliate record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $affiliate_data = [
	 *     'customer_id'         => 1,
	 *     'company'             => '',
	 *     'website'             => '',
	 *     'tracking'            => '',
	 *     'commission'          => 0.00,
	 *     'tax'                 => '',
	 *     'payment_method'      => '',
	 *     'cheque'              => '',
	 *     'paypal'              => '',
	 *     'bank_name'           => 'Bank Name',
	 *     'bank_branch_number'  => '',
	 *     'bank_swift_code'     => '',
	 *     'bank_account_name'   => 'Bank Account Name',
	 *     'bank_account_number' => '',
	 *     'custom_field'        => [],
	 *     'status'              => 0
	 * ];
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $this->model_marketing_affiliate->addAffiliate($affiliate_data);
	 */
	public function addAffiliate(array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate` SET `customer_id` = '" . (int)$data['customer_id'] . "', `company` = '" . $this->db->escape((string)$data['company']) . "', `website` = '" . $this->db->escape((string)$data['website']) . "', `tracking` = '" . $this->db->escape((string)$data['tracking']) . "', `commission` = '" . (float)$data['commission'] . "', `tax` = '" . $this->db->escape((string)$data['tax']) . "', `payment_method` = '" . $this->db->escape((string)$data['payment_method']) . "', `cheque` = '" . $this->db->escape((string)$data['cheque']) . "', `paypal` = '" . $this->db->escape((string)$data['paypal']) . "', `bank_name` = '" . $this->db->escape((string)$data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape((string)$data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape((string)$data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape((string)$data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape((string)$data['bank_account_number']) . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : json_encode([])) . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_added` = NOW()");
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
	 *     'customer_id'         => 1,
	 *     'company'             => '',
	 *     'website'             => '',
	 *     'tracking'            => '',
	 *     'commission'          => 0.00,
	 *     'tax'                 => '',
	 *     'payment_method'      => '',
	 *     'cheque'              => '',
	 *     'paypal'              => '',
	 *     'bank_name'           => 'Bank Name',
	 *     'bank_branch_number'  => '',
	 *     'bank_swift_code'     => '',
	 *     'bank_account_name'   => 'Bank Account Name',
	 *     'bank_account_number' => '',
	 *     'custom_field'        => [],
	 *     'status'              => 1
	 * ];
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $this->model_marketing_affiliate->editAffiliate($customer_id, $affiliate_data);
	 */
	public function editAffiliate(int $customer_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_affiliate` SET `company` = '" . $this->db->escape((string)$data['company']) . "', `website` = '" . $this->db->escape((string)$data['website']) . "', `tracking` = '" . $this->db->escape((string)$data['tracking']) . "', `commission` = '" . (float)$data['commission'] . "', `tax` = '" . $this->db->escape((string)$data['tax']) . "', `payment_method` = '" . $this->db->escape((string)$data['payment_method']) . "', `cheque` = '" . $this->db->escape((string)$data['cheque']) . "', `paypal` = '" . $this->db->escape((string)$data['paypal']) . "', `bank_name` = '" . $this->db->escape((string)$data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape((string)$data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape((string)$data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape((string)$data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape((string)$data['bank_account_number']) . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : json_encode([])) . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Edit Balance
	 *
	 * Edit customer affiliate balance by customer record in the database.
	 *
	 * @param int   $customer_id primary key of the customer record
	 * @param float $amount
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $this->model_marketing_affiliate->editBalance($customer_id, $amount);
	 */
	public function editBalance(int $customer_id, float $amount): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_affiliate` SET `balance` = '" . (float)$amount . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete Affiliate
	 *
	 * Delete customer affiliate in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $this->model_marketing_affiliate->deleteAffiliate($customer_id);
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
	 * $this->load->model('marketing/affiliate');
	 *
	 * $affiliate_info = $this->model_marketing_affiliate->getAffiliate($customer_id);
	 */
	public function getAffiliate(int $customer_id): array {
		$query = $this->db->query("SELECT DISTINCT *, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `customer`, `ca`.`custom_field`, `ca`.`status` FROM `" . DB_PREFIX . "customer_affiliate` `ca` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ca`.`customer_id` = `c`.`customer_id`) WHERE `ca`.`customer_id` = '" . (int)$customer_id . "'");

		if ($query->num_rows) {
			return ['custom_field' => $query->row['custom_field'] ? json_decode($query->row['custom_field'], true) : []] + $query->row;
		} else {
			return [];
		}
	}

	/**
	 * Get Affiliate By Tracking
	 *
	 * @param string $tracking
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $affiliate_info = $this->model_marketing_affiliate->getAffiliateByTracking($tracking);
	 */
	public function getAffiliateByTracking(string $tracking): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `tracking` = '" . $this->db->escape($tracking) . "'");

		if ($query->num_rows) {
			return ['custom_field' => $query->row['custom_field'] ? json_decode($query->row['custom_field'], true) : []] + $query->row;
		} else {
			return [];
		}
	}

	/**
	 * Get Affiliates
	 *
	 * Get the record of the customer affiliate records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> affiliate records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'           => 'John Doe',
	 *     'filter_tracking'       => '',
	 *     'filter_payment_method' => '',
	 *     'filter_commission'     => 0.00,
	 *     'filter_date_from'      => '2021-01-01',
	 *     'filter_date_to'        => '2021-01-31',
	 *     'filter_status'         => 1,
	 *     'sort'                  => 'name',
	 *     'order'                 => 'DESC',
	 *     'start'                 => 0,
	 *     'limit'                 => 10
	 * ];
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $results = $this->model_marketing_affiliate->getAffiliates($filter_data);
	 */
	public function getAffiliates(array $data = []): array {
		$sql = "SELECT *, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `name`, `ca`.`status` FROM `" . DB_PREFIX . "customer_affiliate` `ca` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ca`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_tracking'])) {
			$implode[] = "LCASE(`ca`.`tracking`) = '" . $this->db->escape(oc_strtolower($data['filter_tracking'])) . "'";
		}

		if (!empty($data['filter_payment_method'])) {
			$implode[] = "LCASE(`ca`.`payment_method`) = '" . $this->db->escape(oc_strtolower($data['filter_payment_method'])) . "'";
		}

		if (!empty($data['filter_commission'])) {
			$implode[] = "`ca`.`commission` = '" . (float)$data['filter_commission'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`ca`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`ca`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`ca`.`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			'name'       => 'name',
			'tracking'   => 'ca.tracking',
			'commission' => 'ca.commission',
			'balance'    => 'ca.balance',
			'date_added' => 'ca.date_added'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `name`";
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

		$order_data = [];

		$query = $this->db->query($sql);

		foreach ($query->rows as $key => $result) {
			$order_data[$key] = ['custom_field' => $result['custom_field'] ? json_decode($result['custom_field'], true) : []] + $result;
		}

		return $order_data;
	}

	/**
	 * Get Total Affiliates
	 *
	 * Get the total number of total customer affiliate records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of affiliate records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'           => 'John Doe',
	 *     'filter_tracking'       => '',
	 *     'filter_payment_method' => '',
	 *     'filter_commission'     => 0.00,
	 *     'filter_date_from'      => '2021-01-01',
	 *     'filter_date_to'        => '2021-01-31',
	 *     'filter_status'         => 1,
	 *     'sort'                  => 'name',
	 *     'order'                 => 'DESC',
	 *     'start'                 => 0,
	 *     'limit'                 => 10
	 * ];
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $affiliate_total = $this->model_marketing_affiliate->getTotalAffiliates($filter_data);
	 */
	public function getTotalAffiliates(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_affiliate` `ca` LEFT JOIN `" . DB_PREFIX . "customer` `c` ON (`ca`.`customer_id` = `c`.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_tracking'])) {
			$implode[] = "LCASE(`ca`.`tracking`) = '" . $this->db->escape(oc_strtolower($data['filter_tracking'])) . "'";
		}

		if (!empty($data['filter_payment_method'])) {
			$implode[] = "LCASE(`ca`.`payment_method`) = '" . $this->db->escape(oc_strtolower($data['filter_payment_method'])) . "'";
		}

		if (!empty($data['filter_commission'])) {
			$implode[] = "`ca`.`commission` = '" . (float)$data['filter_commission'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`ca`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`ca`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`ca`.`status` = '" . (bool)$data['filter_status'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Reports
	 *
	 * Get the record of the customer affiliate reports by customer records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> report records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $results = $this->model_marketing_affiliate->getReports($customer_id, $start, $limit);
	 */
	public function getReports(int $customer_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `ip`, `store_id`, `country`, `date_added` FROM `" . DB_PREFIX . "customer_affiliate_report` WHERE `customer_id` = '" . (int)$customer_id . "' ORDER BY `date_added` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Delete Reports
	 *
	 * Delete customer affiliate report records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $this->model_marketing_affiliate->deleteReports($customer_id);
	 */
	public function deleteReports(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_affiliate_report` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Get Total Reports
	 *
	 * Get the total number of total customer affiliate report records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of report records
	 *
	 * @example
	 *
	 * $this->load->model('marketing/affiliate');
	 *
	 * $report_total = $this->model_marketing_affiliate->getTotalReports($customer_id);
	 */
	public function getTotalReports(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_affiliate_report` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}
}
