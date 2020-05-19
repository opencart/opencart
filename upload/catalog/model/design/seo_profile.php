<?php
class ModelDesignSeoProfile extends Model {
	public function getSeoProfilesByKey($key) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_profile` WHERE `key` = '" . $this->db->escape((string)$key) . "'");

		return $query->rows;
	}
}