<?php
namespace Opencart\Catalog\Model\Localisation;
class Country extends \Opencart\System\Engine\Model {
	public function getCountry(int $country_id): array {
		$query = $this->db->query("SELECT *, c.name FROM `" . DB_PREFIX . "country` c LEFT JOIN `" . DB_PREFIX . "address_format` af ON (`c`.`address_format_id` = `af`.`address_format_id`) WHERE `c`.`country_id` = '" . (int)$country_id . "' AND `c`.`status` = '1'");

		return $query->row;
	}

	public function getCountries(): array {
		$country_data = $this->cache->get('country.catalog');

		if (!$country_data) {
			$query = $this->db->query("SELECT *, c.name FROM `" . DB_PREFIX . "country` c LEFT JOIN `" . DB_PREFIX . "address_format` af ON (`c`.`address_format_id` = `af`.`address_format_id`) WHERE `c`.`status` = '1' ORDER BY c.`name` ASC");

			$country_data = $query->rows;

			$this->cache->set('country.catalog', $country_data);
		}

		return $country_data;
	}
}
