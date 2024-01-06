<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Country
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class Country extends \Opencart\System\Engine\Model {
	/**
	 * Get Country
	 *
	 * @param int $country_id
	 *
	 * @return array<string, mixed>
	 */
	public function getCountry(int $country_id): array {
		$query = $this->db->query("SELECT *, `c`.`name` FROM `" . DB_PREFIX . "country` `c` LEFT JOIN `" . DB_PREFIX . "address_format` af ON (`c`.`address_format_id` = `af`.`address_format_id`) WHERE `c`.`country_id` = '" . (int)$country_id . "' AND `c`.`status` = '1'");

		return $query->row;
	}

	/**
	 * Get Country By Iso Code 2
	 *
	 * @param string $iso_code_2
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getCountryByIsoCode2(string $iso_code_2): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($iso_code_2) . "' AND `status` = '1'";

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
	 * Get Country By Iso Code 3
	 *
	 * @param string $iso_code_3
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getCountryByIsoCode3(string $iso_code_3): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_3` = '" . $this->db->escape($iso_code_3) . "' AND `status` = '1'";

		$key = md5($sql);

		$country_data = $this->cache->get('country.' . $key);

		if (!$country_data) {
			$query = $this->db->query($sql);

			$country_data = $query->rows;

			$this->cache->set('country.' . md5($sql), $country_data);
		}

		return $country_data;
	}

	/**
	 * Get Countries
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getCountries(): array {
		$sql = "SELECT *, `c`.`name` FROM `" . DB_PREFIX . "country` `c` LEFT JOIN `" . DB_PREFIX . "address_format` `af` ON (`c`.`address_format_id` = `af`.`address_format_id`) WHERE `c`.`status` = '1' ORDER BY `c`.`name` ASC";

		$key = md5($sql);

		$country_data = $this->cache->get('country.' . $key);

		if (!$country_data) {
			$query = $this->db->query($sql);

			$country_data = $query->rows;

			$this->cache->set('country.' . $key, $country_data);
		}

		return $country_data;
	}
}
