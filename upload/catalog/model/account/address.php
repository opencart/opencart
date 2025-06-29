<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Address
 *
 * Can be called using $this->load->model('account/address');
 *
 * @package Opencart\Catalog\Model\Account
 */
class Address extends \Opencart\System\Engine\Model {
	/**
	 * Add Address
	 *
	 * Create a new address record in the database.
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
	 *     'postcode'     => '',
	 *     'city'         => '',
	 *     'zone_id'      => 1,
	 *     'country_id'   => 1,
	 *     'custom_field' => [],
	 *     'default'      => 0
	 * ];
	 *
	 * $this->load->model('account/address');
	 *
	 * $this->model_account_address->addAddress($customer_id, $address_data);
	 */
	public function addAddress(int $customer_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET `customer_id` = '" . (int)$customer_id . "', `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `company` = '" . $this->db->escape($data['company']) . "', `address_1` = '" . $this->db->escape($data['address_1']) . "', `address_2` = '" . $this->db->escape($data['address_2']) . "', `postcode` = '" . $this->db->escape($data['postcode']) . "', `city` = '" . $this->db->escape($data['city']) . "', `zone_id` = '" . (int)$data['zone_id'] . "', `country_id` = '" . (int)$data['country_id'] . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', `default` = '" . (isset($data['default']) ? (int)$data['default'] : 0) . "'");

		$address_id = $this->db->getLastId();

		if (!empty($data['default'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `default` = '0' WHERE `address_id` != '" . (int)$address_id . "' AND `customer_id` = '" . (int)$customer_id . "'");
		}

		return $address_id;
	}

	/**
	 * Edit Address
	 *
	 * Edit address record in the database.
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
	 *     'postcode'     => '',
	 *     'city'         => '',
	 *     'zone_id'      => 1,
	 *     'country_id'   => 1,
	 *     'custom_field' => [],
	 *     'default'      => 0
	 * ];
	 *
	 * $this->load->model('account/address');
	 *
	 * $this->model_account_address->addAddress($customer_id, $address_id, $address_data);
	 */
	public function editAddress(int $customer_id, int $address_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `company` = '" . $this->db->escape($data['company']) . "', `address_1` = '" . $this->db->escape($data['address_1']) . "', `address_2` = '" . $this->db->escape($data['address_2']) . "', `postcode` = '" . $this->db->escape($data['postcode']) . "', `city` = '" . $this->db->escape($data['city']) . "', `zone_id` = '" . (int)$data['zone_id'] . "', `country_id` = '" . (int)$data['country_id'] . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', `default` = '" . (isset($data['default']) ? (int)$data['default'] : 0) . "' WHERE `address_id` = '" . (int)$address_id . "' AND `customer_id` = '" . (int)$customer_id . "'");

		if (!empty($data['default'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `default` = '0' WHERE `address_id` != '" . (int)$address_id . "' AND `customer_id` = '" . (int)$customer_id . "'");
		}
	}

	/**
	 * Delete Addresses
	 *
	 * Delete address records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $address_id  primary key of the address record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('account/address');
	 *
	 * $this->model_account_address->deleteAddresses($customer_id, $address_id);
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
	 * Get the record of the address record in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 * @param int $address_id  primary key of the address record
	 *
	 * @return array<string, mixed> address record that has customer ID, address ID
	 *
	 * @example
	 *
	 * $this->load->model('account/address');
	 *
	 * $address_info = $this->model_account_address->getAddress($customer_id, $address_id);
	 */
	public function getAddress(int $customer_id, int $address_id): array {
		$address_query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "address` WHERE `address_id` = '" . (int)$address_id . "' AND `customer_id` = '" . (int)$customer_id . "'");

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
				'custom_field'   => $address_query->row['custom_field'] ? json_decode($address_query->row['custom_field'], true) : []
			] + $address_query->row;
		} else {
			return [];
		}
	}

	/**
	 * Get Addresses
	 *
	 * Get the record of the address records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return array<int, array<string, mixed>> address records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('account/address');
	 *
	 * $results = $this->model_account_address->getAddresses($customer_id);
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

			$address_data[$result['address_id']] = [
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => $result['custom_field'] ? json_decode($result['custom_field'], true) : []
			] + $result;
		}

		return $address_data;
	}

	/**
	 * Get Total Addresses
	 *
	 * Get the total number of total address records in the database.
	 *
	 * @param int $customer_id primary key of the customer record
	 *
	 * @return int total number of address records that have customer ID
	 *
	 * @example
	 *
	 * $this->load->model('account/address');
	 *
	 * $address_total = $this->model_account_address->getTotalAddresses($customer_id);
	 */
	public function getTotalAddresses(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "address` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}
}
