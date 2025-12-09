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
	 * Create a new country record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $country_data = [
	 *     'country_description' => [],
	 *     'iso_code_2'          => 'Country ISO Code 2',
	 *     'iso_code_3'          => 'Country ISO Code 3',
	 *     'address_format_id'   => 1,
	 *     'postcode_required'   => 0,
	 *     'status'              => 0
	 * ];
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $country_id = $this->model_localisation_country->addCountry($country_data);
	 */
	public function addCountry(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "country` SET `iso_code_2` = '" . $this->db->escape((string)$data['iso_code_2']) . "', `iso_code_3` = '" . $this->db->escape((string)$data['iso_code_3']) . "', `address_format_id` = '" . (int)$data['address_format_id'] . "', `postcode_required` = '" . (int)$data['postcode_required'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$country_id = $this->db->getLastId();

		foreach ($data['country_description'] as $language_id => $country_description) {
			$this->model_localisation_country->addDescription($country_id, $language_id, $country_description);
		}

		$this->cache->delete('country');

		return $country_id;
	}

	/**
	 * Edit Country
	 *
	 * Edit country record in the database.
	 *
	 * @param int                  $country_id primary key of the country record
	 * @param array<string, mixed> $data       array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $country_data = [
	 *     'country_description' => [],
	 *     'iso_code_2'          => 'Country ISO Code 2',
	 *     'iso_code_3'          => 'Country ISO Code 3',
	 *     'address_format_id'   => 1,
	 *     'postcode_required'   => 0,
	 *     'status'              => 1
	 * ];
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $this->model_localisation_country->editCountry($country_id, $country_data);
	 */
	public function editCountry(int $country_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "country` SET `iso_code_2` = '" . $this->db->escape((string)$data['iso_code_2']) . "', `iso_code_3` = '" . $this->db->escape((string)$data['iso_code_3']) . "', `address_format_id` = '" . (int)$data['address_format_id'] . "', `postcode_required` = '" . (int)$data['postcode_required'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `country_id` = '" . (int)$country_id . "'");

		$this->model_localisation_country->deleteDescriptions($country_id);

		foreach ($data['country_description'] as $language_id => $country_description) {
			$this->model_localisation_country->addDescription($country_id, $language_id, $country_description);
		}

		$this->cache->delete('country');
	}

	/**
	 * Edit Status
	 *
	 * Edit information status record in the database.
	 *
	 * @param int  $information_id primary key of the information record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/information');
	 *
	 * $this->model_catalog_information->editStatus($information_id, $status);
	 */
	public function editStatus(int $country_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "country` SET `status` = '" . (bool)$status . "' WHERE `country_id` = '" . (int)$country_id . "'");

		$this->cache->delete('country');
	}

	/**
	 * Delete Country
	 *
	 * Delete country record in the database.
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

		$this->model_localisation_country->deleteDescriptions($country_id);

		$this->cache->delete('country');
	}

	/**
	 * Get Country
	 *
	 * Get the record of the country record in the database.
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
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "country` `c` LEFT JOIN `" . DB_PREFIX . "country_description` `cd` ON (`c`.`country_id` = `cd`.`country_id`) WHERE `c`.`country_id` = '" . (int)$country_id . "' AND `cd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Country By Iso Code 2
	 *
	 * Get the record of the country iso code 2 record in the database.
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
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` `c` LEFT JOIN `" . DB_PREFIX . "country_description` `cd` ON (`c`.`country_id` = `cd`.`country_id`) WHERE `c`.`iso_code_3` = '" . $this->db->escape($iso_code_2) . "' AND `cd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Country By Iso Code 3
	 *
	 * Get the record of the country iso code 3 record in the database.
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
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_3` = '" . $this->db->escape($iso_code_3) . "'");

		return $query->row;
	}

	/**
	 * Get Countries
	 *
	 * Get the record of the country records in the database.
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
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT * FROM `" . DB_PREFIX . "country` `c` LEFT JOIN `" . DB_PREFIX . "country_description` `cd` ON (`c`.`country_id` = `cd`.`country_id`) WHERE `cd`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`cd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_iso_code_2'])) {
			$sql .= " AND LCASE(`c`.`iso_code_2`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_iso_code_2']) . '%') . "'";
		}

		if (!empty($data['filter_iso_code_3'])) {
			$sql .= " AND LCASE(`c`.`iso_code_3`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_iso_code_3']) . '%') . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND `c`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$sort_data = [
			'name'       => 'cd.name',
			'iso_code_2' => 'c.iso_code_2',
			'iso_code_3' => 'c.iso_code_3',
			'status'     => 'c.status',
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `cd`.`name`";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Countries
	 *
	 * Get the total number of country records in the database.
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
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "country` `c` LEFT JOIN `" . DB_PREFIX . "country_description` `cd` ON (`c`.`country_id` = `cd`.`country_id`) WHERE `cd`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`cd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (!empty($data['filter_iso_code_2'])) {
			$sql .= " AND LCASE(`c`.`iso_code_2`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_iso_code_2']) . '%') . "'";
		}

		if (!empty($data['filter_iso_code_3'])) {
			$sql .= " AND LCASE(`c`.`iso_code_3`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_iso_code_3']) . '%') . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND `c`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Countries By Address Format ID
	 *
	 * Get the total number of countries by address format records in the database.
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

	/**
	 * Add Description
	 *
	 * Create a new country description record in the database.
	 *
	 * @param int                  $country_id  primary key of the country record
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $country_data['country_description'] = [
	 *     'name'             => 'Country Name'
	 * ];
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $this->model_localisation_country->addDescription($country_id, $language_id, $country_data);
	 */
	public function addDescription(int $country_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "country_description` SET `country_id` = '" . (int)$country_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete country description records in the database.
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $this->model_localisation_country->deleteDescriptions($country_id);
	 */
	public function deleteDescriptions(int $country_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "country_description` WHERE `country_id` = '" . (int)$country_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete country descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $this->model_localisation_country->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "country_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Description
	 *
	 * Get the record of the country description in the database.
	 *
	 * @param int $country_id  primary key of the country record
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<string, mixed> country description record
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $description = $this->model_localisation_country->getDescription($country_id, $language_id);
	 */
	public function getDescription(int $country_id, int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country_description` WHERE `country_id` = '" . (int)$country_id . "' AND `language_id` = '" . (int)$language_id . "'");

		return $query->row;
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the country description records in the database.
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return array<int, array<string, string>> description records that have country ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $country_description = $this->model_localisation_country->getDescriptions($country_id);
	 */
	public function getDescriptions(int $country_id): array {
		$country_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country_description` WHERE `country_id` = '" . (int)$country_id . "'");

		foreach ($query->rows as $result) {
			$country_description_data[$result['language_id']] = $result;
		}

		return $country_description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the country descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/country');
	 *
	 * $results = $this->model_localisation_country->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
