<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Location
 *
 * Can be called using $this->load->model('localisation/location');
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class Location extends \Opencart\System\Engine\Model {
	/**
	 * Get Location
	 *
	 * Get the record of the location record in the database.
	 *
	 * @param int $location_id primary key of the location record
	 *
	 * @return array<string, mixed> location record that has location ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/location');
	 *
	 * $location_info = $this->model_localisation_location->getLocation($location_id);
	 */
	public function getLocation(int $location_id): array {
		$query = $this->db->query("SELECT `location_id`, `name`, `address`, `geocode`, `telephone`, `image`, `open`, `comment` FROM `" . DB_PREFIX . "location` WHERE `location_id` = '" . (int)$location_id . "'");

		return $query->row;
	}
}
