<?php
namespace Opencart\Catalog\Model\Localisation;
class Location extends \Opencart\System\Engine\Model {
	public function getLocation(int $location_id): array {
		$query = $this->db->query("SELECT `location_id`, `name`, `address`, `geocode`, `telephone`, `image`, `open`, `comment` FROM `" . DB_PREFIX . "location` WHERE `location_id` = '" . (int)$location_id . "'");

		return $query->row;
	}
}
