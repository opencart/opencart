<?php
namespace Opencart\Admin\Model\Customer;
/**
 * Class Customer
 *
 * Can be loaded using $this->load->model('customer/customer');
 *
 * @package Opencart\Admin\Model\Customer
 */
class Customer extends \Opencart\System\Engine\Model {
	/**
	 * Add Customer
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $customer_data = [
	 *     'store_id'          => 1,
	 *     'language_id'       => 1,
	 *     'customer_group_id' => 1,
	 *     'firstname'         => 'John',
	 *     'lastname'          => 'Doe',
	 *     'email'             => 'demo@opencart.com',
	 *     'telephone'         => '1234567890',
	 *     'custom_field'      => [],
	 *     'newsletter'        => 0,
	 *     'password'          => '',
	 *     'status'            => 0,
	 *     'safe'              => 0,
	 *     'commenter'         => 0
	 * ];
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $customer_id = $this->model_customer_customer->addCustomer($customer_data);
	 */
	public function addCustomer(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET `store_id` = '" . (int)$data['store_id'] . "', `language_id` = '" . (int)$data['language_id'] . "', `customer_group_id` = '" . (int)$data['customer_group_id'] . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape(oc_strtolower($data['email'])) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : json_encode([])) . "', `newsletter` = '" . (isset($data['newsletter']) ? (bool)$data['newsletter'] : 0) . "', `password` = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', `status` = '" . (isset($data['status']) ? (bool)$data['status'] : 0) . "', `safe` = '" . (isset($data['safe']) ? (bool)$data['safe'] : 0) . "', `commenter` = '" . (isset($data['commenter']) ? (bool)$data['commenter'] : 0) . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	/**
	 * Edit Customer
	 *
	 * @param int                  $customer_id primary key of the customer record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $customer_data = [
	 *     'store_id'          => 1,
	 *     'language_id'       => 1,
	 *     'customer_group_id' => 1,
	 *     'firstname'         => 'John',
	 *     'lastname'          => 'Doe',
	 *     'email'             => 'demo@opencart.com',
	 *     'telephone'         => '1234567890',
	 *     'custom_field'      => [],
	 *     'newsletter'        => 0,
	 *     'password'          => '',
	 *     'status'            => 1,
	 *     'safe'              => 0,
	 *     'commenter'         => 0
	 * ];
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->editCustomer($customer_id, $customer_data);
	 */
	public function editCustomer(int $customer_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `store_id` = '" . (int)$data['store_id'] . "', `language_id` = '" . (int)$data['language_id'] . "', `customer_group_id` = '" . (int)$data['customer_group_id'] . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape(oc_strtolower($data['email'])) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : json_encode([])) . "', `newsletter` = '" . (isset($data['newsletter']) ? (bool)$data['newsletter'] : 0) . "', `status` = '" . (isset($data['status']) ? (bool)$data['status'] : 0) . "', `safe` = '" . (isset($data['safe']) ? (bool)$data['safe'] : 0) . "', `commenter` = '" . (isset($data['commenter']) ? (bool)$data['commenter'] : 0) . "' WHERE `customer_id` = '" . (int)$customer_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `password` = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
		}
	}

	/**
	 * Edit Token
	 *
	 * @param int    $customer_id primary key of the customer record
	 * @param string $token
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->editToken($customer_id, $token);
	 */
	public function editToken(int $customer_id, string $token): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `token` = '" . $this->db->escape($token) . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Edit Commenter
	 *
	 * @param int  $customer_id primary key of the customer record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->editCommenter($customer_id, $status);
	 */
	public function editCommenter(int $customer_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `commenter` = '" . (bool)$status . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete Customer
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteCustomer($customer_id);
	 */
	public function deleteCustomer(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer` WHERE `customer_id` = '" . (int)$customer_id . "'");

		$this->deleteActivities($customer_id);

		$this->deleteAddresses($customer_id);
		$this->deleteAuthorizes($customer_id);
		$this->deleteHistories($customer_id);
		$this->deleteRewards($customer_id);
		$this->deleteTransactions($customer_id);
		$this->deleteWishlists($customer_id);
		$this->deleteIps($customer_id);

		// Affiliate
		$this->load->model('marketing/affiliate');

		$this->model_marketing_affiliate->deleteAffiliate($customer_id);

		// Customer Approval
		$this->load->model('customer/customer_approval');

		$this->model_customer_customer_approval->deleteApprovalsByCustomerId($customer_id);
	}

	/**
	 * Get Customer
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return array<string, mixed> customer record that has customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $customer_info = $this->model_customer_customer->getCustomer($customer_id);
	 */
	public function getCustomer(int $customer_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "customer` WHERE `customer_id` = '" . (int)$customer_id . "'");

		if ($query->num_rows) {
			return ['custom_field' => json_decode($query->row['custom_field'], true)] + $query->row;
		} else {
			return [];
		}
	}

	/**
	 * Get Customer By Email
	 *
	 * @param string $email
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $customer_info = $this->model_customer_customer->getCustomerByEmail($email);
	 */
	public function getCustomerByEmail(string $email): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "customer` WHERE LCASE(`email`) = '" . $this->db->escape(oc_strtolower($email)) . "'");

