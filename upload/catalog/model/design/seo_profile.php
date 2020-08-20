<?php
namespace Application\Model\Design;
class SeoProfile extends \System\Engine\Model {
	public $profile = array();

	public function getSeoProfilesByKey($key) {
		// Better to cache the data into a variable so the is DB not making to many connections.
		if (!isset($this->profile[$key])) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_profile` WHERE `key` = '" . $this->db->escape((string)$key) . "'");

			$this->profile[$key] = $query->rows;
		}

		return $this->profile[$key];
	}
}