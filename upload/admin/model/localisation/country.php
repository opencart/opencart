<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Country
 *
 * Can be loaded using $this->load->model('localisation/country');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Country extends \Opencart\System\Engine\Model {
	/**
	 * Add Country
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $country_data = [
	 *     'name'              => 'Country Name',
	 *     'iso_code_2'        => 'Country ISO Code 2',
	 *     'iso_code_3'        => 'Country ISO Code 3',
	 *     'address_format_id' => 1,
	 *     'postcode_required' => 0,
	 *     'status'            => 0
	 * ];
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $country_id = $this->model_localisation_country->addCountry($country_data);
	 */
	public function addCountry(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "country` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `iso_code_2` = '" . $this->db->escape((string)$data['iso_code_2']) . "', `iso_code_3` = '" . $this->db->escape((string)$data['iso_code_3']) . "', `address_format_id` = '" . (int)$data['address_format_id'] . "', `postcode_required` = '" . (int)$data['postcode_required'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$this->cache->delete('country');

		return $this->db->getLastId();
	}

	/**
	 * Edit Country
	 *
	 * @param int                  $country_id primary key of the country record
	 * @param array<string, mixed> $data       array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $country_data = [
	 *     'name'              => 'Country Name',
	 *     'iso_code_2'        => 'Country ISO Code 2',
	 *     'iso_code_3'        => 'Country ISO Code 3',
	 *     'address_format_id' => 1,
	 *     'postcode_required' => 0,
	 *     'status'            => 1
	 * ];
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $this->model_localisation_country->editCountry($country_id, $country_data);
	 */
	public function editCountry(int $country_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "country` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `iso_code_2` = '" . $this->db->escape((string)$data['iso_code_2']) . "', `iso_code_3` = '" . $this->db->escape((string)$data['iso_code_3']) . "', `address_format_id` = '" . (int)$data['address_format_id'] . "', `postcode_required` = '" . (int)$data['postcode_required'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `country_id` = '" . (int)$country_id . "'");

		$this->cache->delete('country');
	}

	/**
	 * Delete Country
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $this->model_localisation_country->deleteCountry($country_id);
	 */
	public function deleteCountry(int $country_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "country` WHERE `country_id` = '" . (int)$country_id . "'");

		$this->cache->delete('country');
	}

	/**
	 * Get Country
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return array<string, mixed> country record that has country ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $country_info = $this->model_localisation_country->getCountry($country_id);
	 */
	public function getCountry(int $country_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "country` WHERE `country_id` = '" . (int)$country_id . "'");

		return $query->row;
	}

	/**
	 * Get Country By Iso Code 2
	 *
	 * @param string $iso_code_2
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $country_info = $this->model_localisation_country->getCountryByIsoCode2($iso_code_2);
	 */
	public function getCountryByIsoCode2(string $iso_code_2): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($iso_code_2) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * Get Country By Iso Code 3
	 *
	 * @param string $iso_code_3
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $country_info = $this->model_localisation_country->getCountryByIsoCode3($iso_code_3);
	 */
	public function getCountryByIsoCode3(string $iso_code_3): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_3` = '" . $this->db->escape($iso_code_3) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * Get Countries
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> country records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'       => 'Country Name',
	 *     'filter_iso_code_2' => 'Country ISO Code 2',
	 *     'filter_iso_code_3' => 'Country ISO Code 3',
	 *     'sort'              => 'name',
	 *     'order'             => 'DESC',
	 *     'start'             => 0,
	 *     'limit'             => 10
	 * ];
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $countries = $this->model_localisation_country->getCountries($filter_data);
	 */
	public function getCountries(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "country`";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_iso_code_2'])) {
			$implode[] = "LCASE(`iso_code_2`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_iso_code_2']) . '%') . "'";
		}

		if (!empty($data['filter_iso_code_3'])) {
			$implode[] = "LCASE(`iso_code_3`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_iso_code_3']) . '%') . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			'name',
			'iso_code_2',
			'iso_code_3'
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

		$key = md5($sql);

		$country_data = $this->cache->get('country.' . $key);

		if (!$country_data) {
			$query = $this->db->query($sql);

			$country_data = $query->rows;

			$this->cache->set('country.' . $key, $country_data);
		}

		return $country_data;
	}

	/**
	 * Get Total Countries
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of country records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_name'       => 'Country Name',
	 *     'filter_iso_code_2' => 'Country ISO Code 2',
	 *     'filter_iso_code_3' => 'Country ISO Code 3',
	 *     'sort'              => 'name',
	 *     'order'             => 'DESC',
	 *     'start'             => 0,
	 *     'limit'             => 10
	 * ];
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $country_total = $this->model_localisation_country->getTotalCountries($filter_data);
	 */
	public function getTotalCountries(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "country`";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_iso_code_2'])) {
			$implode[] = "LCASE(`iso_code_2`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_iso_code_2']) . '%') . "'";
		}

		if (!empty($data['filter_iso_code_3'])) {
			$implode[] = "LCASE(`iso_code_3`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_iso_code_3']) . '%') . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Countries By Address Format ID
	 *
	 * @param int $address_format_id primary key of the address format record
	 *
	 * @return int total number of country records that have address format ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $country_total = $this->model_localisation_country->getTotalCountriesByAddressFormatId($address_format_id);
	 */
	public function getTotalCountriesByAddressFormatId(int $address_format_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "country` WHERE `address_format_id` = '" . (int)$address_format_id . "'");

		return (int)$query->row['total'];
	}
}
