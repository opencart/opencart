<?php
namespace Opencart\Catalog\Model\Account;
/**
 * Class Address
 *
 * @package Opencart\Catalog\Model\Account
 */
class Address extends \Opencart\System\Engine\Model {
	/**
	 * @param int   $customer_id
	 * @param array $data
	 *
	 * @return int
	 */
	public function addAddress(int $customer_id, array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET `customer_id` = '" . (int)$customer_id . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `company` = '" . $this->db->escape((string)$data['company']) . "', `address_1` = '" . $this->db->escape((string)$data['address_1']) . "', `address_2` = '" . $this->db->escape((string)$data['address_2']) . "', `postcode` = '" . $this->db->escape((string)$data['postcode']) . "', `city` = '" . $this->db->escape((string)$data['city']) . "', `zone_id` = '" . (int)$data['zone_id'] . "', `country_id` = '" . (int)$data['country_id'] . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', `default` = '" . (isset($data['default']) ? (int)$data['default'] : 0) . "'");

		$address_id = $this->db->getLastId();

		if (!empty($data['default'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `default` = '0' WHERE `address_id` != '" . (int)$address_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");
		}

		return $address_id;
	}

	/**
	 * @param int   $address_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editAddress(int $address_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `company` = '" . $this->db->escape((string)$data['company']) . "', `address_1` = '" . $this->db->escape((string)$data['address_1']) . "', `address_2` = '" . $this->db->escape((string)$data['address_2']) . "', `postcode` = '" . $this->db->escape((string)$data['postcode']) . "', `city` = '" . $this->db->escape((string)$data['city']) . "', `zone_id` = '" . (int)$data['zone_id'] . "', `country_id` = '" . (int)$data['country_id'] . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', `default` = '" . (isset($data['default']) ? (int)$data['default'] : 0) . "' WHERE `address_id` = '" . (int)$address_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");

		if (!empty($data['default'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `default` = '0' WHERE `address_id` != '" . (int)$address_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");
		}
	}

	/**
	 * @param int $address_id
	 *
	 * @return void
	 */
	public function deleteAddress(int $address_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "address` WHERE `address_id` = '" . (int)$address_id . "' AND `customer_id` = '" . (int)$this->customer->getId() . "'");
	}

	/**
	 * @param int $customer_id
	 * @param int $address_id
	 *
	 * @return array
	 */
	public function getAddress(int $customer_id, int $address_id): array {
		$address_query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "address` WHERE `address_id` = '" . (int)$address_id . "' AND `customer_id` = '" . (int)$customer_id . "'");

		if ($address_query->num_rows) {
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($address_query->row['country_id']);

			if ($country_info) {
				$country = $country_info['name'];
				$iso_code_2 = $country_info['iso_code_2'];
				$iso_code_3 = $country_info['iso_code_3'];
				$address_format = $country_info['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

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
				'address_id'     => $address_query->row['address_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'city'           => $address_query->row['city'],
				'postcode'       => $address_query->row['postcode'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => json_decode($address_query->row['custom_field'], true),
				'default'        => $address_query->row['default']
			];
		} else {
			return [];
		}
	}

	/**
	 * @param int $customer_id
	 *
	 * @return array
	 */
	public function getAddresses(int $customer_id): array {
		$address_data = [];

		$query = $this->db->query("SELECT `address_id` FROM `" . DB_PREFIX . "address` WHERE `customer_id` = '" . (int)$customer_id . "'");

		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($customer_id, $result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}

		return $address_data;
	}

	/**
	 * @param int $customer_id
	 *
	 * @return int
	 */
	public function getTotalAddresses(int $customer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "address` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return (int)$query->row['total'];
	}
}
