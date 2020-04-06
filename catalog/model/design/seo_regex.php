<?php
class ModelDesignSeoRegex extends Model {
	public function getSeoRegexes() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_regex` ORDER BY `sort_order` ASC");

		return $query->rows;
	}
}