		if ($query->num_rows) {
			return ['custom_field' => json_decode($query->row['custom_field'], true)] + $query->row;
		} else {
			return [];
		}
	}

	/**
	 * Get Customers
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> customer records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'              => 'John Doe',
	 *     'filter_email'             => 'demo@opencart.com',
	 *     'filter_customer_group_id' => 1,
	 *     'filter_status'            => 1,
	 *     'filter_ip'                => '',
	 *     'filter_date_from'         => '2021-01-01',
	 *     'filter_date_to'           => '2021-01-31',
	 *     'sort'                     => 'name',
	 *     'order'                    => 'DESC',
	 *     'start'                    => 0,
	 *     'limit'                    => 10
	 * ];
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $results = $this->model_customer_customer->getCustomers($filter_data);
	 */
	public function getCustomers(array $data = []): array {
		$sql = "SELECT *, CONCAT(`c`.`firstname`, ' ', `c`.`lastname`) AS `name`, `cgd`.`name` AS `customer_group` FROM `" . DB_PREFIX . "customer` `c` LEFT JOIN `" . DB_PREFIX . "customer_group_description` `cgd` ON (`c`.`customer_group_id` = `cgd`.`customer_group_id`) WHERE `cgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND LCASE(`c`.`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email']) . '%') . "'";
		}

		if (isset($data['filter_newsletter']) && $data['filter_newsletter'] !== '') {
			$sql .= " AND `c`.`newsletter` = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND `c`.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$sql .= " AND `c`.`customer_id` IN (SELECT `customer_id` FROM `" . DB_PREFIX . "customer_ip` WHERE `ip` = '" . $this->db->escape((string)$data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND `c`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$sql .= " AND DATE(`c`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$sql .= " AND DATE(`c`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		$sort_data = [
			'name',
			'c.email',
			'customer_group',
			'c.status',
			'c.ip',
			'c.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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

		$customer_data = [];

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$customer_data[] = $result + ['custom_field' => json_decode($result['custom_field'], true)];
		}

		return $customer_data;
	}

	/**
	 * Get Total Customers
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of customer records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'              => 'John Doe',
	 *     'filter_email'             => 'demo@opencart.com',
	 *     'filter_customer_group_id' => 1,
	 *     'filter_status'            => 1,
	 *     'filter_ip'                => '',
	 *     'filter_date_from'         => '2021-01-01',
	 *     'filter_date_to'           => '2021-01-31',
	 *     'sort'                     => 'name',
	 *     'order'                    => 'DESC',
	 *     'start'                    => 0,
	 *     'limit'                    => 10
	 * ];
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $customer_total = $this->model_customer_customer->getTotalCustomers($filter_data);
	 */
	public function getTotalCustomers(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer` `c`";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(`c`.`firstname`, ' ', `c`.`lastname`)) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(`c`.`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email']) . '%') . "'";
		}

		if (isset($data['filter_newsletter']) && $data['filter_newsletter'] !== '') {
			$implode[] = "`c`.`newsletter` = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "`c`.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "`c`.`customer_id` IN (SELECT `customer_id` FROM `" . DB_PREFIX . "customer_ip` WHERE `ip` = '" . $this->db->escape((string)$data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "`c`.`status` = '" . (int)$data['filter_status'] . "'";
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
	 * Get Total Customers By Customer Group ID
	 *
	 * @param int $customer_group_id primary key of the customer group record
	 *
	 * @return int total number of customer records that have customer group ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $customer_total = $this->model_customer_customer->getTotalCustomersByCustomerGroupId($customer_group_id);
	 */
	public function getTotalCustomersByCustomerGroupId(int $customer_group_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer` WHERE `customer_group_id` = '" . (int)$customer_group_id . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	/**
	 * Delete Activities
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteActivities($customer_id);
	 */
	public function deleteActivities(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_activity` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Add Address
	 *
	 * @param int                  $customer_id primary key of the customer record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return int returns the primary key of the new address record
	 *
	 * @example
	 *
	 * $address_data = [
	 *     'firstname'    => 'John',
	 *     'lastname'     => 'Doe',
	 *     'company'      => '',
	 *     'address_1'    => 'Address 1',
	 *     'address_2'    => 'Address 2',
	 *     'city'         => '',
	 *     'postcode'     => '90210',
	 *     'country_id'   => 1,
	 *     'zone_id'      => 1,
	 *     'custom_field' => [],
	 *     'default'      => 0
	 * ];
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->addAddress($customer_id, $address_data);
	 */
	public function addAddress(int $customer_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET `customer_id` = '" . (int)$customer_id . "', `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `company` = '" . $this->db->escape($data['company']) . "', `address_1` = '" . $this->db->escape($data['address_1']) . "', `address_2` = '" . $this->db->escape($data['address_2']) . "', `city` = '" . $this->db->escape($data['city']) . "', `postcode` = '" . $this->db->escape($data['postcode']) . "', `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : json_encode([])) . "', `default` = '" . (!empty($data['default']) ? (bool)$data['default'] : 0) . "'");

		$address_id = $this->db->getLastId();

		if (!empty($data['default'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `default` = '0' WHERE `customer_id` = '" . (int)$customer_id . "' AND `address_id` != '" . (int)$address_id . "'");
		}

		return $address_id;
	}

	/**
	 * Edit Address
	 *
	 * @param int                  $customer_id primary key of the customer record
	 * @param int                  $address_id  primary key of the address record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $address_data = [
	 *     'firstname'    => 'John',
	 *     'lastname'     => 'Doe',
	 *     'company'      => '',
	 *     'address_1'    => 'Address 1',
	 *     'address_2'    => 'Address 2',
	 *     'city'         => '',
	 *     'postcode'     => '90210',
	 *     'country_id'   => 1,
	 *     'zone_id'      => 1,
	 *     'custom_field' => [],
	 *     'default'      => 0
	 * ];
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->editAddress($customer_id, $address_id, $address_data);
	 */
	public function editAddress(int $customer_id, int $address_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `company` = '" . $this->db->escape($data['company']) . "', `address_1` = '" . $this->db->escape($data['address_1']) . "', `address_2` = '" . $this->db->escape($data['address_2']) . "', `city` = '" . $this->db->escape($data['city']) . "', `postcode` = '" . $this->db->escape($data['postcode']) . "', `country_id` = '" . (int)$data['country_id'] . "', `zone_id` = '" . (int)$data['zone_id'] . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : json_encode([])) . "', `default` = '" . (!empty($data['default']) ? (bool)$data['default'] : 0) . "' WHERE `address_id` = '" . (int)$address_id . "'");

		if (!empty($data['default'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `default` = '0' WHERE `customer_id` = '" . (int)$customer_id . "' AND `address_id` != '" . (int)$address_id . "'");
		}
	}

	/**
	 * Delete Addresses
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $address_id  primary key of the address record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteAddresses($customer_id, $address_id);
	 */
	public function deleteAddresses(int $customer_id, int $address_id = 0): void {
		$sql = "DELETE FROM `" . DB_PREFIX . "address` WHERE `customer_id` = '" . (int)$customer_id . "'";

		if ($address_id) {
			$sql .= " AND `address_id` = '" . (int)$address_id . "'";
		}

		$this->db->query($sql);
	}

	/**
	 * Get Address
	 *
	 * @param int $address_id primary key of the address record
	 *
	 * @return array<string, mixed> address record that has address ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $address_info = $this->model_customer_customer->getAddress($address_id);
	 */
	public function getAddress(int $address_id): array {
		$address_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "address` WHERE `address_id` = '" . (int)$address_id . "'");

		if ($address_query->num_rows) {
			// Country
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($address_query->row['country_id']);

			if ($country_info) {
				$country = $country_info['name'];
				$iso_code_2 = $country_info['iso_code_2'];
				$iso_code_3 = $country_info['iso_code_3'];
				$address_format_id = $country_info['address_format_id'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format_id = 0;
			}

			// Address Format
			$this->load->model('localisation/address_format');

			$address_format_info = $this->model_localisation_address_format->getAddressFormat($address_format_id);

			if ($address_format_info) {
				$address_format = $address_format_info['address_format'];
			} else {
				$address_format = '';
			}

			// Zone
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($address_query->row['zone_id']);

			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			return [
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($address_query->row['custom_field'], true)
			] + $address_query->row;
		}

		return [];
	}

	/**
	 * Get Addresses
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return array<int, array<string, mixed>> address records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $results = $this->model_customer_customer->getAddresses($customer_id);
	 */
	public function getAddresses(int $customer_id): array {
		$address_data = [];

		// Country
		$this->load->model('localisation/country');

		// Address Format
		$this->load->model('localisation/address_format');

		// Zone
		$this->load->model('localisation/zone');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "address` WHERE `customer_id` = '" . (int)$customer_id . "'");

		foreach ($query->rows as $result) {
			$country_info = $this->model_localisation_country->getCountry($result['country_id']);

			if ($country_info) {
				$country = $country_info['name'];
				$iso_code_2 = $country_info['iso_code_2'];
				$iso_code_3 = $country_info['iso_code_3'];
				$address_format_id = $country_info['address_format_id'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format_id = 0;
			}

			$address_format_info = $this->model_localisation_address_format->getAddressFormat($address_format_id);

			if ($address_format_info) {
				$address_format = $address_format_info['address_format'];
			} else {
				$address_format = '';
			}

			$zone_info = $this->model_localisation_zone->getZone($result['zone_id']);

			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data[] = [
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($result['custom_field'], true)
			] + $result;
		}

		return $address_data;
	}

	/**
	 * Get Total Addresses
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of address records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $address_total = $this->model_customer_customer->getTotalAddresses($customer_id);
	 */
	public function getTotalAddresses(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "address` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Addresses By Country ID
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return int total number of address records that have country ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $address_total = $this->model_customer_customer->getTotalAddressesByCountryId($country_id);
	 */
	public function getTotalAddressesByCountryId(int $country_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "address` WHERE `country_id` = '" . (int)$country_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Addresses By Zone ID
	 *
	 * @param int $zone_id primary key of the zone record
	 *
	 * @return int total number of address records that have zone ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $address_total = $this->model_customer_customer->getTotalAddressesByZoneId($zone_id);
	 */
	public function getTotalAddressesByZoneId(int $zone_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "address` WHERE `zone_id` = '" . (int)$zone_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add History
	 *
	 * @param int    $customer_id primary key of the customer record
	 * @param string $comment
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->addHistory($customer_id, $comment);
	 */
	public function addHistory(int $customer_id, string $comment): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_history` SET `customer_id` = '" . (int)$customer_id . "', `comment` = '" . $this->db->escape(strip_tags($comment)) . "', `date_added` = NOW()");
	}

	/**
	 * Delete Customer Histories
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteHistories($customer_id);
	 */
	public function deleteHistories(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_history` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Get Histories
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> history records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $results = $this->model_customer_customer->getHistories($customer_id, $start, $limit);
	 */
	public function getHistories(int $customer_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `comment`, `date_added` FROM `" . DB_PREFIX . "customer_history` WHERE `customer_id` = '" . (int)$customer_id . "' ORDER BY `date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Histories
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of history records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $history_total = $this->model_customer_customer->getTotalHistories($customer_id);
	 */
	public function getTotalHistories(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_history` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Delete Wishlists
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteWishlists($customer_id);
	 */
	public function deleteWishlists(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete Wishlist By Store ID
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteWishlistByStoreId($store_id);
	 */
	public function deleteWishlistByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Delete Wishlist By Product ID
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteWishlistByProductId($product_id);
	 */
	public function deleteWishlistByProductId(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_wishlist` WHERE `product_id` = '" . (int)$product_id . "'");
	}

	/**
	 * Add Transaction
	 *
	 * @param int    $customer_id primary key of the customer record
	 * @param string $description
	 * @param float  $amount
	 * @param int    $order_id    primary key of the order record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->addTransaction($customer_id, (string)$description, (float)$amount);
	 */
	public function addTransaction(int $customer_id, string $description = '', float $amount = 0, int $order_id = 0): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_transaction` SET `customer_id` = '" . (int)$customer_id . "', `order_id` = '" . (int)$order_id . "', `description` = '" . $this->db->escape($description) . "', `amount` = '" . (float)$amount . "', `date_added` = NOW()");
	}

	/**
	 * Delete Transactions
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteTransactions($customer_id);
	 */
	public function deleteTransactions(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete Transactions By Order ID
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteTransactionByOrderId($order_id);
	 */
	public function deleteTransactionsByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * Get Transactions
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> transaction records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $results = $this->model_customer_customer->getTransactions($customer_id, $start, $limit);
	 */
	public function getTransactions(int $customer_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "' ORDER BY `date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Transactions
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of transaction records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $transaction_total = $this->model_customer_customer->getTotalTransactions($customer_id);
	 */
	public function getTotalTransactions(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Transaction Total
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $transaction_total = $this->model_customer_customer->getTransactionTotal($customer_id);
	 */
	public function getTransactionTotal(int $customer_id): float {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (float)$query->row['total'];
	}

	/**
	 * Get Total Transactions By Order ID
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return int total number of transaction records that have order ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $transaction_total = $this->model_customer_customer->getTotalTransactionsByOrderId($order_id);
	 */
	public function getTotalTransactionsByOrderId(int $order_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Reward
	 *
	 * @param int    $customer_id primary key of the customer record
	 * @param string $description
	 * @param int    $points
	 * @param int    $order_id    primary key of the order record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->addReward($customer_id, (string)$description, (int)$points, (int)$order_id);
	 */
	public function addReward(int $customer_id, string $description = '', int $points = 0, int $order_id = 0): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_reward` SET `customer_id` = '" . (int)$customer_id . "', `order_id` = '" . (int)$order_id . "', `points` = '" . (int)$points . "', `description` = '" . $this->db->escape($description) . "', `date_added` = NOW()");
	}

	/**
	 * Delete Rewards
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteRewards($customer_id);
	 */
	public function deleteRewards(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete Rewards By Order ID
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteRewardsByOrderId($order_id);
	 */
	public function deleteRewardsByOrderId(int $order_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_reward` WHERE `order_id` = '" . (int)$order_id . "' AND `points` > '0'");
	}

	/**
	 * Get Rewards
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> reward records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $results = $this->model_customer_customer->getRewards($customer_id, $start, $limit);
	 */
	public function getRewards(int $customer_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$customer_id . "' ORDER BY `date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Rewards
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of reward records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $reward_total = $this->model_customer_customer->getTotalRewards($customer_id);
	 */
	public function getTotalRewards(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Reward Total
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $reward_total = $this->model_customer_customer->getRewardTotal($customer_id);
	 */
	public function getRewardTotal(int $customer_id): int {
		$query = $this->db->query("SELECT SUM(points) AS `total` FROM `" . DB_PREFIX . "customer_reward` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Rewards By Order ID
	 *
	 * @param int $order_id primary key of the order record
	 *
	 * @return int total number of reward records that have order ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $reward_total = $this->model_customer_customer->getTotalRewardsByOrderId($order_id);
	 */
	public function getTotalRewardsByOrderId(int $order_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_reward` WHERE `order_id` = '" . (int)$order_id . "' AND `points` > '0'");

		return (int)$query->row['total'];
	}

	/**
	 * Delete Ips
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteIps($customer_id);
	 */
	public function deleteIps(int $customer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_ip` WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Get Ips
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> ip records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $results = $this->model_customer_customer->getIps($customer_id, $start, $limit);
	 */
	public function getIps(int $customer_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}
		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `ip`, `store_id`, `country`, `date_added` FROM `" . DB_PREFIX . "customer_ip` WHERE `customer_id` = '" . (int)$customer_id . "' ORDER BY `date_added` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Ips
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of ip records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $ip_total = $this->model_customer_customer->getTotalIps($customer_id);
	 */
	public function getTotalIps(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_ip` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Customers By Ip
	 *
	 * @param string $ip
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $customer_total = $this->model_customer_customer->getTotalCustomersByIp($result['ip']);
	 */
	public function getTotalCustomersByIp(string $ip): int {
		$query = $this->db->query("SELECT COUNT(DISTINCT `customer_id`) AS `total` FROM `" . DB_PREFIX . "customer_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Login Attempts
	 *
	 * @param string $email
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $login_info = $this->model_customer_customer->getTotalLoginAttempts($email);
	 */
	public function getTotalLoginAttempts(string $email): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape(oc_strtolower($email)) . "'");

		return $query->row;
	}

	/**
	 * Delete Login Attempts
	 *
	 * @param string $email
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteLoginAttempts($email);
	 */
	public function deleteLoginAttempts(string $email): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape(oc_strtolower($email)) . "'");
	}

	/**
	 * Add Authorize
	 *
	 * @param int                  $customer_id primary key of the customer record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $customer_authorize_data = [
	 *     'token'      => '',
	 *     'ip'         => '',
	 *     'user_agent' => ''
	 * ];
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_account_customer->addAuthorize($customer_id, $customer_authorize_data);
	 */
	public function addAuthorize(int $customer_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_authorize` SET `customer_id` = '" . (int)$customer_id . "', `token` = '" . $this->db->escape($data['token']) . "', `ip` = '" . $this->db->escape($data['ip']) . "', `user_agent` = '" . $this->db->escape($data['user_agent']) . "', `date_added` = NOW()");
	}

	/**
	 * Edit Authorize Status
	 *
	 * @param int  $customer_authorize_id primary key of the customer authorize record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_account_customer->editAuthorizeStatus($customer_authorize_id, $status);
	 */
	public function editAuthorizeStatus(int $customer_authorize_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_authorize` SET `status` = '" . (bool)$status . "' WHERE `customer_authorize_id` = '" . (int)$customer_authorize_id . "'");
	}

	/**
	 * Edit Authorize Total
	 *
	 * @param int $customer_authorize_id primary key of the customer authorize record
	 * @param int $total
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_account_customer->editAuthorizeTotal($customer_authorize_id, $total);
	 */
	public function editAuthorizeTotal(int $customer_authorize_id, int $total): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_authorize` SET `total` = '" . (int)$total . "' WHERE `customer_authorize_id` = '" . (int)$customer_authorize_id . "'");
	}

	/**
	 * Reset Authorizes
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_account_customer->resetAuthorizes($customer_id);
	 */
	public function resetAuthorizes(int $customer_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_authorize` SET `total` = '0' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}

	/**
	 * Delete Authorizes
	 *
	 * @param int $customer_id           primary key of the customer record
	 * @param int $customer_authorize_id primary key of the customer authorize record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $this->model_customer_customer->deleteAuthorizes($customer_id, $customer_authorize_id);
	 */
	public function deleteAuthorizes(int $customer_id, int $customer_authorize_id = 0): void {
		$sql = "DELETE FROM `" . DB_PREFIX . "customer_authorize` WHERE `customer_id` = '" . (int)$customer_id . "'";

		if ($customer_authorize_id) {
			$sql .= " AND `customer_authorize_id` = '" . (int)$customer_authorize_id . "'";
		}

		$this->db->query($sql);
	}

	/**
	 * Get Authorize By Token
	 *
	 * @param int    $customer_id primary key of the customer record
	 * @param string $token
	 *
	 * @return array<string, mixed> authorize token record that has customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $login_info = $this->model_account_customer->getAuthorizeByToken($customer_id, $token);
	 */
	public function getAuthorizeByToken(int $customer_id, string $token): array {
		$query = $this->db->query("SELECT *, (SELECT SUM(`total`) FROM `" . DB_PREFIX . "customer_authorize` WHERE `customer_id` = '" . (int)$customer_id . "') AS `attempts` FROM `" . DB_PREFIX . "customer_authorize` WHERE `customer_id` = '" . (int)$customer_id . "' AND `token` = '" . $this->db->escape($token) . "'");

		return $query->row;
	}

	/**
	 * Get Authorizes
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>> authorize records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $results = $this->model_customer_customer->getAuthorizes($customer_id, $start, $limit);
	 */
	public function getAuthorizes(int $customer_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_authorize` WHERE `customer_id` = '" . (int)$customer_id . "' LIMIT " . (int)$start . "," . (int)$limit);

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return [];
		}
	}

	/**
	 * Get Total Authorizes
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of authorize records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('customer/customer');
	 *
	 * $authorize_total = $this->model_customer_customer->getTotalAuthorizes($customer_id);
	 */
	public function getTotalAuthorizes(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer_authorize` WHERE `customer_id` = '" . (int)$customer_id . "'");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}
}
